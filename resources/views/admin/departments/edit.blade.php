@extends('layouts.app')

@section('page-title', "Edit Department")

@section('content')
<div class="card shadow">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Oops! Something went wrong.</strong>
                <ul class="mt-2 mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.departments.update', $department->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="name" class="form-label">Department Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $department->name }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="12">{{ $department->description }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    <i class="fas fa-save me-2"></i>Update Department
                </button>
            </div>
        </form>
    </div>
</div>
@endsection