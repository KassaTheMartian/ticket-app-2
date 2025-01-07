@extends('layouts.app')
<?php 
    $code = isset($code) ? $code : 403;
    $title = isset($title) ? $title : 'Forbidden';
    $message = isset($message) ? $message : 'You do not have permission to access this resource!';
?>
@section('content')
    <div class="container error-container" style="text-align: center; margin-top: 50px;">
        <h1 style="font-size: 72px; color: #ff0000;">{{ $code }}: {{ $title }}</h1>
        <h2 style="font-size: 24px; color: #333;">{{ $message }}</h2>
        <p style="margin-top: 20px;"></p>
        <a href="#" class="btn" style="background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Go Back</a>
    </div>
@endsection
