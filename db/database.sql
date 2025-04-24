-- Users table
USE bangiay;
DROP TABLE IF EXISTS users;
CREATE TABLE users
(
  id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  status char(10) NOT NULL DEFAULT 'UNLOCK',
  username VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE (email),
  UNIQUE (username)
);

-- Admin table
DROP TABLE IF EXISTS admin;
CREATE TABLE admin
(
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  position INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE (email)
);

-- Product types table
DROP TABLE IF EXISTS type_product;
CREATE TABLE type_product
(
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  id_admin INT NOT NULL,
  is_active INT NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_admin) REFERENCES admin(id) ON DELETE CASCADE
);

-- Payment methods table
DROP TABLE IF EXISTS payment_method;
CREATE TABLE payment_method
(
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE (name)
);

-- Shipping information table
DROP TABLE IF EXISTS information_receive;
CREATE TABLE information_receive
(
  id INT NOT NULL AUTO_INCREMENT,
  address TEXT NOT NULL,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  id_user INT NOT NULL,
  is_default BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE
);

-- Suppliers table
DROP TABLE IF EXISTS supplier;
CREATE TABLE supplier
(
  id INT NOT NULL AUTO_INCREMENT,
  address TEXT NOT NULL,
  phone VARCHAR(20) NOT NULL,
  name VARCHAR(100) NOT NULL,
  status TINYINT NOT NULL DEFAULT 1,
  id_admin INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_admin) REFERENCES admin(id) ON DELETE CASCADE
);

-- Vouchers table
DROP TABLE IF EXISTS voucher;
CREATE TABLE voucher
(
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  deduction DECIMAL(10,2) NOT NULL,
  description TEXT,
  date_start DATE NOT NULL,
  date_end DATE,
  id_admin INT,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_admin) REFERENCES admin(id) ON DELETE SET NULL
);

-- Bills table
DROP TABLE IF EXISTS bill;
CREATE TABLE bill
(
  id INT NOT NULL AUTO_INCREMENT,
  status VARCHAR(20) NOT NULL DEFAULT 'pending',
  bill_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  id_user INT NOT NULL,
  id_payment_method INT NOT NULL,
  total_amount DECIMAL(12,2) NOT NULL,
  shipping_address TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (id_payment_method) REFERENCES payment_method(id)
);

-- Bill details table
DROP TABLE IF EXISTS bill_detail;
CREATE TABLE bill_detail
(
  id INT NOT NULL AUTO_INCREMENT,
  bill_id INT NOT NULL,
  price_product DECIMAL(10,2) NOT NULL,
  shipping_fee DECIMAL(10,2) NOT NULL,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (bill_id) REFERENCES bill(id) ON DELETE CASCADE
);

-- Products table
DROP TABLE IF EXISTS products;
CREATE TABLE products
(
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  quantity INT NOT NULL DEFAULT 0,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  weight DECIMAL(10,2) NOT NULL COMMENT 'in grams',
  id_voucher INT,
  id_type_product INT NOT NULL,
  id_admin INT NOT NULL,
  id_supplier INT NOT NULL,
  is_active BOOLEAN DEFAULT TRUE,
  image_url VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_voucher) REFERENCES voucher(id) ON DELETE SET NULL,
  FOREIGN KEY (id_type_product) REFERENCES type_product(id) ON DELETE CASCADE,
  FOREIGN KEY (id_admin) REFERENCES admin(id) ON DELETE CASCADE,
  FOREIGN KEY (id_supplier) REFERENCES supplier(id) ON DELETE CASCADE
);

-- Product images table
DROP TABLE IF EXISTS product_images;
CREATE TABLE product_images
(
  id INT NOT NULL AUTO_INCREMENT,
  image_url VARCHAR(255) NOT NULL,
  id_product INT NOT NULL,
  is_primary BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_product) REFERENCES products(id) ON DELETE CASCADE
);

-- Bill products junction table
DROP TABLE IF EXISTS bill_products;
CREATE TABLE bill_products
(
  id INT NOT NULL AUTO_INCREMENT,
  id_bill INT NOT NULL,
  id_product INT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  unit_price DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_bill) REFERENCES bill(id) ON DELETE CASCADE,
  FOREIGN KEY (id_product) REFERENCES products(id) ON DELETE CASCADE,
  UNIQUE (id_bill, id_product)
);
INSERT INTO users (email, password, status, username) VALUES
('customer1@gmail.com', '123', "UNLOCK", 'user_john'),
('customer2@gmail.com', '123', 'UNLOCK', 'user_mary'),
('customer3@gmail.com', 'password123', 'UNLOCK', 'user_david'),
('customer4@gmail.com', 'password123', 'UNLOCK', 'user_lisa'),
('customer5@gmail.com', 'password123', 'LOCK', 'inactive_user'),
('vip_customer@gmail.com', 'password123', 'LOCK', 'vip_member'),
('new_user@gmail.com', 'password123', 'LOCK', 'newbie123'),
('sneaker_lover@gmail.com', 'password123', 'UNLOCK', 'sneakerhead'),
('fashion_guy@gmail.com', 'password123', 'UNLOCK', 'fashionista'),
('admin_test@gmail.com', 'password123', 'UNLOCK', 'test_admin');

INSERT INTO admin (name, email, password, position) VALUES
('Nguyễn Văn A', 'admin1@bangiay.com', 'admin123', 'Quản lý cửa hàng'),
('Trần Thị B', 'admin2@bangiay.com', 'admin123', 'Quản lý kho'),
('Lê Văn C', 'admin3@bangiay.com', 'admin123', 'Quản lý bán hàng'),
('Phạm Thị D', 'admin4@bangiay.com', 'admin123', 'Quản lý marketing'),
('Hoàng Văn E', 'admin5@bangiay.com', 'admin123', 'Quản trị hệ thống'),
('Super Admin', 'superadmin@bangiay.com', 'admin123', 'Super Administrator'),
('CS Manager', 'csmanager@bangiay.com', 'admin123', 'Quản lý CSKH'),
('Inventory Admin', 'inventory@bangiay.com', 'admin123', 'Quản lý tồn kho'),
('Marketing Admin', 'marketing@bangiay.com', 'admin123', 'Quản lý tiếp thị'),
('IT Support', 'itsupport@bangiay.com', 'admin123', 'Hỗ trợ kỹ thuật');
INSERT INTO type_product (name, id_admin) VALUES
('Giày thể thao', 1),
('Giày chạy bộ', 1),
('Giày bóng đá', 2),
('Giày bóng rổ', 2),
('Giày tập gym', 3),
('Giày đi phượt', 3),
('Giày công sở', 4),
('Giày thời trang', 4),
('Dép thể thao', 5),
('Phụ kiện giày', 5);
INSERT INTO payment_method (name, is_active) VALUES
('Tiền mặt khi nhận hàng', 1),
('Chuyển khoản ngân hàng', 1),
('Ví điện tử Momo', 1),
('Thẻ tín dụng Visa', 1),
('Thẻ tín dụng Mastercard', 1),
('Thẻ ghi nợ nội địa', 1),
('Trả góp 0%', 1),
('QR Code', 1),
('PayPal', 0), -- Tạm ngừng
('Internet Banking', 1);
INSERT INTO information_receive (address, name, phone, id_user, is_default) VALUES
('123 Đường Lê Lợi, Q.1, TP.HCM', 'Nguyễn Văn An', '0912345678', 1, 1),
('456 Đường Nguyễn Huệ, Q.1, TP.HCM', 'Nguyễn Văn An', '0912345679', 1, 0),
('789 Đường Cách Mạng Tháng 8, Q.3, TP.HCM', 'Trần Thị Bình', '0987654321', 2, 1),
('321 Đường 3/2, Q.10, TP.HCM', 'Lê Văn Cường', '0905123456', 3, 1),
('654 Đường Lý Thường Kiệt, Q.Tân Bình, TP.HCM', 'Phạm Thị Dung', '0978123456', 4, 1),
('987 Đường Nguyễn Văn Linh, Q.7, TP.HCM', 'Hoàng Văn Em', '0911222333', 5, 1),
('555 Đường Lê Văn Việt, Q.9, TP.HCM', 'Vũ Thị Phương', '0988999888', 6, 1),
('222 Đường Võ Văn Ngân, Q.Thủ Đức, TP.HCM', 'Đặng Văn Hùng', '0967123456', 7, 1),
('111 Đường Phạm Văn Đồng, Q.Bình Thạnh, TP.HCM', 'Bùi Thị Lan', '0938123456', 8, 1),
('333 Đường Trường Chinh, Q.Tân Phú, TP.HCM', 'Mai Văn Tài', '0945123456', 9, 1);
INSERT INTO supplier (address, phone, name, status, id_admin) VALUES
('KCN Biên Hòa, Đồng Nai', '02513888888', 'Công ty Giày thể thao Việt Nam', 1, 1),
('KCN Bình Dương', '02743666666', 'Công ty TNHH Giày dép ABC', 1, 1),
('KCN Sóng Thần, Bình Dương', '02743777777', 'Xưởng giày Thành Công', 1, 2),
('KCN Tân Tạo, TP.HCM', '02839876543', 'Công ty Giày da Thịnh Phát', 1, 2),
('KCN Vĩnh Lộc, TP.HCM', '02837654321', 'Nhà cung cấp Giày thể thao Quốc tế', 1, 3),
('KCN Đồng Nai', '02513999999', 'Công ty Giày dép Xuất khẩu', 0, 3), -- Ngừng hợp tác
('KCN Long Bình, Đồng Nai', '02513555555', 'Xưởng giày Minh Anh', 1, 4),
('KCN Amata, Đồng Nai', '02513666666', 'Công ty Giày thể thao Đại Dương', 1, 4),
('KCN Nhơn Trạch, Đồng Nai', '02513777777', 'Nhà cung cấp Giày cao cấp', 1, 5),
('KCN Tân Phú Trung, TP.HCM', '02838765432', 'Xưởng giày Phú Thịnh', 1, 5);
INSERT INTO voucher (name, deduction, description, date_start, date_end, id_admin) VALUES
('Giảm 10% đơn đầu', 10.00, 'Giảm 10% cho đơn hàng đầu tiên', '2023-01-01', '2023-12-31', 1),
('Freeship toàn quốc', 0.00, 'Miễn phí vận chuyển toàn quốc', '2023-01-01', '2023-06-30', 1),
('Giảm 20% ngày lễ', 20.00, 'Giảm 20% trong các ngày lễ lớn', '2023-04-30', '2023-05-02', 2),
('Giảm 15% tháng 6', 15.00, 'Giảm 15% toàn bộ sản phẩm tháng 6', '2023-06-01', '2023-06-30', 2),
('Giảm 30% Black Friday', 30.00, 'Giảm 30% ngày Black Friday', '2023-11-24', '2023-11-24', 3),
('Giảm 50K đơn 500K', 50.00, 'Giảm 50K cho đơn hàng từ 500K', '2023-01-01', '2023-12-31', 3),
('Giảm 100K đơn 1TR', 100.00, 'Giảm 100K cho đơn hàng từ 1 triệu', '2023-07-01', '2023-09-30', 4),
('Giảm 5% thành viên', 5.00, 'Giảm 5% cho thành viên thân thiết', '2023-01-01', NULL, 4), -- Không hết hạn
('Giảm 25% sinh nhật', 25.00, 'Giảm 25% trong tháng sinh nhật', '2023-01-01', '2023-12-31', 5),
('Giảm 70% thanh lý', 70.00, 'Giảm 70% thanh lý mẫu cũ', '2023-08-01', '2023-08-15', 5);
INSERT INTO products (name, quantity, description, price, weight, id_voucher, id_type_product, id_admin, id_supplier) VALUES
('Giày thể thao Nike Air Max', 50, 'Giày thể thao đế khí Nike Air Max 2023', 2500000, 500, 1, 1, 1, 1),
('Giày chạy bộ Adidas Ultraboost', 30, 'Giày chạy bộ công nghệ Boost', 3200000, 450, NULL, 2, 1, 2),
('Giày bóng đá Puma Future', 20, 'Giày bóng đá công nghệ lưới thoáng khí', 1800000, 400, 3, 3, 2, 3),
('Giày bóng rổ Jordan', 15, 'Giày bóng rổ Jordan phiên bản giới hạn', 4500000, 600, NULL, 4, 2, 4),
('Giày tập gym Under Armour', 40, 'Giày tập gym đế chống trượt', 1500000, 480, 5, 5, 3, 5),
('Giày đi phượt Columbia', 25, 'Giày đi phượt chống nước', 2200000, 550, NULL, 6, 3, 6),
('Giày công sở Bitis', 60, 'Giày công sở da thật mềm mại', 1200000, 420, 7, 7, 4, 7),
('Giày thời trang Gucci', 10, 'Giày thời trang cao cấp Gucci', 8500000, 380, NULL, 8, 4, 8),
('Dép thể thao Adidas', 100, 'Dép thể thao quai ngang thoáng khí', 450000, 200, 9, 9, 5, 9),
('Bộ vệ sinh giày', 80, 'Bộ dụng cụ vệ sinh giày 5 món', 250000, 150, NULL, 10, 5, 10);
INSERT INTO product_images (image_url, id_product, is_primary) VALUES
('nike_air_max_1.jpg', 1, 1),
('nike_air_max_2.jpg', 1, 0),
('nike_air_max_3.jpg', 1, 0),
('adidas_ultraboost_1.jpg', 2, 1),
('adidas_ultraboost_2.jpg', 2, 0),
('puma_future_1.jpg', 3, 1),
('jordan_bball_1.jpg', 4, 1),
('ua_gym_1.jpg', 5, 1),
('columbia_hiking_1.jpg', 6, 1),
('bitis_offic_1.jpg', 7, 1),
('gucci_fashion_1.jpg', 8, 1),
('adidas_sandal_1.jpg', 9, 1),
('shoe_clean_kit_1.jpg', 10, 1);
INSERT INTO bill (status, bill_date, id_user, id_payment_method, total_amount, shipping_address) VALUES
('completed', '2023-01-15 10:30:00', 1, 1, 2500000, '123 Đường Lê Lợi, Q.1, TP.HCM'),
('completed', '2023-02-20 14:15:00', 2, 2, 3200000, '789 Đường Cách Mạng Tháng 8, Q.3, TP.HCM'),
('shipping', '2023-03-05 09:45:00', 3, 3, 1800000, '321 Đường 3/2, Q.10, TP.HCM'),
('processing', '2023-03-10 16:20:00', 4, 4, 4500000, '654 Đường Lý Thường Kiệt, Q.Tân Bình, TP.HCM'),
('completed', '2023-04-01 11:10:00', 5, 5, 1500000, '987 Đường Nguyễn Văn Linh, Q.7, TP.HCM'),
('cancelled', '2023-04-15 13:25:00', 6, 1, 2200000, '555 Đường Lê Văn Việt, Q.9, TP.HCM'),
('completed', '2023-05-02 15:30:00', 7, 6, 1200000, '222 Đường Võ Văn Ngân, Q.Thủ Đức, TP.HCM'),
('completed', '2023-05-20 10:00:00', 8, 7, 8500000, '111 Đường Phạm Văn Đồng, Q.Bình Thạnh, TP.HCM'),
('shipping', '2023-06-01 14:45:00', 9, 8, 450000, '333 Đường Trường Chinh, Q.Tân Phú, TP.HCM'),
('processing', '2023-06-15 17:00:00', 1, 9, 250000, '456 Đường Nguyễn Huệ, Q.1, TP.HCM');
INSERT INTO bill_detail (bill_id, price_product, shipping_fee, notes) VALUES
(1, 2500000, 30000, 'Giao giờ hành chính'),
(2, 3200000, 0, 'Đã sử dụng voucher freeship'),
(3, 1800000, 25000, 'Giao trước 17h'),
(4, 4500000, 50000, 'Kiểm tra hàng trước khi thanh toán'),
(5, 1500000, 20000, NULL),
(6, 2200000, 30000, 'Hủy do khách không nhận'),
(7, 1200000, 15000, 'Đóng gói cẩn thận'),
(8, 8500000, 0, 'Hàng cao cấp - yêu cầu ký gửi'),
(9, 450000, 10000, NULL),
(10, 250000, 15000, 'Quà tặng kèm theo đơn hàng');
INSERT INTO bill_products (id_bill, id_product, quantity, unit_price) VALUES
(1, 1, 1, 2500000),
(2, 2, 1, 3200000),
(3, 3, 1, 1800000),
(4, 4, 1, 4500000),
(5, 5, 1, 1500000),
(6, 6, 1, 2200000),
(7, 7, 1, 1200000),
(8, 8, 1, 8500000),
(9, 9, 1, 450000),
(10, 10, 1, 250000),
(1, 10, 1, 250000), -- Đơn hàng 1 mua thêm bộ vệ sinh
(2, 9, 2, 450000); -- Đơn hàng 2 mua thêm 2 đôi dép
UPDATE products SET image_url = 'nike_air_max_1.jpg' WHERE id = 1;
UPDATE products SET image_url = 'adidas_ultraboost_1.jpg' WHERE id = 2;
UPDATE products SET image_url = 'puma_future_1.jpg' WHERE id = 3;
UPDATE products SET image_url = 'jordan_bball_1.jpg' WHERE id = 4;
UPDATE products SET image_url = 'ua_gym_1.jpg' WHERE id = 5;
UPDATE products SET image_url = 'columbia_hiking_1.jpg' WHERE id = 6;
UPDATE products SET image_url = 'bitis_offic_1.jpg' WHERE id = 7;
UPDATE products SET image_url = 'gucci_fashion_1.jpg' WHERE id = 8;
UPDATE products SET image_url = 'adidas_sandal_1.jpg' WHERE id = 9;
UPDATE products SET image_url = 'shoe_clean_kit_1.jpg' WHERE id = 10;