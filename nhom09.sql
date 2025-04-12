CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,           -- Mã hàng (id tự tăng)
    name VARCHAR(255) NOT NULL,                  -- Tên hàng
    quantity INT NOT NULL,                       -- Số lượng
    image_url VARCHAR(255),                      -- Hình ảnh (đường dẫn tương đối)
    description TEXT,                            -- Mô tả sản phẩm
    price DECIMAL(10, 2) NOT NULL                -- Giá bán
);
INSERT INTO products (name, quantity, image_url, description, price) VALUES
('iPhone 15 Pro Max', 10, 'uploads/iphone15.jpg', 'iPhone 15 Pro Max - thiết kế titan siêu nhẹ, chip A17 Pro cực mạnh.', 33990000),
('Samsung Galaxy S23', 15, 'uploads/galaxy-s23.jpg', 'Samsung Galaxy S23 mạnh mẽ với camera 200MP.', 23990000),
('MacBook Air M2', 5, 'uploads/macbook-air.jpg', 'Laptop Apple mỏng nhẹ, pin lâu, chip M2 mạnh mẽ.', 28990000),
('MacBook Pro M3', 8, 'uploads/macbook-pro.jpg', 'MacBook Pro M3 dành cho dân đồ họa, lập trình chuyên nghiệp.', 49990000),
('Áo thun nam cổ tròn', 50, 'uploads/tshirt-men.jpg', 'Áo thun nam cotton 100% thoáng mát, dễ mặc.', 199000),
('Quần jeans nữ skinny', 40, 'uploads/jeans-women.jpg', 'Quần jeans nữ co giãn, ôm dáng, thời trang.', 299000),
('Giày sneaker Adidas', 30, 'uploads/adidas-sneaker.jpg', 'Giày thể thao Adidas chính hãng, bền đẹp.', 1599000),
('Giày Nike Air Max', 25, 'uploads/nike-air.jpg', 'Giày Nike Air Max êm ái, trẻ trung.', 1899000),
('Tai nghe AirPods Pro', 20, 'uploads/airpods-pro.jpg', 'Tai nghe Apple AirPods Pro chống ồn chủ động.', 4990000),
('Tai nghe Sony WH-1000XM4', 12, 'uploads/sony-headphone.jpg', 'Tai nghe chống ồn đỉnh cao của Sony.', 6490000),
('Sách Harry Potter 1', 100, 'uploads/harry1.jpg', 'Harry Potter và Hòn đá phù thủy - tập đầu tiên.', 129000),
('Sách Đắc nhân tâm', 80, 'uploads/dacnhantam.jpg', 'Cuốn sách kỹ năng sống kinh điển giúp bạn thành công.', 99000),
('Sách Lập trình Python', 40, 'uploads/python-book.jpg', 'Hướng dẫn học lập trình Python từ cơ bản đến nâng cao.', 159000),
('Balo laptop chống sốc', 60, 'uploads/laptop-backpack.jpg', 'Balo chống sốc cho laptop, nhiều ngăn tiện dụng.', 359000),
('Bàn phím cơ gaming Razer', 15, 'uploads/razer-keyboard.jpg', 'Bàn phím cơ RGB dành cho game thủ.', 2099000),
('Chuột không dây Logitech', 35, 'uploads/logitech-mouse.jpg', 'Chuột không dây mượt mà, tiết kiệm pin.', 299000),
('Màn hình LG 27 inch 4K', 10, 'uploads/lg-monitor.jpg', 'Màn hình LG sắc nét, viền mỏng, 4K UHD.', 7490000),
('Ổ cứng SSD 1TB Samsung', 25, 'uploads/ssd-samsung.jpg', 'SSD Samsung tốc độ cao, lưu trữ cực nhanh.', 2290000),
('Smartwatch Apple Watch S9', 18, 'uploads/apple-watch.jpg', 'Đồng hồ thông minh Apple Watch series 9 mới nhất.', 11990000),
('Máy in Canon LBP 2900', 10, 'uploads/canon-printer.jpg', 'Máy in laser Canon LBP 2900 nhỏ gọn, bền bỉ.', 2590000);
