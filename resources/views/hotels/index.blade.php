@extends('adminlte::page')

@section('title', 'Hotel Management')

@section('content_header')
    <h1>Hotel Management</h1>
@endsection

@section('content')
    <form method="GET" action="{{ route('hotels.index') }}">
        <div class="input-group mb-3">
            <input type="text" name="search" class="form-control" placeholder="Search hotel by name, code, city">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>City</th>
                <th>Owner</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hotels as $hotel)
            <tr>
                <td>{{ $hotel->name }}</td>
                <td>{{ $hotel->code }}</td>
                <td>{{ $hotel->city->name }}</td>
                <td>{{ $hotel->owner->user_name }}</td>
                <td>
                    <a href="{{ route('hotels.edit', $hotel->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('hotels.destroy', $hotel->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
