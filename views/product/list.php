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
        
        /* Tùy chỉnh phân trang */
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Product List</h1>

        <!-- Form tìm kiếm -->
        <form method="GET" action="" class="mb-4" id="search-form">
            <div class="input-group">
                <input type="text" id="search-input" name="search" class="form-control" placeholder="Search products...">
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
                <tbody id="product-table-body">
                    <!-- Dữ liệu sẽ được thêm bằng js -->
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <!-- Phân trang sẽ được thêm bằng js -->
            </ul>
        </nav>

        <!-- Form thêm sản phẩm và upload hình ảnh -->
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="/kiemtr2_nhom09/public/js/ajax.js"></script>
</body>
</html>