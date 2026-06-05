{{-- Tombol aksi untuk DataTables Tridharma (Pengajaran/Pengabdian) --}}
<div class="d-flex gap-1">
    <a href="{{ route('admin.tridharma.edit', [$type, $berita->id]) }}" class="btn btn-sm btn-info" title="Edit">
        <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-pencil') }}"></use></svg>
    </a>
    <button type="button"
        class="btn btn-sm btn-danger btn-delete"
        data-url="{{ route('admin.tridharma.destroy', [$type, $berita->id]) }}"
        data-nama="{{ $berita->judul }}"
        title="Hapus">
        <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-trash') }}"></use></svg>
    </button>
</div>
