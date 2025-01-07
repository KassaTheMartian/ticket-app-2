@extends('layouts.customer_layout')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header" style="background-color: #3498DB; color: white;" class="d-flex align-items-center">
            <h3 class="card-title mb-0 fw-bold">Ticket create</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('customer.tickets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-12">
                        <div class="form-floating mb-1">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Ticket Title" value="{{ old('title') }}" required>
                            <label for="title" class="text-muted">Ticket Title</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-1">
                            <select class="form-select" id="ticket_type_id" name="ticket_type_id" required>
                                <option value="">Select Ticket Type</option>
                                @foreach ($ticketTypes as $id => $name)
                                    <option value="{{ $id }}" {{ old('ticket_type_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            <label for="ticket_type_id" class="text-muted">Ticket Type</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-1">
                            <select class="form-select" id="department_id" name="department_id" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $id => $name)
                                    <option value="{{ $id }}" {{ old('department_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            <label for="department_id" class="text-muted">Department</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div>
                            <label for="attachments" class="form-label">Attachments</label>
                            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                            <small class="form-text text-muted">You can upload multiple files</small>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="content" class="form-label mb-1">Description</label>
                        <div class="input-group">
                            <div style="width: 100%; height: 400px;">
                                <textarea id="content" name="description">{{ old('description') }}</textarea>
                            </div>   
                        </div>
                        @if ($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif
                    </div>
                </div>
                {!! ReCaptcha::htmlFormSnippet() !!}
                @if ($errors->has('g-recaptcha-response'))
                    <span class="text-danger">Authentication Failed</span>
                @endif

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill" style="background-color: #3498DB; border-color: #3498DB;">
                        <i class="bi bi-send me-2"></i>Create Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection