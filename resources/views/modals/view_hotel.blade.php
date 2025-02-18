<!-- Modal View Hotel -->
<div class="modal fade" id="viewHotelModal" tabindex="-1" role="dialog" aria-labelledby="viewHotelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewHotelModalLabel">Hotel Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>City</th>
                            <td id="hotel-city"></td>
                        </tr>
                        <tr>
                            <th>Hotel Code</th>
                            <td id="hotel-code"></td>
                        </tr>
                        <tr>
                            <th>Hotel Name (EN)</th>
                            <td id="hotel-name-en"></td>
                        </tr>
                        <tr>
                            <th>Hotel Name (JP)</th>
                            <td id="hotel-name-jp"></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td id="hotel-email"></td>
                        </tr>
                        <tr>
                            <th>Telephone</th>
                            <td id="hotel-telephone"></td>
                        </tr>
                        <tr>
                            <th>Fax</th>
                            <td id="hotel-fax"></td>
                        </tr>
                        <tr>
                            <th>Address 1</th>
                            <td id="hotel-address1"></td>
                        </tr>
                        <tr>
                            <th>Address 2</th>
                            <td id="hotel-address2"></td>
                        </tr>
                        <tr>
                            <th>Company Name</th>
                            <td id="hotel-company"></td>
                        </tr>
                        <tr>
                            <th>Tax Code</th>
                            <td id="hotel-tax-code"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function viewHotel(hotelId) {
        fetch(`/api/hotels/${hotelId}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(hotel => {
            document.getElementById('hotel-city').innerText = hotel.city_id || 'N/A';
            document.getElementById('hotel-code').innerText = hotel.code || 'N/A';
            document.getElementById('hotel-name-en').innerText = hotel.name || 'N/A';
            document.getElementById('hotel-name-jp').innerText = hotel.name_jp || 'N/A';
            document.getElementById('hotel-email').innerText = hotel.email || 'N/A';
            document.getElementById('hotel-telephone').innerText = hotel.telephone || 'N/A';
            document.getElementById('hotel-fax').innerText = hotel.fax || 'N/A';
            document.getElementById('hotel-address1').innerText = hotel.address_1 || 'N/A';
            document.getElementById('hotel-address2').innerText = hotel.address_2 || 'N/A';
            document.getElementById('hotel-company').innerText = hotel.company_name || 'N/A';
            document.getElementById('hotel-tax-code').innerText = hotel.tax_code || 'N/A';

            $('#viewHotelModal').modal('show');
        })
        .catch(error => {
            console.error('Error fetching hotel details:', error);
            alert('Failed to load hotel details');
        });
    }
</script>
