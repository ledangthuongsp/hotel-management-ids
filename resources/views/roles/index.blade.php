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
                <button class="btn btn-success float-right" data-toggle="modal" data-target="#createRoleModal">
                    Create New Role
                </button>
            </div>
            <div class="card-body">
                <div id="alertMessage"></div>

                <table class="table table-bordered" id="roleTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Role Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr id="role_{{ $role->id }}">
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">Update</a>
                                    <button class="btn btn-danger btn-sm delete-role" data-id="{{ $role->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('modals.create_role')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".delete-role").forEach(button => {
                button.addEventListener("click", function () {
                    let roleId = this.getAttribute("data-id");
    
                    if (!confirm("Bạn có chắc chắn muốn xóa role này?")) {
                        return;
                    }
    
                    fetch(`/roles/${roleId}`, {
                        method: "DELETE",
                        headers: {
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Lỗi: " + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) {
                            document.getElementById("alertMessage").innerHTML = 
                                `<div class="alert alert-danger">${data.error}</div>`;
                        } else {
                            document.getElementById("alertMessage").innerHTML = 
                                `<div class="alert alert-success">${data.message}</div>`;
                            document.getElementById(`role_${roleId}`).remove();
                        }
                    })
                    .catch(error => {
                        console.error("Error deleting role:", error);
                        document.getElementById("alertMessage").innerHTML = 
                            `<div class="alert alert-danger">Không thể xóa role. Lỗi: ${error.message}</div>`;
                    });
                });
            });
        });
    </script>
    
@endsection
