<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProgramStudiRequest;
use App\Models\ProgramStudi;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProgramStudiController extends Controller
{
    public function index()
    {
        return view('admin.program-studi.index');
    }

    public function datatable()
    {
        $query = ProgramStudi::query()->select('program_studis.*');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('no_sk', function ($p) {
                if (! $p->no_sk) {
                    return '<span class="text-body-secondary">-</span>';
                }

                return '<span class="small text-body-secondary" title="'
                    .e($p->no_sk).'">'.e($p->no_sk).'</span>';
            })
            ->addColumn('kedaluwarsa', function ($p) {
                if (! $p->tanggal_kedaluwarsa) {
                    return '<span class="text-body-secondary">-</span>';
                }
                $tgl = $p->tanggal_kedaluwarsa->format('d-m-Y');
                $status = $p->getStatusKedaluwarsa();
                $icon = $status['color'] === 'success'
                    ? '<span class="text-success">●</span>'
                    : '<span class="text-danger">●</span>';

                return '<div class="d-flex align-items-center gap-2">'
                    .$icon.'<span>'.$tgl.'</span></div>';
            })
            ->addColumn('bukti', function ($p) {
                $items = [];

                // Bukti #1 — sertifikat fisik (PDF/JPG/PNG)
                if ($p->sertifikat) {
                    $ext = strtoupper(pathinfo($p->sertifikat, PATHINFO_EXTENSION));
                    $href = asset('storage/'.$p->sertifikat);
                    $items[] = '<a href="'.$href.'" target="_blank" '
                        .'class="badge bg-success-subtle text-success-emphasis border border-success-subtle text-decoration-none d-inline-flex align-items-center gap-1" '
                        .'title="Lihat sertifikat ('.$ext.')">'
                        .'<i class="bi bi-file-earmark-check"></i>'.e($ext).'</a>';
                }

                // Bukti #2 — verifikasi eksternal (PDDikti/BAN-PT/dll)
                if ($p->verifikasi_url) {
                    $label = e($p->getVerifikasiLabel() ?? 'Verifikasi');
                    $items[] = '<a href="'.e($p->verifikasi_url).'" target="_blank" rel="noopener" '
                        .'class="badge bg-info-subtle text-info-emphasis border border-info-subtle text-decoration-none d-inline-flex align-items-center gap-1" '
                        .'title="Verifikasi resmi pemerintah di '.$label.'">'
                        .'<i class="bi bi-patch-check"></i>'.$label.'</a>';
                }

                // Empty state — keduanya kosong
                if (empty($items)) {
                    return '<span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">'
                        .'<i class="bi bi-x-circle me-1"></i>Belum lengkap</span>';
                }

                return '<div class="d-flex flex-column gap-1 align-items-start">'
                    .implode('', $items).'</div>';
            })
            ->addColumn('status', fn ($p) => $p->is_active
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-secondary">Nonaktif</span>')
            ->addColumn('aksi', fn ($p) => view('admin.program-studi._aksi', ['prodi' => $p])->render())
            ->rawColumns(['no_sk', 'kedaluwarsa', 'bukti', 'status', 'aksi'])
            ->toJson();
    }

    public function create()
    {
        return view('admin.program-studi.create');
    }

    public function store(ProgramStudiRequest $request)
    {
        $data = $request->validated();
        unset($data['hapus_sertifikat']);
        if ($request->hasFile('sertifikat')) {
            $data['sertifikat'] = $request->file('sertifikat')->store('prodi/sertifikat', 'public');
        }
        $prodi = ProgramStudi::create($data);
        activity()->causedBy(auth()->user())->performedOn($prodi)->log('Menambahkan prodi: '.$prodi->nama);

        return redirect()->route('admin.program-studi.index')->with('success', 'Program Studi berhasil ditambahkan!');
    }

    public function edit(ProgramStudi $program_studi)
    {
        $prodi = $program_studi;

        return view('admin.program-studi.edit', compact('prodi'));
    }

    public function update(ProgramStudiRequest $request, ProgramStudi $program_studi)
    {
        $prodi = $program_studi;
        $data = $request->validated();
        $hapusSertifikat = (bool) ($data['hapus_sertifikat'] ?? false);
        unset($data['hapus_sertifikat']);

        // Upload baru selalu mengalahkan flag hapus (user mengganti file lama).
        if ($request->hasFile('sertifikat')) {
            if ($prodi->sertifikat && Storage::disk('public')->exists($prodi->sertifikat)) {
                Storage::disk('public')->delete($prodi->sertifikat);
            }
            $data['sertifikat'] = $request->file('sertifikat')->store('prodi/sertifikat', 'public');
        } elseif ($hapusSertifikat && $prodi->sertifikat) {
            if (Storage::disk('public')->exists($prodi->sertifikat)) {
                Storage::disk('public')->delete($prodi->sertifikat);
            }
            $data['sertifikat'] = null;
        }

        $prodi->update($data);
        activity()->causedBy(auth()->user())->performedOn($prodi)->log('Mengubah prodi: '.$prodi->nama);

        return redirect()->route('admin.program-studi.index')->with('success', 'Program Studi berhasil diperbarui!');
    }

    public function destroy(ProgramStudi $program_studi)
    {
        $nama = $program_studi->nama;

        // Hapus sertifikat dari storage
        if ($program_studi->sertifikat && Storage::disk('public')->exists($program_studi->sertifikat)) {
            Storage::disk('public')->delete($program_studi->sertifikat);
        }

        $program_studi->delete();
        activity()->causedBy(auth()->user())->log('Menghapus prodi: '.$nama);

        return response()->json(['success' => true, 'message' => 'Program Studi berhasil dihapus!']);
    }
}
