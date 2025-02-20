@extends('adminlte::page')

@section('title', 'Role Management')

@section('content_header')
    <h1>Role Settings</h1>
@endsection

@section('content')
    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">List Role</div>
                <!-- Nút mở modal thay vì điều hướng -->
                <button class="btn btn-success float-right" data-toggle="modal" data-target="#createRoleModal">
                    Create New Role
                </button>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Role Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <!-- Nút edit -->
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">Update</a>
                                    
                                    <!-- Nút delete -->
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa role này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Include modal create_role -->
    @include('modals.create_role')
    <script>
        function fetchRoles() {
            fetch('/api/roles', {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "Authorization": "Bearer " + localStorage.getItem("token")
                }
            })
            .then(response => response.json())
            .then(data => {
                let roleTableBody = document.querySelector("table tbody");
                roleTableBody.innerHTML = ""; // Xóa danh sách cũ
    
                if (data.length === 0) {
                    roleTableBody.innerHTML = '<tr><td colspan="3">No roles found.</td></tr>';
                    return;
                }
    
                data.forEach(role => {
                    roleTableBody.innerHTML += `
                        <tr>
                            <td>${role.id}</td>
                            <td>${role.name}</td>
                            <td>
                                <a href="/roles/${role.id}/edit" class="btn btn-warning btn-sm">Update</a>
                                <form action="/roles/${role.id}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa role này?')" style="display:inline;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    `;
                });
            })
            .catch(error => console.error("Error fetching roles:", error));
        }
    
        // Gọi fetchRoles() khi trang load
        document.addEventListener("DOMContentLoaded", function() {
            fetchRoles();
        });
    </script>
    
@endsection
