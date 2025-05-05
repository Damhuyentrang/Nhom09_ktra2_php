<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .cart-table { margin-top: 20px; }
        .total-price { font-size: 1.2em; font-weight: bold; margin-top: 20px; }
        .btn-paypal {
            background-color: #0070ba;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 2px;
            transition: background-color 0.3s;
        }
        .btn-paypal:hover {
            background-color: #005ea6;
        }
        .btn-momo {
            background-color: #a50064;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 2px;
            transition: background-color 0.3s;
        }
        .btn-momo:hover {
            background-color: #8c0054;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Your cart</h1>

        <!-- Liên kết quay lại trang chủ -->
        <div class="text-start mb-3">
            <a href="/kiemtr2_nhom09/public/index.php" class="btn btn-secondary">Back</a>
        </div>

        <!-- Bảng giỏ hàng -->
        <div class="cart-table">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="cartItems">
                    <!-- Sản phẩm sẽ được thêm bằng JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Tổng giá và nút thanh toán -->
        <div class="total-price" id="totalPrice">Total payment: 0 VNĐ</div>
        <div class="text-end mt-3">
            <button class="btn btn-paypal" onclick="checkoutWithPaypal()">Pay with PayPal</button>
            <button class="btn btn-momo" onclick="checkoutWithMomo()">Pay with Momo</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script>
        // Hiển thị giỏ hàng khi trang tải
        document.addEventListener('DOMContentLoaded', function() {
            fetchCart();
        });

        // Hàm lấy danh sách sản phẩm trong giỏ hàng
        function fetchCart() {
            fetch('/kiemtr2_nhom09/public/index.php/api/cart')
                .then(response => response.json())
                .then(data => {
                    if (data && data.status === 'success') {
                        displayCart(data.data || []);
                    } else {
                        alert('Lỗi: ' + (data && data.message ? data.message : 'Unknown error'));
                        displayCart([]);
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi lấy giỏ hàng:', error);
                    displayCart([]);
                });
        }

        // Hàm hiển thị giỏ hàng
        function displayCart(cart) {
            const cartItems = document.getElementById('cartItems');
            const totalPriceElement = document.getElementById('totalPrice');
            let total = 0;

            cartItems.innerHTML = '';

            if (!cart || cart.length === 0) {
                cartItems.innerHTML = '<tr><td colspan="5" class="text-center">The cart is empty.</td></tr>';
                totalPriceElement.textContent = 'Total: 0 VNĐ';
                return;
            }

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${item.price.toLocaleString()} VNĐ</td>
                    <td>
                        <input type="number" min="1" value="${item.quantity}" onchange="updateQuantity('${item.id}', this.value)">
                    </td>
                    <td>${itemTotal.toLocaleString()} VNĐ</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="removeFromCart('${item.id}')">Delete</button>
                    </td>
                `;
                cartItems.appendChild(row);
            });

            totalPriceElement.textContent = `Total: ${total.toLocaleString()} VNĐ`;
        }

        // Hàm cập nhật số lượng sản phẩm
        function updateQuantity(productId, quantity) {
            quantity = parseInt(quantity);
            if (quantity <= 0) {
                removeFromCart(productId);
                return;
            }

            fetch('/kiemtr2_nhom09/public/index.php/api/update-cart', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ product_id: productId, quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.status === 'success') {
                    fetchCart();
                } else {
                    alert('Lỗi: ' + (data && data.message ? data.message : 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Lỗi khi cập nhật số lượng:', error);
                alert('Đã xảy ra lỗi khi cập nhật số lượng: ' + error.message);
            });
        }

        // Hàm xóa sản phẩm khỏi giỏ hàng
        function removeFromCart(productId) {
            fetch('/kiemtr2_nhom09/public/index.php/api/remove-from-cart', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(result => {
                if (result.status === 'success') {
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Hàm thanh toán bằng PayPal
        function checkoutWithPaypal() {
            fetch('/kiemtr2_nhom09/public/index.php/api/cart')
                .then(response => response.json())
                .then(data => {
                    if (data && data.status === 'success' && data.data && data.data.length > 0) {
                        const items = data.data.map(item => ({
                            product_id: item.id,
                            quantity: item.quantity
                        }));

                        fetch('/kiemtr2_nhom09/public/index.php/api/create-payment', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ items })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.status === 'success' && data.redirect_url) {
                                // Xóa giỏ hàng sau khi thanh toán thành công
                                fetch('/kiemtr2_nhom09/public/index.php/api/remove-from-cart', {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({ clear_all: true })
                                }).then(() => {
                                    window.location.href = data.redirect_url;
                                });
                            } else {
                                alert('Lỗi khi tạo thanh toán PayPal: ' + (data && data.message ? data.message : 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Lỗi khi tạo thanh toán PayPal:', error);
                            alert('Đã xảy ra lỗi khi tạo thanh toán PayPal: ' + error.message);
                        });
                    } else {
                        alert('Giỏ hàng trống, không thể thanh toán!');
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi kiểm tra giỏ hàng:', error);
                    alert('Đã xảy ra lỗi khi kiểm tra giỏ hàng: ' + error.message);
                });
        }

        // Hàm thanh toán bằng Momo
        function checkoutWithMomo() {
            fetch('/kiemtr2_nhom09/public/index.php/api/cart')
                .then(response => response.json())
                .then(data => {
                    if (data && data.status === 'success' && data.data && data.data.length > 0) {
                        const items = data.data.map(item => ({
                            product_id: item.id,
                            quantity: item.quantity
                        }));

                        fetch('/kiemtr2_nhom09/public/index.php/api/create-momo-payment', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ items })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.status === 'success' && data.redirect_url) {
                                // Xóa giỏ hàng sau khi thanh toán thành công
                                fetch('/kiemtr2_nhom09/public/index.php/api/remove-from-cart', {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({ clear_all: true })
                                }).then(() => {
                                    window.location.href = data.redirect_url;
                                });
                            } else {
                                alert('Lỗi khi tạo thanh toán Momo: ' + (data && data.message ? data.message : 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Lỗi khi tạo thanh toán Momo:', error);
                            alert('Đã xảy ra lỗi khi tạo thanh toán Momo: ' + error.message);
                        });
                    } else {
                        alert('Giỏ hàng trống, không thể thanh toán!');
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi kiểm tra giỏ hàng:', error);
                    alert('Đã xảy ra lỗi khi kiểm tra giỏ hàng: ' + error.message);
                });
        }
    </script>
</body>
</html>