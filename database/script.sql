-- =====================================================
-- Скрипт базы данных ООО «СтройМатериалы»
-- ДЭ 09.02.07, вариант 3, Модуль 1
-- СУБД: MySQL 8.0
-- =====================================================
DROP DATABASE IF EXISTS stroy_materials;
CREATE DATABASE stroy_materials CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE stroy_materials;
SET NAMES utf8mb4;

CREATE TABLE roles (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE users (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id   INT UNSIGNED NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    login     VARCHAR(100) NOT NULL UNIQUE,
    password  VARCHAR(100) NOT NULL,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles (id)
) ENGINE=InnoDB;

CREATE TABLE categories (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE manufacturers (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE suppliers (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE units (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE products (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    article         VARCHAR(20) NOT NULL UNIQUE,
    name            VARCHAR(150) NOT NULL,
    description     TEXT NULL,
    category_id     INT UNSIGNED NOT NULL,
    manufacturer_id INT UNSIGNED NOT NULL,
    supplier_id     INT UNSIGNED NOT NULL,
    unit_id         INT UNSIGNED NOT NULL,
    price           DECIMAL(10,2) NOT NULL CHECK (price >= 0),
    discount        TINYINT UNSIGNED NOT NULL DEFAULT 0,
    stock_quantity  INT NOT NULL DEFAULT 0 CHECK (stock_quantity >= 0),
    photo           VARCHAR(255) NULL,
    CONSTRAINT fk_products_category     FOREIGN KEY (category_id)     REFERENCES categories (id),
    CONSTRAINT fk_products_manufacturer FOREIGN KEY (manufacturer_id) REFERENCES manufacturers (id),
    CONSTRAINT fk_products_supplier     FOREIGN KEY (supplier_id)     REFERENCES suppliers (id),
    CONSTRAINT fk_products_unit         FOREIGN KEY (unit_id)         REFERENCES units (id)
) ENGINE=InnoDB;

CREATE TABLE pickup_points (
    id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    address VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE order_statuses (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE orders (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_date      DATE NOT NULL,
    delivery_date   DATE NULL,
    pickup_point_id INT UNSIGNED NOT NULL,
    user_id         INT UNSIGNED NULL,
    receive_code    INT UNSIGNED NOT NULL,
    status_id       INT UNSIGNED NOT NULL,
    CONSTRAINT fk_orders_point  FOREIGN KEY (pickup_point_id) REFERENCES pickup_points (id),
    CONSTRAINT fk_orders_user   FOREIGN KEY (user_id)         REFERENCES users (id),
    CONSTRAINT fk_orders_status FOREIGN KEY (status_id)       REFERENCES order_statuses (id)
) ENGINE=InnoDB;

CREATE TABLE order_items (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id   INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity   INT UNSIGNED NOT NULL CHECK (quantity > 0),
    CONSTRAINT fk_items_order   FOREIGN KEY (order_id)   REFERENCES orders (id) ON DELETE CASCADE,
    CONSTRAINT fk_items_product FOREIGN KEY (product_id) REFERENCES products (id),
    CONSTRAINT uq_order_product UNIQUE (order_id, product_id)
) ENGINE=InnoDB;

INSERT INTO roles (id, name) VALUES
(1, 'Администратор'),
(2, 'Менеджер'),
(3, 'Авторизированный клиент');

INSERT INTO users (id, role_id, full_name, login, password) VALUES
(1, 1, 'Ворсин Петр Евгеньевич', '94d5ous@gmail.com', 'uzWC67'),
(2, 1, 'Старикова Елена Павловна', 'uth4iz@mail.com', '2L6KZG'),
(3, 1, 'Одинцов Серафим Артёмович', 'yzls62@outlook.com', 'JlFRCZ'),
(4, 2, 'Степанов Михаил Артёмович', '1diph5e@tutanota.com', '8ntwUp'),
(5, 2, 'Ворсин Петр Евгеньевич', 'tjde7c@yahoo.com', 'YOyhfR'),
(6, 2, 'Старикова Елена Павловна', 'wpmrc3do@tutanota.com', 'RSbvHv'),
(7, 3, 'Михайлюк Анна Вячеславовна', '5d4zbu@tutanota.com', 'rwVDh9'),
(8, 3, 'Ситдикова Елена Анатольевна', 'ptec8ym@yahoo.com', 'LdNyos'),
(9, 3, 'Никифорова Весения Николаевна', '1qz4kw@mail.com', 'gynQMT'),
(10, 3, 'Сазонов Руслан Германович', '4np6se@mail.com', 'AtnDjr');

INSERT INTO categories (id, name) VALUES
(1, 'Общестроительные материалы'),
(2, 'Стеновые и фасадные материалы'),
(3, 'Сухие строительные смеси и гидроизоляция'),
(4, 'Ручной инструмент'),
(5, 'Защита лица, глаз, головы');

INSERT INTO manufacturers (id, name) VALUES
(1, 'М500'),
(2, 'Изостронг'),
(3, 'Knauf'),
(4, 'MixMaster'),
(5, 'ЛСР'),
(6, 'ВОЛМА'),
(7, 'Vinylon'),
(8, 'Павловский завод'),
(9, 'Weber'),
(10, 'Hesler'),
(11, 'Armero'),
(12, 'Wenzo Roma'),
(13, 'KILIMGRIN'),
(14, 'Исток'),
(15, 'RUIZ'),
(16, 'Husqvarna'),
(17, 'Delta');

INSERT INTO suppliers (id, name) VALUES
(1, 'М500'),
(2, 'Изостронг'),
(3, 'Knauf'),
(4, 'MixMaster'),
(5, 'ЛСР'),
(6, 'ВОЛМА'),
(7, 'Vinylon'),
(8, 'Павловский завод'),
(9, 'Weber'),
(10, 'Hesler'),
(11, 'Armero'),
(12, 'Wenzo Roma'),
(13, 'KILIMGRIN'),
(14, 'Исток'),
(15, 'RUIZ'),
(16, 'Husqvarna'),
(17, 'Delta');

INSERT INTO units (id, name) VALUES
(1, 'шт.');

INSERT INTO products (id, article, name, description, category_id, manufacturer_id, supplier_id, unit_id, price, discount, stock_quantity, photo) VALUES
(1, 'PMEZMH', 'Цемент', 'Цемент Евроцемент М500 Д0 ЦЕМ I 42,5 50 кг', 1, 1, 1, 1, 440.00, 8, 34, 'images/PMEZMH.jpg'),
(2, 'BPV4MM', 'Пленка техническая', 'Пленка техническая полиэтиленовая Изостронг 60 мк 3 м рукав 1,5 м, пог.м', 1, 2, 2, 1, 8.00, 8, 2, 'images/BPV4MM.jpg'),
(3, 'JVL42J', 'Пленка техническая', 'Пленка техническая полиэтиленовая Изостронг 100 мк 3 м рукав 1,5 м, пог.м', 1, 2, 2, 1, 13.00, 4, 34, 'images/JVL42J.jpg'),
(4, 'F895RB', 'Песок строительный', 'Песок строительный 50 кг', 1, 3, 3, 1, 102.00, 6, 7, 'images/F895RB.jpg'),
(5, '3XBOTN', 'Керамзит фракция', 'Керамзит фракция 10-20 мм 0,05 куб.м', 1, 4, 4, 1, 110.00, 5, 21, 'images/3XBOTN.jpg'),
(6, '3L7RCZ', 'Газобетон', 'Газобетон ЛСР 100х250х625 мм D400', 2, 5, 5, 1, 7400.00, 2, 20, 'images/3L7RCZ.jpg'),
(7, 'S72AM3', 'Пазогребневая плита', 'Пазогребневая плита ВОЛМА Гидро 667х500х80 мм полнотелая', 2, 6, 6, 1, 500.00, 5, 35, 'images/S72AM3.jpg'),
(8, '2G3280', 'Угол наружный', 'Угол наружный Vinylon 3050 мм серо-голубой', 2, 7, 7, 1, 795.00, 9, 20, 'images/2G3280.jpg'),
(9, 'MIO8YV', 'Кирпич', 'Кирпич рядовой Боровичи полнотелый М150 250х120х65 мм 1NF', 2, 6, 6, 1, 30.00, 9, 31, 'images/MIO8YV.jpg'),
(10, 'UER2QD', 'Скоба для пазогребневой плиты', 'Скоба для пазогребневой плиты Knauf С1 120х100 мм', 2, 3, 3, 1, 25.00, 8, 27, 'images/UER2QD.jpg'),
(11, 'ZR70B4', 'Кирпич', 'Кирпич рядовой силикатный Павловский завод полнотелый М200 250х120х65 мм 1NF', 2, 8, 8, 1, 16.00, 3, 0, NULL),
(12, 'LPDDM4', 'Штукатурка гипсовая', 'Штукатурка гипсовая Knauf Ротбанд 30 кг', 3, 3, 3, 1, 500.00, 6, 38, NULL),
(13, 'LQ48MW', 'Штукатурка гипсовая', 'Штукатурка гипсовая Knauf МП-75 машинная 30 кг', 3, 9, 9, 1, 462.00, 6, 33, NULL),
(14, 'O43COU', 'Шпаклевка', 'Шпаклевка полимерная Weber.vetonit LR + для сухих помещений белая 20 кг', 3, 6, 6, 1, 750.00, 1, 16, NULL),
(15, 'M26EXW', 'Клей для плитки, керамогранита и камня', 'Клей для плитки, керамогранита и камня Крепс Усиленный серый (класс С1) 25 кг', 3, 3, 3, 1, 340.00, 8, 0, NULL),
(16, 'K0YACK', 'Смесь цементно-песчаная', 'Смесь цементно-песчаная (ЦПС) 300 по ТУ MixMaster Универсал 25 кг', 3, 4, 4, 1, 160.00, 8, 19, NULL),
(17, 'ASPXSG', 'Ровнитель', 'Ровнитель (наливной пол) финишный Weber.vetonit 4100 самовыравнивающийся высокопрочный 20 кг', 3, 9, 9, 1, 711.00, 10, 20, NULL),
(18, 'ZKQ5FF', 'Лезвие для ножа', 'Лезвие для ножа Hesler 18 мм прямое (10 шт.)', 4, 10, 10, 1, 65.00, 6, 6, NULL),
(19, '4WZEOT', 'Лезвие для ножа', 'Лезвие для ножа Armero 18 мм прямое (10 шт.)', 4, 11, 11, 1, 110.00, 6, 17, NULL),
(20, '4JR1HN', 'Шпатель', 'Шпатель малярный 100 мм с пластиковой ручкой', 4, 10, 10, 1, 26.00, 6, 7, NULL),
(21, 'Z3XFSP', 'Нож строительный', 'Нож строительный Hesler 18 мм с ломающимся лезвием пластиковый корпус', 4, 10, 10, 1, 63.00, 8, 5, NULL),
(22, 'I6MH89', 'Валик', 'Валик Wenzo Roma полиакрил 250 мм ворс 18 мм для красок грунтов и антисептиков на водной основе с рукояткой', 4, 12, 12, 1, 326.00, 12, 3, NULL),
(23, '83M5ME', 'Кисть', 'Кисть плоская смешанная щетина 100х12 мм для красок и антисептиков на водной основе', 4, 11, 11, 1, 122.00, 9, 26, NULL),
(24, '61PGH3', 'Очки защитные', 'Очки защитные Delta Plus KILIMANDJARO (KILIMGRIN) открытые с прозрачными линзами', 5, 13, 13, 1, 184.00, 6, 25, NULL),
(25, 'GN6ICZ', 'Каска защитная', 'Каска защитная Исток (КАС001О) оранжевая', 5, 14, 14, 1, 154.00, 15, 8, NULL),
(26, 'Z3LO0U', 'Очки защитные', 'Очки защитные Delta Plus RUIZ (RUIZ1VI) закрытые с прозрачными линзами', 5, 15, 15, 1, 228.00, 9, 11, NULL),
(27, 'QHNOKR', 'Маска защитная', 'Маска защитная Исток (ЩИТ001) ударопрочная и термостойкая', 5, 14, 14, 1, 251.00, 2, 22, NULL),
(28, 'EQ6RKO', 'Подшлемник', 'Подшлемник для каски одноразовый', 5, 16, 16, 1, 36.00, 17, 22, NULL),
(29, '81F1WG', 'Каска защитная', 'Каска защитная Delta Plus BASEBALL DIAMOND V UP (DIAM5UPBCFLBS) белая', 5, 17, 17, 1, 1500.00, 2, 13, NULL),
(30, '0YGHZ7', 'Очки защитные', 'Очки защитные Husqvarna Clear (5449638-01) открытые с прозрачными линзами', 5, 16, 16, 1, 700.00, 9, 36, NULL);

INSERT INTO pickup_points (id, address) VALUES
(1, '420151, г. Лесной, ул. Вишневая, 32'),
(2, '125061, г. Лесной, ул. Подгорная, 8'),
(3, '630370, г. Лесной, ул. Шоссейная, 24'),
(4, '400562, г. Лесной, ул. Зеленая, 32'),
(5, '614510, г. Лесной, ул. Маяковского, 47'),
(6, '410542, г. Лесной, ул. Светлая, 46'),
(7, '620839, г. Лесной, ул. Цветочная, 8'),
(8, '443890, г. Лесной, ул. Коммунистическая, 1'),
(9, '603379, г. Лесной, ул. Спортивная, 46'),
(10, '603721, г. Лесной, ул. Гоголя, 41'),
(11, '410172, г. Лесной, ул. Северная, 13'),
(12, '614611, г. Лесной, ул. Молодежная, 50'),
(13, '454311, г.Лесной, ул. Новая, 19'),
(14, '660007, г.Лесной, ул. Октябрьская, 19'),
(15, '603036, г. Лесной, ул. Садовая, 4'),
(16, '394060, г.Лесной, ул. Фрунзе, 43'),
(17, '410661, г. Лесной, ул. Школьная, 50'),
(18, '625590, г. Лесной, ул. Коммунистическая, 20'),
(19, '625683, г. Лесной, ул. 8 Марта'),
(20, '450983, г.Лесной, ул. Комсомольская, 26'),
(21, '394782, г. Лесной, ул. Чехова, 3'),
(22, '603002, г. Лесной, ул. Дзержинского, 28'),
(23, '450558, г. Лесной, ул. Набережная, 30'),
(24, '344288, г. Лесной, ул. Чехова, 1'),
(25, '614164, г.Лесной,  ул. Степная, 30'),
(26, '394242, г. Лесной, ул. Коммунистическая, 43'),
(27, '660540, г. Лесной, ул. Солнечная, 25'),
(28, '125837, г. Лесной, ул. Шоссейная, 40'),
(29, '125703, г. Лесной, ул. Партизанская, 49'),
(30, '625283, г. Лесной, ул. Победы, 46'),
(31, '614753, г. Лесной, ул. Полевая, 35'),
(32, '426030, г. Лесной, ул. Маяковского, 44'),
(33, '450375, г. Лесной ул. Клубная, 44'),
(34, '625560, г. Лесной, ул. Некрасова, 12'),
(35, '630201, г. Лесной, ул. Комсомольская, 17'),
(36, '190949, г. Лесной, ул. Мичурина, 26');

INSERT INTO order_statuses (id, name) VALUES
(1, 'Завершен'),
(2, 'Новый');

INSERT INTO orders (id, order_date, delivery_date, pickup_point_id, user_id, receive_code, status_id) VALUES
(1, '2025-02-27', '2025-04-20', 1, 7, 901, 1),
(2, '2024-09-28', '2025-04-21', 11, 8, 902, 1),
(3, '2025-03-21', '2025-04-22', 2, 9, 903, 1),
(4, '2025-02-20', '2025-04-23', 11, 10, 904, 1),
(5, '2025-03-17', '2025-04-24', 2, 7, 905, 1),
(6, '2025-03-01', '2025-04-25', 15, 8, 906, 1),
(7, '2025-02-28', '2025-04-26', 3, 9, 907, 1),
(8, '2025-03-31', '2025-04-27', 19, 10, 908, 2),
(9, '2025-04-02', '2025-04-28', 5, 9, 909, 2),
(10, '2025-04-03', '2025-04-29', 19, 10, 910, 2);

INSERT INTO order_items (order_id, product_id, quantity) VALUES
(1, 1, 2),
(1, 2, 2),
(2, 3, 1),
(2, 4, 1),
(3, 5, 10),
(3, 6, 10),
(4, 7, 5),
(4, 8, 4),
(5, 9, 2),
(5, 10, 2),
(6, 11, 1),
(6, 12, 1),
(7, 13, 10),
(7, 14, 10),
(8, 15, 5),
(8, 16, 4),
(9, 17, 5),
(9, 18, 1),
(10, 19, 5),
(10, 20, 5);
