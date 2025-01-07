@extends('layouts.app')

@section('page-title', "Create New Article")

@section('content')
<div>
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Oops! Something went wrong.</strong>
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="title" class="form-label">Article Title</label>
                    <input type="text" class="form-control" id="title" name="title" 
                           placeholder="Enter article title" required>
                </div>

                <div class="form-group mb-3">
                    <label for="content" class="form-label">Article Content</label>
                    <div class="input-group">
                        <div style="width: 100%; height: 800px;">
                            <textarea id="content" name="content">{{ old('content') }}</textarea>
                          </div>                                
                    </div>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="attachments" class="form-label">Attachments</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input form-control" id="attachments" 
                               name="attachments[]" multiple>
                        <label class="custom-file-label my-2" for="attachments">
                            Choose multiple files
                        </label>
                    </div>
                    <small class="form-text text-muted">
                        You can select multiple files. Maximum file size: 2MB per file.
                    </small>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="fas fa-save me-2"></i>Create Article
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Custom file input label
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
@endpush