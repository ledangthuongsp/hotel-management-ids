<!-- Modal Create Role -->
<div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRoleModalLabel">Create New Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createRoleForm">
                    @csrf
                    <div class="form-group">
                        <label for="name">Role Name</label>
                        <input type="text" class="form-control" id="role_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="role_description" name="description" required></textarea>
                    </div>
                    <button type="button" class="btn btn-success" onclick="submitCreateRole()">Save Role</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
                <!-- Hiển thị thông báo lỗi -->
                <div id="create-role-error" class="alert alert-danger mt-2 d-none"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function submitCreateRole() {
        let roleName = document.getElementById('role_name').value;
        let roleDescription = document.getElementById('role_description').value;
        let errorDiv = document.getElementById('create-role-error');

        fetch('/api/roles', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "Authorization": "Bearer " + localStorage.getItem("token")
            },
            body: JSON.stringify({ name: roleName, description: roleDescription })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                errorDiv.innerText = data.error;
                errorDiv.classList.remove('d-none');
            } else {
                $('#createRoleModal').modal('hide'); // Đóng modal sau khi tạo thành công
                document.getElementById('role_name').value = ''; 
                document.getElementById('role_description').value = ''; 
                fetchRoles(); // Load lại danh sách roles
            }
        })
        .catch(error => {
            errorDiv.innerText = "An error occurred!";
            errorDiv.classList.remove('d-none');
            console.error("Error creating role:", error);
        });
    }
</script>
