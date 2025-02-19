@extends('adminlte::page')

@section('title', 'Hotel List')

@section('content_header')
    <h1>User List</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Tab Content -->
            <div class="tab-content mt-3" id="tab-content">
                <!-- Hotels Tab -->
                <div class="tab-pane fade show active" id="hotels-tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">List of Users</h3>
                            <button class="btn btn-success float-right" onclick="">Add New User</button>
                        </div>
                        <div class="card-body">
                            <!-- Search -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="dropdown">
                                        <select id="filter-role" class="form-control">
                                            <option value="">--Select Role--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="filter-user-name" class="form-control" placeholder="Name">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-primary" onclick="searchHotels()">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                </div>
                            </div>

                            <!-- Table -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Hotel Name</th>
                                        <th>Email</th>
                                        <th>Telephone</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="hotels-list">
                                    <tr><td colspan="7">Loading...</td></tr>
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <div class="mt-3">
                                <button id="prev-page" class="btn btn-secondary" onclick="changePage(-1)">Previous</button>
                                <span id="current-page" class="mx-3">Page 1</span>
                                <button id="next-page" class="btn btn-secondary" onclick="changePage(1)">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createHotelModal" tabindex="-1" role="dialog" aria-labelledby="createHotelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createHotelModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createUserForm">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="createUser()">Save User</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function fetchAllUser()
        {

        }
        function fetchUserByFilter()
        {

        }
        function createUser()
        {

        }
        function viewUser()
        {

        }
        function editUser()
        {

        }
        function deleteUser()
        {
            
        }
    </script>
@endsection