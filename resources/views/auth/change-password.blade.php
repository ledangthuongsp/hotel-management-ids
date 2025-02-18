@extends('adminlte::page')

@section('title', 'Change Password')

@section('content_header')
    <h1>Change Password</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Change Your Password</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('change-password.post') }}">
                @csrf

                <!-- Password fields -->
                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
        </div>
    </div>
@endsection
