<!-- Modal Edit Hotel -->
<div class="modal fade" id="editHotelModal" tabindex="-1" role="dialog" aria-labelledby="editHotelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editHotelModalLabel">Edit Hotel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form to edit hotel -->
                <form id="edit-hotel-form">
                    <input type="hidden" id="edit-hotel-id"> <!-- Hidden input for Hotel ID -->

                    <!-- Hotel Name -->
                    <div class="form-group">
                        <label for="edit-hotel-name">Hotel Name</label>
                        <input type="text" class="form-control" id="edit-hotel-name" required>
                        <small class="text-danger error-message" id="error-edit-hotel-name"></small>
                    </div>

                    <!-- Hotel Code -->
                    <div class="form-group">
                        <label for="edit-hotel-code">Hotel Code</label>
                        <input type="text" class="form-control" id="edit-hotel-code" required>
                        <small class="text-danger error-message" id="error-edit-hotel-code"></small>
                    </div>

                    <!-- City Name -->
                    <div class="form-group">
                        <label for="edit-hotel-city">City</label>
                        <select class="form-control" id="edit-hotel-city">
                            <option value="">--Select City--</option>
                        </select>
                        <small class="text-danger error-message" id="error-edit-hotel-city"></small>
                    </div>

                    <!-- Hotel Email -->
                    <div class="form-group">
                        <label for="edit-hotel-email">Hotel Email</label>
                        <input type="email" class="form-control" id="edit-hotel-email" required>
                        <small class="text-danger error-message" id="error-edit-hotel-email"></small>
                    </div>

                    <!-- Hotel Telephone -->
                    <div class="form-group">
                        <label for="edit-hotel-telephone">Hotel Telephone</label>
                        <input type="text" class="form-control" id="edit-hotel-telephone" required>
                        <small class="text-danger error-message" id="error-edit-hotel-telephone"></small>
                    </div>

                    <!-- Tax Code -->
                    <div class="form-group">
                        <label for="edit-hotel-tax-code">Tax Code</label>
                        <input type="text" class="form-control" id="edit-hotel-tax-code" required>
                        <small class="text-danger error-message" id="error-edit-hotel-tax-code"></small>
                    </div>

                    <!-- Company Name -->
                    <div class="form-group">
                        <label for="edit-hotel-company-name">Company Name</label>
                        <input type="text" class="form-control" id="edit-hotel-company-name" required>
                        <small class="text-danger error-message" id="error-edit-hotel-company-name"></small>
                    </div>

                    <!-- Optional Fields -->
                    <div class="form-group">
                        <label for="edit-hotel-address-1">Address 1</label>
                        <input type="text" class="form-control" id="edit-hotel-address-1" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-hotel-address-2">Address 2 (Optional)</label>
                        <input type="text" class="form-control" id="edit-hotel-address-2">
                    </div>

                    <div class="form-group">
                        <label for="edit-hotel-fax">Fax (Optional)</label>
                        <input type="text" class="form-control" id="edit-hotel-fax">
                    </div>

                    <div class="form-group">
                        <label for="edit-hotel-name-jp">Hotel Name (JP) (Optional)</label>
                        <input type="text" class="form-control" id="edit-hotel-name-jp">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveHotel()">Save changes</button>
            </div>
        </div>
    </div>
</div>
