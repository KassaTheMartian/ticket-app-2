@extends('layouts.customer_layout')
@section('page-title', 'Ticket Handle')
@section('content')
<style>
    .message-content img {
    max-width: 100%;
    height: auto;
    }
</style>
<div class="container py-4">
    <div class="row g-3">
        <!-- Ticket Information Section -->
        <div class="col-md-12">
            <div class="card border-0 shadow-lg mb-2">
                <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white rounded">
                    <h5 class="mb-0">
                        <i class="fas fa-ticket-alt me-2"></i>Ticket Details
                    </h5>
                    <button class="btn btn-outline-light" type="button" data-bs-toggle="collapse" data-bs-target="#ticketDetails" aria-expanded="false" aria-controls="ticketDetails">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="collapse" id="ticketDetails">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-2 position-relative">
                                <label for="ticket_id" class="form-label position-absolute top-0 start-4 bg-white px-2" 
                                       style="z-index: 10; transform: translateY(-50%);">Ticket ID</label>
                                <input type="text" class="form-control rounded pt-3" id="ticket_id" name="ticket_id" value="{{ $ticket->id }}" readonly>
                            </div>

                            <div class="col-md-10 position-relative">
                                <label for="title" class="form-label position-absolute top-0 start-4 bg-white px-2" 
                                       style="z-index: 10; transform: translateY(-50%);">Title</label>
                                <input type="text" class="form-control rounded pt-3" id="title" name="title" value="{{ $ticket->title }}" readonly>
                            </div>

                            <div class="col-md-2 position-relative">
                                <label for="ticket_type_id" class="form-label position-absolute top-0 start-4 bg-white px-2" 
                                       style="z-index: 10; transform: translateY(-50%);">Ticket Type</label>
                                <input type="text" class="form-control rounded pt-3" id="ticket_type_id" name="ticket_type_id" value="{{ $ticket->ticketType->name }}" readonly>
                            </div>
                            
                            <div class="col-md-2 position-relative">
                                <label for="department" class="form-label position-absolute top-0 start-4 bg-white px-2" 
                                       style="z-index: 10; transform: translateY(-50%);">Department</label>
                                <input type="text" class="form-control rounded pt-3" id="department" name="department" 
                                       value="{{ ucfirst($ticket->department->name) }}" readonly>
                            </div>

                            <div class="col-md-2 position-relative">
                                <label for="customer_id" class="form-label position-absolute top-0 start-4 bg-white px-2" 
                                       style="z-index: 10; transform: translateY(-50%);">Customer</label>
                                <input type="text" class="form-control rounded pt-3" id="customer_id" name="" value="{{ $ticket->customer->name }}" readonly>
                            </div>
                            <div class="col-md-2 position-relative">
                                <label for="priority" class="form-label position-absolute top-0 start-4 bg-white px-2" 
                                       style="z-index: 10; transform: translateY(-50%);">Priority</label>
                                <input type="text" class="form-control rounded pt-3" id="priority" name="priority" value="{{ ucfirst($ticket->priority) }}" readonly>
                            </div>
                            <div class="col-md-2 position-relative">
                                <label for="resolved_at" class="form-label position-absolute top-0 start-4 bg-white px-2" 
                                       style="z-index: 10; transform: translateY(-50%);">Closed At</label>
                                <input type="text" class="form-control rounded pt-3" id="resolved_at" name="resolved_at" value="{{ $ticket->closed_at }}" readonly>
                            </div>
                            <div class="col-md-2 position-relative">
                                <label for="status" class="form-label position-absolute top-0 start-4 bg-white px-2" 
                                       style="z-index: 10; transform: translateY(-50%);">Status</label>
                                <input type="text" class="form-control rounded pt-3" id="status" name="status" value="{{ ucfirst($ticket->status) }}" readonly>
                            </div>
                            <div class="col-md-6 position-relative">
                                <label for="description" class="form-label position-absolute top-0 start-4 bg-white px-2" 
                                       style="z-index: 10; transform: translateY(-50%);">Description</label>
                                <div class="form-control rounded pt-3 overflow-auto" id="description" name="description" style="height: 315px" readonly>{!! $ticket->description !!}</div>
                            </div>
                            <div class="card col-md-6" style="max-height: 315px">
                                <div class="card-header">
                                    <h4 class="h6 mb-0">Current Attachments</h4>
                                </div>
                                <div class="card-body p-0" style="max-height: 190px; overflow-y: auto;">
                                    @if($ticket->attachments->count() > 0)
                                        <ul class="list-group list-group-flush">
                                            @foreach($ticket->attachments as $attachment)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <a href="{{ route('admin.attachments.show', $attachment->id) }}" 
                                                    target="_blank" 
                                                    class="text-decoration-none">
                                                        <i class="fas fa-file me-2"></i>
                                                        {{ $attachment->filename }}
                                                    </a>
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
        <!-- Chat Section -->
        <div class="col-md-12">
            <div class="card border-0 shadow-lg ">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="fas fa-comments me-2"></i>
                    <h5 class="mb-0">Conversation</h5>
                </div>
                <div class="card-body d-flex flex-column p-0" style="min-height: 125vh;">
                    <div class="chat-messages overflow-auto pt-2" style="flex: 1; background-color: #f4f6f9; max-height: 75vh;" id="chat-messages">
                        @foreach ($ticket->messages as $message)
                            <div class="message-wrapper mb-4">
                                <div class="d-flex align-items-start {{ $message->sender_type == 'App\\Models\\Customer' ? 'flex-row-reverse' : '' }}">
                                    <img src="{{ asset('storage/' . ($message->sender_type == 'App\\Models\\Customer' ? $message->sender->profile_picture : $message->sender->profile_picture)) }}" 
                                         alt="Avatar" 
                                         class="rounded-circle shadow-sm me-3 ms-3" 
                                         style="width: 45px; height: 45px; object-fit: cover;"
                                         onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($message->sender->name) }}&background=0D8ABC&color=fff';">
                                    
                                    <div class="message-content 
                                        {{ $message->sender_type == 'App\\Models\\Customer' ? 'border border-success bg-light text-dark' : 'border border-primary bg-light text-dark' }} 
                                        rounded-4 p-3 shadow-sm" 
                                        style="max-width: 80%; min-width: 100px;">
                                        <div class="d-flex justify-content-between mb-2">
                                            @if ($message->sender_type == 'App\\Models\\Customer')
                                                <small class="opacity-50">{{ $message->created_at->format('h:i A') }}</small>
                                                <small class="fw-bold opacity-75 ms-2">{{ $message->sender->name }}</small>
                                            @else
                                                <small class="fw-bold opacity-75 me-2">{{ $message->sender->name }}</small>
                                                <small class="opacity-50">{{ $message->created_at->format('h:i A') }}</small>
                                            @endif
                                        </div>
                                        <div class="mb-0">{!! $message->message !!}</div>
                                        <div class="d-flex flex-wrap mt-2">
                                            @if($message->attachments->count() > 0)
                                                @foreach($message->attachments as $attachment)
                                                    <a href="{{ route('admin.attachments.show', $attachment->id) }}" 
                                                        target="_blank" 
                                                        class=" p-2 m-1 border border-primary rounded text-decoration-none d-flex align-items-center">
                                                        <i class="fas fa-file me-2"></i>
                                                        {{ Str::limit($attachment->filename, 20) }}
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="message-input border-top p-3 bg-white rounded">
                        <form action="{{ route('customer.messages.send') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                            <div class="input-group">
                                <div style="width: 100%; height: 300px;">
                                    <textarea id="content" name="message"></textarea>
                                </div>   
                            </div>
                            <div class="col-6 position-relative mt-3">
                                <label for="attachments" class="form-label position-absolute top-0 start-4 bg-white px-2" 
                                    style="z-index: 10; transform: translateY(-50%);">Attachments</label>
                                <input type="file" class="form-control rounded pt-3" id="attachments" name="attachments[]" multiple>
                                <small class="form-text text-muted">You can upload multiple files</small>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3" style=" z-index: 9999;">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card border-0 shadow-lg">
            <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white rounded">
                <h5 class="mb-0">
                <i class="fas fa-history me-2"></i>Ticket History
                </h5>
                <button class="btn btn-outline-light" type="button" data-bs-toggle="collapse" data-bs-target="#ticketHistory" aria-expanded="false" aria-controls="ticketHistory">
                <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="collapse" id="ticketHistory">
                <div class="card-body p-0">
                <ul class="list-group list-group-flush" style="height: 390px; overflow-y: auto;">
                    @foreach ($ticket->logs as $log)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="w-100">
                        <div class="d-flex justify-content-between">
                            <small class="opacity-50">{{ $log->created_at->format('M d, Y h:i A') }}</small>
                            <small class="opacity-50">{{ $log->user ? $log->user->name : 'N/A' }}</small>
                        </div>
                        <p class="mb-0">{{ $log->action }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var chatMessages = document.getElementById('chat-messages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
});
</script>
@endsection