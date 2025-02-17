<div class="modal fade" id="createHotelModal" tabindex="-1" aria-labelledby="createHotelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createHotelModalLabel">Add New Hotel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="create-hotel-form">
                    <div class="form-group">
                        <label for="hotel-name">Hotel Name</label>
                        <input type="text" class="form-control" id="hotel-name" required>
                    </div>
                    <div class="form-group">
                        <label for="hotel-email">Email</label>
                        <input type="email" class="form-control" id="hotel-email" required>
                    </div>
                    <div class="form-group">
                        <label for="hotel-phone">Telephone</label>
                        <input type="text" class="form-control" id="hotel-phone" required>
                    </div>
                    <div class="form-group">
                        <label for="hotel-address">Address</label>
                        <input type="text" class="form-control" id="hotel-address" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('create-hotel-form').addEventListener('submit', function(event) {
        event.preventDefault();
        
        fetch('/api/hotels', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            body: JSON.stringify({
                name: document.getElementById('hotel-name').value,
                email: document.getElementById('hotel-email').value,
                telephone: document.getElementById('hotel-phone').value,
                address_1: document.getElementById('hotel-address').value
            })
        }).then(response => response.json())
        .then(() => {
            $('#createHotelModal').modal('hide');
            fetchHotels();
        });
    });
</script>
