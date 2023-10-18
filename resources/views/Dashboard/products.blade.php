@extends('Dashboard.layouts._main')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <input type="hidden" id="productIdInput">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Create Product
        </button>

        <table id="productsTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>

            </tfoot>
        </table>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-field">
                            <input type="text" id="product-name" placeholder="Name" />
                            <label for="product-name">Name</label>
                        </div>
                        <div class="input-field">
                            <input type="file" id="product-image" placeholder="Image URL" />
                            <label for="product-image">Image</label>
                        </div>
                        <div class="input-field">
                            <textarea id="product-description" class="materialize-textarea" placeholder="Description"></textarea>
                            <label for="product-description">Description</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="createProduct()" class="btn btn-primary">create</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignModalLabel">Assign Product to User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id="userIdInput" class="form-control" placeholder="User ID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="assignProduct()">Assign</button>
                </div>
            </div>
        </div>
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
                    <input type="hidden" id="update-product-id">
                    <div class="form-group">
                        <label for="update-product-name">Name</label>
                        <input type="text" class="form-control" id="update-product-name" placeholder="Product Name">
                    </div>
                    <div class="form-group">
                        <label for="update-product-description">Description</label>
                        <textarea class="form-control" id="update-product-description" placeholder="Product Description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="update-product-image">Image</label>
                        <input type="file" class="form-control-file" id="update-product-image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateProduct()">Update</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script defer>
        const baseUrl = "http://localhost:8000";
        const token = localStorage.getItem('token') || window.location.replace('/login');//|| "Bearer 7|UGGhLXcpXV14kv32YDAq5LywJo2ZGxpKC7BTY2LYdfcde415";
        const searchParams = new URLSearchParams(window.location.search);
        let currentPage = parseInt(searchParams.get('page')) || 1;

        loadProducts(`${baseUrl}/api/products?page=${currentPage}`);

        function setId(id) {
            $('#update-product-id').val(id);
        };

        function createProduct() {
            const formData = new FormData();
            formData.append('name', $('#product-name').val());
            formData.append('description', $('#product-description').val());
            formData.append('image', $('#product-image')[0].files[0]);

            $.ajax({
                url: baseUrl + '/api/products',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "Authorization": token
                },
                success: function(response) {
                    console.log('Product created successfully:', response);
                    $('#product-name').val('');
                    $('#product-description').val('');
                    $('#exampleModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error('Error creating product:', error);
                }
            });
        }

        function updateProduct() {
            const productId = $('#update-product-id').val();
            const formData = new FormData();
            formData.append('name', $('#update-product-name').val());
            formData.append('description', $('#update-product-description').val());
            formData.append('image', $('#update-product-image')[0].files[0]);

            $.ajax({
                url: `${baseUrl}/api/products/1/edit`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "Authorization": token
                },
                success: function(response) {
                    console.log('Product updated successfully:', response);
                    loadProducts(`${baseUrl}/api/products?page=${currentPage}`);
                    $('#update-product-name').val('');
                    $('#update-product-description').val('');
                    $('#updateModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error('Error updating product:', error, xhr);
                }
            });
        }

        function loadProducts(url) {
            const getPage = new URLSearchParams(url.split('?')[1]);
            currentPage = getPage.get('page') || 1;

            const queryParams = new URLSearchParams(window.location.search);

            queryParams.set("page", currentPage);

            history.replaceState(null, null, "?" + queryParams.toString());

            $('#productsTable tbody').empty();


            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                headers: {
                    "Authorization": token
                },
                success: function(data) {
                    const products = data.data;
                    products.forEach(function(product) {
                        const row = `<tr>
                                <td>${product.name}</td>
                                <td>${product.description}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#updateModal" data-product-id="${product.id}"
                                        onclick="setId(${product.id})">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="deleteProduct(${product.id})">Delete</button>
                                    <button type="button" onclick="openAssignModal(${product.id})">Assign Product</button>
                                </td>
                            </tr>`;
                        $('#productsTable tbody').append(row);
                    });

                    handlePagination(data.links);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function openAssignModal(productId) {
            $('#assignModal').modal('show');
            $('#productIdInput').val(productId);
        }

        function assignProduct() {
            const userId = $('#userIdInput').val();
            const productId = $('#productIdInput').val();

            $.ajax({
                url: baseUrl + '/api/products/assign',
                type: 'PATCH',
                data: JSON.stringify({
                    user_id: userId,
                    product_id: productId
                }),
                dataType: 'json',
                contentType: 'application/json',
                headers: {
                    "Authorization": token
                },
                success: function(response) {
                    console.log('Product assigned successfully:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error assigning product:', error);
                }
            });
        }

        function deleteProduct(productId) {
            if (confirm("Are you sure you want to delete this product?")) {
                $.ajax({
                    url: `${baseUrl}/api/products/${productId}`,
                    type: 'DELETE',
                    headers: {
                        "Authorization": token
                    },
                    success: function(response) {
                        console.log('Product deleted successfully:', response);
                        loadProducts(`${baseUrl}/api/products?page=${currentPage}`);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting product:', error);
                    }
                });
            }
        }

        function handlePagination(links) {
            const nextUrl = links.next;
            const prevUrl = links.prev;
            const lastUrl = links.last;
            const firstUrl = links.first;

            deleteLinks();

            if (firstUrl) {
                const firstButton =
                    `<button type="button" class="btn btn-primary" onclick="loadProducts('${firstUrl}')">First</button>`;
                $('#productsTable tfoot').append(firstButton);
            }

            if (nextUrl) {
                const nextButton =
                    `<button type="button" class="btn btn-primary" onclick="loadProducts('${nextUrl}')">Next</button>`;
                $('#productsTable tfoot').append(nextButton);
            }

            if (prevUrl) {
                const prevButton =
                    `<button type="button" class="btn btn-primary" onclick="loadProducts('${prevUrl}')">Previous</button>`;
                $('#productsTable tfoot').append(prevButton);
            }

            if (lastUrl) {
                const lastButton =
                    `<button type="button" class="btn btn-primary" onclick="loadProducts('${lastUrl}')">Last</button>`;
                $('#productsTable tfoot').append(lastButton);
            }
        }

        function deleteLinks() {
            const e = document.querySelector("#productsTable tfoot");

            let child = e.lastElementChild;
            while (child) {
                e.removeChild(child);
                child = e.lastElementChild;
            }
        }
    </script>
@endsection
