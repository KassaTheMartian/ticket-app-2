@extends('layouts.app')

@section('page-title', 'Edit Article')

@section('content')
<div>
    <div class="row justify-content-center">
        <div class="">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Oops! Some errors occurred.</strong>
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.articles.update', $article) }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Article Title</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $article->title) }}" 
                                   required
                                   placeholder="Enter article title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Article Content</label>
                            <div class="input-group">
                                <div style="width: 100%; height: 800px;">
                                    <textarea id="content" name="content">{{ old('content', $article->content) }}</textarea>
                                  </div>                                
                            </div>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="attachments" class="form-label">
                                Add New Attachments 
                                <small class="text-muted">(Multiple files allowed)</small>
                            </label>
                            <input type="file" 
                                   class="form-control" 
                                   id="attachments" 
                                   name="attachments[]" 
                                   multiple>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Update Article
                            </button>
                        </div>
                    </form>
                    <div class="card mb-3 mt-3">
                        <div class="card-header">
                            <h4 class="h5 mb-0">Current Attachments</h4>
                        </div>
                        <div class="card-body p-0">
                            @if($article->attachments->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach($article->attachments as $attachment)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <a href="{{ route('admin.attachments.show', $attachment->id) }}" 
                                               target="_blank" 
                                               class="text-decoration-none">
                                                <i class="fas fa-file me-2"></i>
                                                {{ $attachment->filename }}
                                            </a>
                                            <form action="{{ route('admin.attachments.destroy', $attachment) }}" 
                                                  method="POST" 
                                                  class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this attachment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete btn-danger btn-sm">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center text-muted p-3">
                                    No attachments uploaded
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: "Confirm Deletion",
                text: "Are you sure you want to delete this attachment?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, Delete",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.closest('form').submit();
                }
            });
        });
    });
});
</script>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Optional: Custom file input label
        const fileInput = document.getElementById('attachments');
        fileInput.addEventListener('change', function() {
            const fileName = this.files.length > 0 
                ? `${this.files.length} file(s) selected` 
                : 'Choose files';
            this.nextElementSibling?.querySelector('.custom-file-label')?.textContent = fileName;
        });
    });
</script>
@endsection