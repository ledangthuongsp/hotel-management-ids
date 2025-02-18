@extends('adminlte::page')

@section('title', 'Hotel List')

@section('content_header')
    <h1>Hotel List</h1>
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
                            <h3 class="card-title">List of Hotels</h3>
                            <button class="btn btn-success float-right" onclick="">Add New Hotel</button>
                        </div>
                        <div class="card-body">
                            <!-- Search -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="dropdown">
                                        <input type="text" id="search-city" class="form-control" placeholder="Search City" oninput="">
                                        <select id="filter-city" class="form-control">
                                            <option value="">--Select City--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="filter-hotel-code" class="form-control" placeholder="Hotel Code">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="filter-hotel-name" class="form-control" placeholder="Hotel Name">
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
                                        <th></th>
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
                    <h5 class="modal-title" id="createHotelModalLabel">Add New Hotel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createHotelForm">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="hotel-name">Hotel Name</label>
                                <input type="text" class="form-control" id="hotel-name" placeholder="Enter Hotel Name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="hotel-code">Hotel Code</label>
                                <input type="text" class="form-control" id="hotel-code" placeholder="Enter Hotel Code">
                            </div>
                        </div>
    
                        <!-- City -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="hotel-city">City</label>
                                <select class="form-control" id="hotel-city">
                                    <option value="">--Select City--</option>
                                </select>
                            </div>
    
                            <!-- District -->
                            <div class="form-group col-md-6">
                                <label for="hotel-district">District</label>
                                <select class="form-control" id="hotel-district">
                                    <option value="">--Select District--</option>
                                </select>
                            </div>
                        </div>
    
                        <!-- Ward -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="hotel-ward">Ward</label>
                                <select class="form-control" id="hotel-ward">
                                    <option value="">--Select Ward--</option>
                                </select>
                            </div>
                        </div>
    
                        <!-- Other Fields -->
                        <div class="form-group">
                            <label for="hotel-address-1">Address (Street + Number)</label>
                            <input type="text" class="form-control" id="hotel-address-1" placeholder="Enter Street Number and Name">
                        </div>
    
                        <div class="form-group">
                            <label for="hotel-address-2">Address (Optional)</label>
                            <input type="text" class="form-control" id="hotel-address-2" placeholder="Enter Additional Address">
                        </div>
    
                        <div class="form-group">
                            <label for="hotel-email">Email</label>
                            <input type="email" class="form-control" id="hotel-email" placeholder="Enter Email">
                        </div>
                        <div class="form-group">
                            <label for="hotel-telephone">Telephone</label>
                            <input type="text" class="form-control" id="hotel-telephone" placeholder="Enter Telephone">
                        </div>
                        <div class="form-group">
                            <label for="hotel-fax">Fax (Optional)</label>
                            <input type="text" class="form-control" id="hotel-fax" placeholder="Enter Fax">
                        </div>
                        <div class="form-group">
                            <label for="hotel-name-jp">Hotel Name (Japanese)</label>
                            <input type="text" class="form-control" id="hotel-name-jp" placeholder="Enter Hotel Name in Japanese">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="createHotel()">Save Hotel</button>
                </div>
            </div>
        </div>
    </div>
    