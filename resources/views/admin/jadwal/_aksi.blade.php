<div class="d-flex gap-1">
    {{-- Toggle Aktif/Nonaktif (quick action, AJAX) --}}
    <button type="button"
            class="btn btn-sm btn-toggle-active {{ $jadwal->is_active ? 'btn-outline-success' : 'btn-outline-secondary' }}"
            data-url="{{ route('admin.jadwal.toggle-active', $jadwal->id) }}"
            data-nama="{{ $jadwal->program_studi }} ({{ $jadwal->tahun_ajaran }} {{ $jadwal->semester }})"
            data-current="{{ $jadwal->is_active ? '1' : '0' }}"
            title="{{ $jadwal->is_active ? 'Klik untuk nonaktifkan' : 'Klik untuk aktifkan' }}">
        <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#' . ($jadwal->is_active ? 'cil-check-circle' : 'cil-circle')) }}"></use></svg>
    </button>

    {{-- Edit --}}
    <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="btn btn-sm btn-info" title="Edit">
        <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-pencil') }}"></use></svg>
    </a>

    {{-- Hapus --}}
    <button type="button" class="btn btn-sm btn-danger btn-delete"
        data-url="{{ route('admin.jadwal.destroy', $jadwal->id) }}"
        data-nama="{{ $jadwal->program_studi }}" title="Hapus">
        <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-trash') }}"></use></svg>
    </button>
</div>
