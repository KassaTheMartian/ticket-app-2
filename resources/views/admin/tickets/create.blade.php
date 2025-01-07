@extends('layouts.app')

@section('page-title', "Create Ticket")

@section('content')
<div>
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

            <form action="{{ route('admin.tickets.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="ticket_type_id" class="form-label">Ticket Type</label>
                            <select class="form-control" id="ticket_type_id" name="ticket_type_id" required>
                                <option value="">Select Ticket Type</option>
                                @foreach ($ticketTypes as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="department_id" class="form-label">Department</label>
                            <select class="form-control selectpicker" id="department_id" name="department_id" data-live-search="true" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select class="form-control" id="customer_id" name="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach ($customers as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-control" id="priority" name="priority" required>
                                <option value="">Select Priority</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control p-2" id="description" name="description" rows="15" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="fas fa-save me-2"></i>Create Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
