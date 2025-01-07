@extends('layouts.customer_layout')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <!-- Article Header -->
                <div class="card-header bg-white border-bottom">
                    <div class="mb-2">
                        <span class="badge bg-primary text-white rounded-pill px-3">
                            Article
                        </span>
                    </div>
                    <h3 class="card-title h4 fw-bold mb-0">
                        {{ $article->title }}
                    </h3>
                </div>
                
                <!-- Article Content -->
                <div class="card-body">
                    <div class="article-content">
                        {!! $article->content !!}
                    </div>
                </div>

                <!-- Attachments Section -->
                @if($article->attachments->count() > 0)
                <div class="card-footer bg-light">
                    <h4 class="h5 fw-bold mb-3">Attachments</h4>
                    <ul class="list-unstyled mb-0">
                        @foreach($article->attachments as $attachment)
                            <li class="mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-paperclip text-secondary me-2"></i>
                                    <a href="{{ route('admin.attachments.show', $attachment->id) }}" 
                                       class="text-decoration-none link-primary"
                                       target="_blank">
                                        {{ $attachment->filename }}
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for article content */
.article-content {
    font-size: 1rem;
    line-height: 1.6;
    color: #212529;
}

.article-content p {
    margin-bottom: 1.2rem;
}

.article-content h1,
.article-content h2,
.article-content h3,
.article-content h4,
.article-content h5,
.article-content h6 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.article-content img {
    max-width: 100%;
    height: auto;
    margin: 1rem 0;
    border-radius: 0.375rem;
}

.article-content ul,
.article-content ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.article-content blockquote {
    padding: 1rem;
    margin: 1rem 0;
    border-left: 4px solid #dee2e6;
    background-color: #f8f9fa;
}

/* Hover effect for attachment links */
.card-footer a:hover {
    text-decoration: underline !important;
}
</style>

@endsection