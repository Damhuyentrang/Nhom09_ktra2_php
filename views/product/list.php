<!-- views/product/list.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-image { max-width: 100px; }
        .form-container { margin-bottom: 30px; }
        .table-container { margin-top: 20px; }
        .pagination { justify-content: center; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Product List</h1>

        <!-- Form tìm kiếm -->
        <form method="GET" action="" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <!-- Danh sách sản phẩm -->
        <div class="table-container">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['id']); ?></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                                <td>
                                    <?php if (!empty($product['image_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image" class="product-image">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($product['description'] ?? 'No description'); ?></td>
                                <td><?php echo htmlspecialchars($product['price']); ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-product" 
                                            data-id="<?php echo $product['id']; ?>" 
                                            data-name="<?php echo htmlspecialchars($product['name']); ?>" 
                                            data-quantity="<?php echo $product['quantity']; ?>" 
                                            data-image_url="<?php echo htmlspecialchars($product['image_url'] ?? ''); ?>" 
                                            data-description="<?php echo htmlspecialchars($product['description'] ?? ''); ?>" 
                                            data-price="<?php echo $product['price']; ?>" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editProductModal">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-product" 
                                            data-id="<?php echo $product['id']; ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>

        <!-- Form thêm sản phẩm và upload hình ảnh (di chuyển xuống dưới cùng) -->
        <div class="form-container card p-4 mt-4">
            <h2 class="h4 mb-3">Add New Product</h2>
            <form id="add-product-form">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Product Name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Quantity" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="Price" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="image_url" class="form-label">Image URL (optional)</label>
                        <input type="text" name="image_url" id="image_url" class="form-control" placeholder="Image URL">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Description"></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <button type="submit" class="btn btn-success">Add Product</button>
                    </div>
                </div>
            </form>

            <h3 class="h5 mt-4 mb-3">Upload Product Image</h3>
            <form id="upload-image-form" enctype="multipart/form-data">
                <div class="input-group">
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                    <button type="submit" class="btn btn-primary">Upload Image</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal chỉnh sửa sản phẩm -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-product-form">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Product Name</label>
                            <input type="text" name="name" id="edit-name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="edit-quantity" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-price" class="form-label">Price</label>
                            <input type="number" step="0.01" name="price" id="edit-price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-image_url" class="form-label">Image URL (optional)</label>
                            <input type="text" name="image_url" id="edit-image_url" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit-description" class="form-label">Description</label>
                            <textarea name="description" id="edit-description" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script>
        // Xử lý form thêm sản phẩm bằng AJAX
        document.getElementById('add-product-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = new FormData(this);
            const data = {
                name: form.get('name'),
                quantity: parseInt(form.get('quantity')),
                image_url: form.get('image_url') || null,
                description: form.get('description'),
                price: parseFloat(form.get('price'))
            };

            fetch('/kiemtr2_nhom09/public/index.php/api/products', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.status === 'success') {
                    alert('Product added successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the product: ' + error.message);
            });
        });

        // Xử lý form upload hình ảnh bằng AJAX
        document.getElementById('upload-image-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/kiemtr2_nhom09/public/index.php/api/upload-image', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.status === 'success') {
                    alert('Image uploaded successfully!');
                    document.getElementById('image_url').value = result.image_url;
                } else {
                    alert('Error: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while uploading the image: ' + error.message);
            });
        });

        // Xử lý nút Edit
        document.querySelectorAll('.edit-product').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const quantity = this.getAttribute('data-quantity');
                const image_url = this.getAttribute('data-image_url');
                const description = this.getAttribute('data-description');
                const price = this.getAttribute('data-price');

                document.getElementById('edit-id').value = id;
                document.getElementById('edit-name').value = name;
                document.getElementById('edit-quantity').value = quantity;
                document.getElementById('edit-image_url').value = image_url;
                document.getElementById('edit-description').value = description;
                document.getElementById('edit-price').value = price;
            });
        });

        // Xử lý form chỉnh sửa sản phẩm bằng AJAX
        document.getElementById('edit-product-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = new FormData(this);
            const data = {
                id: parseInt(form.get('id')),
                name: form.get('name'),
                quantity: parseInt(form.get('quantity')),
                image_url: form.get('image_url') || null,
                description: form.get('description'),
                price: parseFloat(form.get('price'))
            };

            fetch('/kiemtr2_nhom09/public/index.php/api/products', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.status === 'success') {
                    alert('Product updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the product: ' + error.message);
            });
        });

        // Xử lý nút Delete
        document.querySelectorAll('.delete-product').forEach(button => {
            button.addEventListener('click', function() {
                if (!confirm('Are you sure you want to delete this product?')) {
                    return;
                }

                const id = this.getAttribute('data-id');
                const data = { id: parseInt(id) };

                fetch('/kiemtr2_nhom09/public/index.php/api/products', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(result => {
                    if (result.status === 'success') {
                        alert('Product deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + result.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the product: ' + error.message);
                });
            });
        });
    </script>
</body>
</html>