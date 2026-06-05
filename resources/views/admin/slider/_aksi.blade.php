{{-- Tombol aksi untuk DataTables --}}
<div class="d-flex gap-1">
    <a href="{{ route('admin.slider.edit', $slider->id) }}" class="btn btn-sm btn-info" title="Edit">
        <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-pencil') }}"></use></svg>
    </a>
    <button type="button"
        class="btn btn-sm btn-danger btn-delete"
        data-url="{{ route('admin.slider.destroy', $slider->id) }}"
        data-nama="{{ $slider->judul }}"
        title="Hapus">
        <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-trash') }}"></use></svg>
    </button>
</div>
