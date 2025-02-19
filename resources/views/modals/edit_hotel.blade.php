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
                    <input type="hidden" id="edit-hotel-id" name="hotel_id"> <!-- Hidden input for Hotel ID -->
                    
                    <!-- Hotel Name -->
                    <div class="form-group">
                        <label for="edit-hotel-name">Hotel Name</label>
                        <input type="text" id="edit-hotel-name" name="hotel_name" class="form-control" required>
                    </div>

                    <!-- Hotel Name (JP) -->
                    <div class="form-group">
                        <label for="edit-hotel-name-jp">Hotel Name (JP)</label>
                        <input type="text" id="edit-hotel-name-jp" name="hotel_name_jp" class="form-control">
                    </div>

                    <!-- Hotel Code -->
                    <div class="form-group">
                        <label for="edit-hotel-code">Hotel Code</label>
                        <input type="text" id="edit-hotel-code" name="hotel_code" class="form-control" required>
                    </div>

                    <!-- User ID -->
                    <div class="form-group">
                        <label for="edit-hotel-user-id">User ID</label>
                        <input type="number" id="edit-hotel-user-id" name="hotel_user_id" class="form-control" required>
                    </div>

                    <!-- City ID -->
                    <div class="form-group">
                        <label for="edit-hotel-city-id">City ID</label>
                        <input type="number" id="edit-hotel-city-id" name="hotel_city_id" class="form-control" required>
                    </div>

                    <!-- Hotel Email -->
                    <div class="form-group">
                        <label for="edit-hotel-email">Hotel Email</label>
                        <input type="email" id="edit-hotel-email" name="hotel_email" class="form-control" required>
                    </div>

                    <!-- Hotel Telephone -->
                    <div class="form-group">
                        <label for="edit-hotel-telephone">Hotel Telephone</label>
                        <input type="text" id="edit-hotel-telephone" name="hotel_telephone" class="form-control" required>
                    </div>

                    <!-- Hotel Fax -->
                    <div class="form-group">
                        <label for="edit-hotel-fax">Hotel Fax</label>
                        <input type="text" id="edit-hotel-fax" name="hotel_fax" class="form-control">
                    </div>

                    <!-- Hotel Address 1 -->
                    <div class="form-group">
                        <label for="edit-hotel-address-1">Hotel Address 1</label>
                        <input type="text" id="edit-hotel-address-1" name="hotel_address_1" class="form-control" required>
                    </div>

                    <!-- Hotel Address 2 -->
                    <div class="form-group">
                        <label for="edit-hotel-address-2">Hotel Address 2</label>
                        <input type="text" id="edit-hotel-address-2" name="hotel_address_2" class="form-control">
                    </div>

                    <!-- Tax Code (Required) -->
                    <div class="form-group">
                        <label for="edit-hotel-tax-code">Tax Code</label>
                        <input type="text" class="form-control" id="edit-hotel-tax-code" name ="edit-hotel-tax-code" required>
                    </div>
                    <!-- Company Name (Required) -->
                    <div class="form-group">
                        <label for="edit-hotel-company-name">Company Name</label>
                        <input type="text" class="form-control" id="edit-hotel-company-name" name="edit-hotel-company-name" required>
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
