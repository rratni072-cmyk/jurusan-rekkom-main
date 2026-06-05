<div class="d-flex gap-1">
    <a href="{{ route('admin.tipe-kegiatan.edit', $tipe->id) }}" class="btn btn-sm btn-info" title="Edit"><svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-pencil') }}"></use></svg></a>
    <button type="button" class="btn btn-sm btn-danger btn-delete" data-url="{{ route('admin.tipe-kegiatan.destroy', $tipe->id) }}" data-nama="{{ $tipe->label }}" title="Hapus"><svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-trash') }}"></use></svg></button>
</div>
