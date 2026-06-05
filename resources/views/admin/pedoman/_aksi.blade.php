<div class="d-flex gap-1">
    {{-- Toggle Aktif/Nonaktif (quick action, AJAX) --}}
    <button type="button"
            class="btn btn-sm btn-toggle-active {{ $pedoman->is_active ? 'btn-outline-success' : 'btn-outline-secondary' }}"
            data-url="{{ route('admin.pedoman.toggle-active', $pedoman->id) }}"
            data-nama="{{ $pedoman->nama_file }}"
            data-current="{{ $pedoman->is_active ? '1' : '0' }}"
            title="{{ $pedoman->is_active ? 'Klik untuk nonaktifkan' : 'Klik untuk aktifkan' }}">
        <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#' . ($pedoman->is_active ? 'cil-check-circle' : 'cil-circle')) }}"></use></svg>
    </button>

    {{-- Edit --}}
    <a href="{{ route('admin.pedoman.edit', $pedoman->id) }}" class="btn btn-sm btn-info" title="Edit">
        <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-pencil') }}"></use></svg>
    </a>

    {{-- Hapus --}}
    <button type="button" class="btn btn-sm btn-danger btn-delete"
            data-url="{{ route('admin.pedoman.destroy', $pedoman->id) }}"
            data-nama="{{ $pedoman->nama_file }}" title="Hapus">
        <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-trash') }}"></use></svg>
    </button>
</div>
