<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JadwalRequest;
use App\Models\Jadwal;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class JadwalController extends Controller
{
    /**
     * Halaman index admin: tabel + Quick Stats + Filter Bar.
     *
     * Stats dihitung dari DB sekali (lightweight query, dataset kecil).
     * Filter options di-derive dari data existing — agar dropdown filter
     * hanya menampilkan tahun/prodi yang relevan.
     */
    public function index()
    {
        // Stats untuk widget di atas DataTable.
        $tahunAktif = Jadwal::active()
            ->orderByDesc('tahun_ajaran')
            ->value('tahun_ajaran');

        $stats = [
            'tahun_aktif' => $tahunAktif,
            'total' => Jadwal::count(),
            'aktif' => Jadwal::where('is_active', true)->count(),
            'tahun_unique' => Jadwal::distinct('tahun_ajaran')->count('tahun_ajaran'),
        ];

        // Opsi filter dropdown — hanya yang ada di data.
        $listTahunAjaran = Jadwal::distinct()
            ->orderByDesc('tahun_ajaran')
            ->pluck('tahun_ajaran');

        $listProdi = ProgramStudi::orderBy('jenjang')
            ->orderBy('nama')
            ->get(['id', 'nama', 'jenjang']);

        return view('admin.jadwal.index', compact('stats', 'listTahunAjaran', 'listProdi'));
    }

    public function datatable(Request $request)
    {
        // Filter dari toolbar (server-side, ditangani lewat query string).
        $jadwals = Jadwal::with('programStudi')
            ->when($request->filled('tahun_ajaran'),
                fn ($q) => $q->where('tahun_ajaran', $request->string('tahun_ajaran')))
            ->when($request->filled('semester'),
                fn ($q) => $q->where('semester', $request->string('semester')))
            ->when($request->filled('status') && $request->string('status')->toString() !== '',
                fn ($q) => $q->where('is_active', $request->string('status')->toString() === 'aktif'))
            ->when($request->filled('program_studi_id'),
                fn ($q) => $q->where('program_studi_id', $request->integer('program_studi_id')))
            ->orderByDesc('tahun_ajaran')
            ->orderByRaw("CASE semester WHEN 'Genap' THEN 1 WHEN 'Ganjil' THEN 2 ELSE 3 END")
            ->latest('id')
            ->get();

        return DataTables::of($jadwals)
            ->addIndexColumn()
            // Custom column nama prodi — hindari collision JSON antara DB column
            // `program_studi` (string) dan relasi `programStudi` (yg ter-snake_case
            // jadi `program_studi` saat serialize → menimpa string column).
            ->addColumn('prodi_display', function ($j) {
                return e($j->nama_prodi); // pakai accessor model: jenjang + nama
            })
            ->addColumn('semester_badge', function ($j) {
                $color = $j->semester === Jadwal::SEMESTER_GENAP ? 'info' : 'warning';
                // Icon numerik kontekstual akademik (sama seperti frontend):
                // Ganjil → 1-circle-fill, Genap → 2-circle-fill
                $icon = $j->semester === Jadwal::SEMESTER_GENAP ? '2-circle-fill' : '1-circle-fill';

                return '<span class="badge bg-'.$color.'-subtle text-'.$color.'-emphasis border border-'.$color.'-subtle">'
                    .'<i class="bi bi-'.$icon.' me-1"></i>'.e($j->semester).'</span>';
            })
            ->addColumn('status', function ($j) {
                return $j->is_active
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-secondary">Nonaktif</span>';
            })
            ->addColumn('file_link', function ($j) {
                return '<a href="'.asset('storage/'.$j->file_path).'" target="_blank" class="btn btn-sm btn-info"><i class="cil-cloud-download"></i> Unduh</a>';
            })
            ->addColumn('aksi', function ($j) {
                return view('admin.jadwal._aksi', ['jadwal' => $j])->render();
            })
            ->rawColumns(['semester_badge', 'status', 'file_link', 'aksi'])
            ->toJson();
    }

    public function create()
    {
        $listProdi = ProgramStudi::where('is_active', true)
            ->orderBy('jenjang')
            ->orderBy('nama')
            ->get(['id', 'nama', 'jenjang']);

        return view('admin.jadwal.create', compact('listProdi'));
    }

    public function store(JadwalRequest $request)
    {
        $data = $this->resolveProgramStudi($request->validated());

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('jadwal', 'public');
        }

        $jadwal = Jadwal::create($data);

        activity()->causedBy(auth()->user())->performedOn($jadwal)
            ->log('Menambahkan jadwal: '.$jadwal->program_studi);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit(Jadwal $jadwal)
    {
        $listProdi = ProgramStudi::where('is_active', true)
            ->orderBy('jenjang')
            ->orderBy('nama')
            ->get(['id', 'nama', 'jenjang']);

        return view('admin.jadwal.edit', compact('jadwal', 'listProdi'));
    }

    public function update(JadwalRequest $request, Jadwal $jadwal)
    {
        $data = $this->resolveProgramStudi($request->validated());

        if ($request->hasFile('file_path')) {
            if ($jadwal->file_path && Storage::disk('public')->exists($jadwal->file_path)) {
                Storage::disk('public')->delete($jadwal->file_path);
            }
            $data['file_path'] = $request->file('file_path')->store('jadwal', 'public');
        } elseif ($request->boolean('hapus_file')) {
            if ($jadwal->file_path && Storage::disk('public')->exists($jadwal->file_path)) {
                Storage::disk('public')->delete($jadwal->file_path);
            }
            $data['file_path'] = null;
        }

        $jadwal->update($data);

        activity()->causedBy(auth()->user())->performedOn($jadwal)
            ->log('Mengubah jadwal: '.$jadwal->program_studi);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Sinkronkan field `program_studi` (string) dengan FK `program_studi_id`.
     *
     * Jika admin memilih dari dropdown (program_studi_id terisi), maka
     * `program_studi` string di-derive dari relasi (jenjang + nama)
     * untuk konsistensi tampilan tanpa perlu query relasi setiap kali.
     */
    private function resolveProgramStudi(array $data): array
    {
        if (! empty($data['program_studi_id'])) {
            $prodi = ProgramStudi::find($data['program_studi_id']);
            if ($prodi) {
                $data['program_studi'] = $prodi->jenjang.' - '.$prodi->nama;
            }
        }

        return $data;
    }

    public function destroy(Jadwal $jadwal)
    {
        $nama = $jadwal->program_studi;

        if ($jadwal->file_path && Storage::disk('public')->exists($jadwal->file_path)) {
            Storage::disk('public')->delete($jadwal->file_path);
        }

        $jadwal->delete();

        activity()->causedBy(auth()->user())->log('Menghapus jadwal: '.$nama);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus!']);
    }

    /**
     * Toggle status aktif/nonaktif via AJAX (no page reload).
     *
     * Quick action di kolom Aksi DataTable — admin bisa cepat
     * aktifkan/nonaktifkan jadwal saat ganti periode tanpa buka form Edit.
     */
    public function toggleActive(Jadwal $jadwal)
    {
        $jadwal->update(['is_active' => ! $jadwal->is_active]);

        $statusLabel = $jadwal->is_active ? 'diaktifkan' : 'dinonaktifkan';
        activity()->causedBy(auth()->user())->performedOn($jadwal)
            ->log("Jadwal {$statusLabel}: {$jadwal->program_studi} ({$jadwal->tahun_ajaran} {$jadwal->semester})");

        return response()->json([
            'success' => true,
            'is_active' => $jadwal->is_active,
            'message' => 'Jadwal berhasil '.$statusLabel.'.',
        ]);
    }
}
