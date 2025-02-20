<!-- Modal for Viewing User Details -->
<div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserModalLabel">View User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="display: flex; justify-content: center; align-items: center;">
                    <img id="view_avatar" width="150" height="150" class="rounded-circle" alt="Avatar">
                </div>
                
                <div class="form-group">
                    <label for="view_first_name">First Name</label>
                    <p id="view_first_name"></p>
                </div>
                <div class="form-group">
                    <label for="view_last_name">Last Name</label>
                    <p id="view_last_name"></p>
                </div>
                <div class="form-group">
                    <label for="view_user_name">Username</label>
                    <p id="view_user_name"></p>
                </div>
                <div class="form-group">
                    <label for="view_email">Email</label>
                    <p id="view_email"></p>
                </div>
                <div class="form-group">
                    <label for="view_role">Role</label>
                    <p id="view_role"></p>
                </div>
                <div class="form-group">
                    <label for="view_day_of_birth">Date of Birth</label>
                    <p id="view_day_of_birth"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
