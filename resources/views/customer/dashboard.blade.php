@extends('layouts.customer_layout')
@section('content')
<div class="container-fluid px-4 py-5 bg-light">
    <!-- Hero Section -->
    <div class="row align-items-center g-5 py-5 mb-5 mx-3">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold lh-1 mb-3">Welcome to Tickies Support</h1>
            <p class="lead">Get instant support for all your inquiries. Our team is here to help you with any questions or concerns you may have.</p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <a href="{{ route('customer.tickets.create') }}" class="btn btn-primary btn-lg px-4 me-md-2">
                    <i class="fas fa-ticket-alt me-2"></i>Create New Ticket
                </a>
                <a href="{{ route('customer.tickets.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                    <i class="fas fa-list-alt me-2"></i>View My Tickets
                </a>
            </div>
        </div>
        <div class="col-lg-6">
            <img src="https://lanit.com.vn/wp-content/uploads/2024/01/it-helpdesk.jpg" class="img-fluid rounded-3 shadow" alt="Support illustration">
        </div>
    </div>

    <!-- Articles Section -->
    <div class="row g-4 py-5 mx-3">
        <div class="col-12">
            <h2 class="display-6 fw-bold text-center mb-3">Latest Support Articles</h2>
        </div>
        <div class="col-12 mb-4">
            <form action="{{ route('customer.dashboard') }}" method="GET" class="d-flex justify-content-center">
                <div class="input-group" style="max-width: 600px;">
                    <input class="form-control" type="search" name="query" placeholder="Search articles..." aria-label="Search" value="{{ request('query') }}">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
        @foreach($articles as $article)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow">
                <div class="card-body d-flex flex-column p-4">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-primary me-2">Article</span>
                        <small class="text-muted">{{ $article->created_at->format('M d, Y') }}</small>
                    </div>
                    <h3 class="h3 card-title mb-3">{{ $article->title }}</h3>
                    <div class="card-text flex-grow-1 mb-4 article-content">
                        {!! Str::limit($article->content, 1000) !!}
                    </div>
                    <div class="mt-auto">
                        <a href="{{ route('articles.show', $article) }}" class="btn btn-link text-primary p-0 read-more" style="font-weight: bold;">Read More</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center my-4">
        {{ $articles->links('pagination::bootstrap-4') }}
    </div>

    <!-- Quick Support Section -->
    <div class="row g-4 py-5 mx-3">
        <div class="col-12">
            <h2 class="display-6 fw-bold text-center mb-5">Quick Support Options</h2>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body p-4">
                    <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3 mx-auto" style="width: 60px; height: 60px; line-height: 60px;">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="h5">Search Knowledge Base</h3>
                    <p class="text-muted">Find answers to common questions in our comprehensive knowledge base.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body p-4">
                    <div class="feature-icon bg-success bg-gradient text-white rounded-circle mb-3 mx-auto" style="width: 60px; height: 60px; line-height: 60px;">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="h5">Live Chat Support</h3>
                    <p class="text-muted">Connect with our support team instantly through live chat.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body p-4">
                    <div class="feature-icon bg-info bg-gradient text-white rounded-circle mb-3 mx-auto" style="width: 60px; height: 60px; line-height: 60px;">
                        <i class="fas fa-video"></i>
                    </div>
                    <h3 class="h5">Video Tutorials</h3>
                    <p class="text-muted">Watch our helpful video tutorials for step-by-step guidance.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Article Content -->
{{-- <div class="modal fade" id="articleModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <div class="attachments-section w-100">
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection

@section('styles')
<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
}

.article-content {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 8;
    -webkit-box-orient: vertical;
    text-overflow: ellipsis;
}

.article-content img {
    max-width: 100%;
    height: auto;
}

.feature-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.read-more {
    text-decoration: none;
}

.read-more:hover {
    text-decoration: underline;
}

/* Custom scrollbar for modal */
.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endsection

@section('scripts')
{{-- <script>document.addEventListener('DOMContentLoaded', function() {
    // Clean up empty paragraphs
    document.querySelectorAll('.article-content').forEach(function(element) {
        element.querySelectorAll('p:empty').forEach(p => p.remove());
    });

    // Handle Read More clicks
    document.querySelectorAll('.read-more').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.card');
            const title = card.querySelector('.card-title').textContent;
            const content = card.querySelector('.article-content').innerHTML;
            const attachments = card.querySelector('.attachments-data').innerHTML; // Add a hidden div with attachments
            
            const modal = new bootstrap.Modal(document.getElementById('articleModal'));
            document.querySelector('#articleModal .modal-title').textContent = title;
            document.querySelector('#articleModal .modal-body').innerHTML = content;
            document.querySelector('#articleModal .attachments-section').innerHTML = attachments;
            modal.show();
        });
    });
}); --}}
</script>
@endsection