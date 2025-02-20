<!-- Modal for Editing User -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Avatar -->
                <div class="text-center mb-3">
                    <img id="edit_avatar_preview" src="/images/default_avatar.png" class="rounded-circle" width="100" height="100">
                    <br>
                    <input type="file" id="edit_avatar" name="edit_avatar" accept="image/*" class="mt-2">
                </div>
                <form id="editUserForm">
                    <input type="hidden" id="edit_user_id">
                    <div class="form-group">
                        <label for="edit_first_name">First Name</label>
                        <input type="text" class="form-control" id="edit_first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_last_name">Last Name</label>
                        <input type="text" class="form-control" id="edit_last_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_user_name">Username</label>
                        <input type="text" class="form-control" id="edit_user_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" class="form-control" id="edit_email" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_role_id">Role</label>
                        <select class="form-control" id="edit_role_id" required>
                            <option value="">--Select Role--</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_day_of_birth">Date of Birth</label>
                        <input type="date" class="form-control" id="edit_day_of_birth">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveUser()">Save Changes</button>
            </div>
        </div>
    </div>
</div>
<script>
     // Preview avatar khi chọn ảnh
     document.getElementById('edit_avatar').addEventListener('change', function(event) {
        let reader = new FileReader();
        reader.onload = function() {
            document.getElementById('edit_avatar_preview').src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    });
</script>