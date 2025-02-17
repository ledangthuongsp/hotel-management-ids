<div class="modal fade" id="editHotelModal" tabindex="-1" aria-labelledby="editHotelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editHotelModalLabel">Edit Hotel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-hotel-form">
                    <input type="hidden" id="edit-hotel-id">
                    <div class="form-group">
                        <label for="edit-hotel-name">Hotel Name</label>
                        <input type="text" class="form-control" id="edit-hotel-name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-hotel-email">Email</label>
                        <input type="email" class="form-control" id="edit-hotel-email" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-hotel-phone">Telephone</label>
                        <input type="text" class="form-control" id="edit-hotel-phone" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-hotel-address">Address</label>
                        <input type="text" class="form-control" id="edit-hotel-address" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editHotel(id) {
        fetch(`/api/hotels/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit-hotel-id').value = data.id;
                document.getElementById('edit-hotel-name').value = data.name;
                document.getElementById('edit-hotel-email').value = data.email;
                document.getElementById('edit-hotel-phone').value = data.telephone;
                document.getElementById('edit-hotel-address').value = data.address_1;
                $('#editHotelModal').modal('show');
            });
    }

    document.getElementById('edit-hotel-form').addEventListener('submit', function(event) {
        event.preventDefault();
        let hotelId = document.getElementById('edit-hotel-id').value;

        fetch(`/api/hotels/${hotelId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            body: JSON.stringify({
                name: document.getElementById('edit-hotel-name').value,
                email: document.getElementById('edit-hotel-email').value,
                telephone: document.getElementById('edit-hotel-phone').value,
                address_1: document.getElementById('edit-hotel-address').value
            })
        }).then(response => response.json())
        .then(() => {
            $('#editHotelModal').modal('hide');
            fetchHotels();
        });
    });
</script>
