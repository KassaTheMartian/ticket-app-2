@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ticket Details</h1>
    <div class="card">
        <div class="card-header">
            <h2>{{ $ticket->title }}</h2>
        </div>
        <div class="card-body">
            <p><strong>Description:</strong> {{ $ticket->description }}</p>
            <p><strong>Type:</strong> {{ $ticket->ticketType->name }}</p>
            <p><strong>Customer:</strong> {{ $ticket->customer->name }}</p>
            <p><strong>Department:</strong> {{ $ticket->department->name }}</p>
            <p><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($ticket->status) }}</p>
            <p><strong>Created At:</strong> {{ $ticket->created_at->format('d-m-Y H:i') }}</p>
            <p><strong>Updated At:</strong> {{ $ticket->updated_at->format('d-m-Y H:i') }}</p>
            <h3>Ticket Logs</h3>
            <ul>
                @foreach($logs as $log)
                    <li>
                        <p><strong>{{ $log->created_at->format('d-m-Y H:i') }}:</strong> {{ $log->action }}</p>
                    </li>
                @endforeach
            </ul>
            <h3>Attachments</h3>
            <ul>
                @foreach($ticket->attachments as $attachment)
                    <li>
                        <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank">{{ $attachment->filename }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <a href="{{ route('admin.tickets.index') }}" class="btn btn-primary mt-3">Back to Tickets List</a>
</div>
@endsection
