<!-- Modal Create Role -->
<div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRoleModalLabel">Add New Role</h5>
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
        let roleName = document.getElementById('role_name').value.trim();
        let roleDescription = document.getElementById('role_description').value.trim();
        let errorDiv = document.getElementById('create-role-error');

        // Xóa thông báo lỗi trước đó
        errorDiv.innerText = "";
        errorDiv.classList.add('d-none');

        fetch('/api/roles', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "Authorization": "Bearer " + localStorage.getItem("token")
            },
            body: JSON.stringify({ name: roleName, description: roleDescription })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data }))) // Đọc cả status & body
        .then(({ status, body }) => {
            if (status === 201) {
                // Thành công => Ẩn modal, reset form & cập nhật danh sách roles
                $('#createRoleModal').modal('hide');
                document.getElementById('role_name').value = ''; 
                document.getElementById('role_description').value = ''; 
                fetchRoles(); // Load lại danh sách roles
            } else {
                // Thất bại => Hiển thị lỗi từ API
                errorDiv.innerText = body.message || "An error occurred!";
                errorDiv.classList.remove('d-none');
            }
        })
        .catch(error => {
            errorDiv.innerText = "An error occurred! Please try again.";
            errorDiv.classList.remove('d-none');
            console.error("Error creating role:", error);
        });
    }

    function fetchRoles() {
        fetch('/api/roles', {
            method: "GET",
            headers: {
                "Accept": "application/json",
                "Authorization": "Bearer " + localStorage.getItem("token")
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to fetch roles");
            }
            return response.json();
        })
        .then(data => {
            let rolesList = document.getElementById("roles-list"); // ID của danh sách roles trong bảng
            rolesList.innerHTML = "";

            if (!data || data.length === 0) {
                rolesList.innerHTML = "<tr><td colspan='3'>No roles available</td></tr>";
                return;
            }

            data.forEach(role => {
                let row = `<tr>
                    <td>${role.name}</td>
                    <td>${role.description}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editRole(${role.id})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteRole(${role.id})">Delete</button>
                    </td>
                </tr>`;
                rolesList.innerHTML += row;
            });
        })
        .catch(error => {
            console.error("Error fetching roles:", error);
        });
    }

    // Gọi `fetchRoles()` khi trang được tải
    document.addEventListener("DOMContentLoaded", function() {
        fetchRoles();
    });

</script>
