@extends('Dashboard.layouts._main')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <h1>Users</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="editUserId">
                        <div class="form-group">
                            <label for="editFirstName">First Name</label>
                            <input type="text" class="form-control" id="editFirstName">
                        </div>
                        <div class="form-group">
                            <label for="editLastName">Last Name</label>
                            <input type="text" class="form-control" id="editLastName">
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" class="form-control" id="editEmail">
                        </div>
                        <div class="form-group">
                            <label for="editPhoneNumber">Phone Number</label>
                            <input type="text" class="form-control" id="editPhoneNumber">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateUser()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="userProductModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="productModalLabel">Product Table</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

@endsection

@section('scripts')
    <script>
        const baseUrl = "http://localhost:8000";
        const token = localStorage.getItem('token') || window.location.replace('/login');//|| "Bearer 7|UGGhLXcpXV14kv32YDAq5LywJo2ZGxpKC7BTY2LYdfcde415";

        function deleteUser(userId) {
            $.ajax({
                url: `${baseUrl}/api/dashboard/users/${userId}`,
                type: 'DELETE',
                headers: {
                    "Authorization": token
                },
                success: function(response) {
                    console.log('User deleted:', response);
                    loadUsers();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        
        function updateUser(userId) {
            const productId = $('#update-product-id').val();
            const formData = new FormData();
            formData.append('name', $('#update-product-name').val());
            formData.append('description', $('#update-product-description').val());
            formData.append('image', $('#update-product-image')[0].files[0]);

            $.ajax({
                url: `${baseUrl}/api/users/${userId}/edit`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "Authorization": token
                },
                success: function(response) {
                    console.log('User updated successfully:', response);
                    loadProducts(`http://localhost:8000/api/dashboard/users`);
                    $('#editFirstName').val('');
                    $('#editLastName').val('');
                    $('#editEmail').val('');
                    $('#editPhoneNumber').val('');
                    $('#updateModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error('Error updating User:', error, xhr);
                }
            });
        }


        function openChangePasswordModal(userId) {
            $('#changePasswordModal #password-user-id').val('');
            $('#changePasswordModal #new-password').val('');
            $('#changePasswordModal #password-user-id').val(userId);
            $('#changePasswordModal').modal('show');
        }

        $('#changePasswordModal').on('submit', '#change-password-form', function(e) {
            e.preventDefault();
            var userId = $('#changePasswordModal #password-user-id').val();
            var newPassword = $('#changePasswordModal #new-password').val();
            var confirmPassword = $('#changePasswordModal #confirm-password').val();

            if (newPassword !== confirmPassword) {
                alert("Passwords do not match.");
                return;
            }
            updatePassword(userId, newPassword);
        });

        function updatePassword(userId, newPassword) {
            $.ajax({
                url: `${baseUrl}/api/dashboard/users/${userId}/password`,
                type: 'PUT',
                headers: {
                    "Authorization": token
                },
                data: {
                    password: newPassword
                },
                success: function(response) {
                    console.log('Password updated:', response);
                    $('#changePasswordModal').modal('hide');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function openUserProductsModal(userId) {
            $.ajax({
                url: `${baseUrl}/api/dashboard/users/${userId}/products`,
                type: 'GET',
                headers: {
                    "Authorization": token
                },
                success: function(response) {
                    var products = response;

                    var modalContent = '';
                    products.forEach(function(product) {
                        modalContent += `
                        <tr>
                            <td><img src="${product.image}" alt="Product 2" width="50"></td>
                            <td>${product.name}</td>
                            <td>${product.description}</td>
                        </tr>
                        `;
                    });

                    $('#userProductModal .modal-body').html(modalContent);

                    $('#userProductModal').modal('show');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
        $(document).ready(function() {
            $.ajax({
                url: `${baseUrl}/api/dashboard/users`,
                type: 'GET',
                headers: {
                    "Authorization": token
                },
                success: function(response) {
                    var users = response;

                    var userRows = '';
                    users.forEach(function(user) {
                        userRows += '<tr data-user="' + user.id + '">' +
                            '<td>' + user.first_name + '</td>' +
                            '<td>' + user.last_name + '</td>' +
                            '<td>' + user.email + '</td>' +
                            '<td>' +
                            '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateModal" data-user-id="${user.id}">Edit</button>' +
                            '<button class="btn btn-sm btn-danger delete-user">Delete</button>' +
                            '<button class="btn btn-sm btn-secondary product-modal">Products</button>' +
                            '</td>' +
                            '</tr>';
                    });

                    $('tbody').html(userRows);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });

            $(document).on('click', '.delete-user', function() {
                var userId = $(this).closest('tr').data('user');
                deleteUser(userId);
            });

            $(document).on('click', '.edit-user', function() {
                var userId = $(this).closest('tr').data('user');
                openEditUserModal(userId);
            });

            $(document).on('click', '.product-modal', function() {
                var userId = $(this).closest('tr').data('user');
                openUserProductsModal(userId);
            });

            $(document).on('click', '.change-password', function() {
                var userId = $(this).closest('tr').data('user');
                openChangePasswordModal(userId);
            });
        });
    </script>
@endsection
