// public/js/ajax.js

// Biến toàn cục
let currentPage = 1;
let searchQuery = '';

// Hàm hiển thị danh sách sản phẩm bằng AJAX
async function loadProducts(page = 1, search = '') {
    try {
        const url = `/kiemtr2_nhom09/public/api/products?page=${page}&search=${encodeURIComponent(search)}`;
        console.log('Fetching products from:', url); // Debug URL
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Received data:', data); // Debug dữ liệu trả về
        if (!data || !data.products) {
            throw new Error('Invalid data format: products array not found');
        }

        displayProducts(data.products);
        updatePagination(data.current_page, data.total_pages, search);
        
    } catch (error) {
        console.error('Error fetching products:', error);
        alert('An error occurred while fetching products: ' + error.message);
        const tbody = document.getElementById('product-table-body');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center">Failed to load products. Please try again later.</td></tr>';
        }
    }
}

// Hiển thị sản phẩm lên bảng
function displayProducts(products) {
    const tbody = document.getElementById('product-table-body');
    if (!tbody) {
        console.error('Product table body not found! Ensure <tbody id="product-table-body"> exists in the HTML.');
        return;
    }
    tbody.innerHTML = '';

    if (!products || products.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center">No products found.</td></tr>';
        return;
    }

    products.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.id}</td>
            <td>${product.name}</td>
            <td>${product.quantity}</td>
            <td>
                ${product.image_url ? `<img src="${product.image_url}" alt="Product Image" class="product-image">` : 'No Image'}
            </td>
            <td>${product.description || 'No description'}</td>
            <td>${product.price}</td>
            <td>
                <button class="btn btn-warning btn-sm edit-product" 
                        data-id="${product.id}" 
                        data-name="${product.name}" 
                        data-quantity="${product.quantity}" 
                        data-image_url="${product.image_url || ''}" 
                        data-description="${product.description || ''}" 
                        data-price="${product.price}" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editProductModal">Edit</button>
                <button class="btn btn-danger btn-sm delete-product" 
                        data-id="${product.id}">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    attachEditDeleteEvents();
}

// Cập nhật phân trang
function updatePagination(currentPage, totalPages, search) {
    const paginationContainer = document.querySelector('.pagination'); // Sửa thành class selector
    if (!paginationContainer) {
        console.error('Pagination container not found!');
        return;
    }

    paginationContainer.innerHTML = ''; // Xóa nội dung cũ

    console.log('Updating pagination:', currentPage, totalPages);

    if (!totalPages || totalPages <= 1) {
        // Ẩn phân trang nếu chỉ có 1 trang
        paginationContainer.style.display = 'none';
        return;
    }

    // Hiển thị phân trang
    paginationContainer.style.display = 'flex';

    // Tạo nút Previous
    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a>`;
    paginationContainer.appendChild(prevLi);

    // Tạo các nút trang
    for (let i = 1; i <= totalPages; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
        paginationContainer.appendChild(li);
    }

    // Tạo nút Next
    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
    nextLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage + 1}">&raquo;</a>`;
    paginationContainer.appendChild(nextLi);

    // Gắn sự kiện click
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = parseInt(this.getAttribute('data-page'));
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                loadProducts(page, searchQuery);
            }
        });
    });
}

// Gắn sự kiện cho các nút Edit và Delete
function attachEditDeleteEvents() {
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

    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', async function() {
            if (!confirm('Are you sure you want to delete this product?')) return;

            const id = this.getAttribute('data-id');
            const data = { id: parseInt(id) };

            try {
                const response = await fetch('/kiemtr2_nhom09/public/api/products', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

                const result = await response.json();
                if (result.status === 'success') {
                    alert('Product deleted successfully!');
                    loadProducts(currentPage, searchQuery);
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error deleting product:', error);
                alert('An error occurred while deleting the product: ' + error.message);
            }
        });
    });
}

// Xử lý form tìm kiếm
document.getElementById('search-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    searchQuery = document.getElementById('search-input').value;
    currentPage = 1;
    loadProducts(currentPage, searchQuery);
});

// Xử lý form thêm sản phẩm
document.getElementById('add-product-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = new FormData(this);
    const data = {
        name: form.get('name'),
        quantity: parseInt(form.get('quantity')),
        image_url: form.get('image_url') || null,
        description: form.get('description'),
        price: parseFloat(form.get('price'))
    };

    try {
        const response = await fetch('/kiemtr2_nhom09/public/api/products', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const result = await response.json();
        if (result.status === 'success') {
            alert('Product added successfully!');
            this.reset();
            loadProducts(currentPage, searchQuery);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error adding product:', error);
        alert('An error occurred while adding the product: ' + error.message);
    }
});

// Xử lý form upload hình ảnh
document.getElementById('upload-image-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    try {
        const response = await fetch('/kiemtr2_nhom09/public/api/upload-image', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const result = await response.json();
        if (result.status === 'success') {
            alert('Image uploaded successfully!');
            document.getElementById('image_url').value = result.image_url;
            this.reset();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error uploading image:', error);
        alert('An error occurred while uploading the image: ' + error.message);
    }
});

// Xử lý form chỉnh sửa sản phẩm
document.getElementById('edit-product-form')?.addEventListener('submit', async function(e) {
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

    try {
        const response = await fetch('/kiemtr2_nhom09/public/api/products', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const result = await response.json();
        if (result.status === 'success') {
            alert('Product updated successfully!');
            $('#editProductModal').modal('hide');
            loadProducts(currentPage, searchQuery);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error updating product:', error);
        alert('An error occurred while updating the product: ' + error.message);
    }
});

// Tải danh sách sản phẩm khi trang được tải
document.addEventListener('DOMContentLoaded', () => {
    console.log('Page loaded, fetching products...');
    loadProducts(currentPage, searchQuery);
});