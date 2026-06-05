<div class="d-flex gap-1">
    <a href="{{ route('admin.pesan.show', $pesan->id) }}" class="btn btn-sm btn-primary"
        data-coreui-toggle="tooltip" data-coreui-placement="top" title="Baca Pesan">
        <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-envelope-open') }}"></use></svg>
    </a>
    <button type="button" class="btn btn-sm btn-danger btn-delete"
            data-url="{{ route('admin.pesan.destroy', $pesan->id) }}"
            data-nama="{{ $pesan->nama }}"
            data-coreui-toggle="tooltip" data-coreui-placement="top" title="Hapus Pesan">
        <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-trash') }}"></use></svg>
    </button>
</div>
