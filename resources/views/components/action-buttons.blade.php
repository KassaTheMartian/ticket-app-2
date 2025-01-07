@props(['row', 'editRoute', 'deleteRoute'])

<div>
    <a href="{{ route($editRoute, $row->id) }}" class="btn btn-outline-warning rounded-2 mr-1 btn-sm">
        <i class="fas fa-edit"></i>
    </a>
    <button data-form="deleteForm{{ $row->id }}" class="btn btn-outline-danger btn-delete rounded-2 btn-sm">
        <i class="fas fa-trash"></i>
    </button>
    <form id="deleteForm{{ $row->id }}" action="{{ route($deleteRoute, $row->id) }}" method="POST" class="d-none">
        @method('DELETE')
        @csrf
    </form>
</div>