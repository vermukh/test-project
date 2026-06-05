<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\PickupPoint;
use App\Models\Product;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StroyMaterialsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->syncById(Role::class, [
                ['id' => 1, 'name' => 'Администратор'],
                ['id' => 2, 'name' => 'Менеджер'],
                ['id' => 3, 'name' => 'Авторизированный клиент'],
            ]);

            $this->syncById(User::class, [
                ['id' => 1, 'role_id' => 1, 'full_name' => 'Ворсин Петр Евгеньевич', 'login' => '94d5ous@gmail.com', 'password' => 'uzWC67'],
                ['id' => 2, 'role_id' => 1, 'full_name' => 'Старикова Елена Павловна', 'login' => 'uth4iz@mail.com', 'password' => '2L6KZG'],
                ['id' => 3, 'role_id' => 1, 'full_name' => 'Одинцов Серафим Артёмович', 'login' => 'yzls62@outlook.com', 'password' => 'JlFRCZ'],
                ['id' => 4, 'role_id' => 2, 'full_name' => 'Степанов Михаил Артёмович', 'login' => '1diph5e@tutanota.com', 'password' => '8ntwUp'],
                ['id' => 5, 'role_id' => 2, 'full_name' => 'Ворсин Петр Евгеньевич', 'login' => 'tjde7c@yahoo.com', 'password' => 'YOyhfR'],
                ['id' => 6, 'role_id' => 2, 'full_name' => 'Старикова Елена Павловна', 'login' => 'wpmrc3do@tutanota.com', 'password' => 'RSbvHv'],
                ['id' => 7, 'role_id' => 3, 'full_name' => 'Михайлюк Анна Вячеславовна', 'login' => '5d4zbu@tutanota.com', 'password' => 'rwVDh9'],
                ['id' => 8, 'role_id' => 3, 'full_name' => 'Ситдикова Елена Анатольевна', 'login' => 'ptec8ym@yahoo.com', 'password' => 'LdNyos'],
                ['id' => 9, 'role_id' => 3, 'full_name' => 'Никифорова Весения Николаевна', 'login' => '1qz4kw@mail.com', 'password' => 'gynQMT'],
                ['id' => 10, 'role_id' => 3, 'full_name' => 'Сазонов Руслан Германович', 'login' => '4np6se@mail.com', 'password' => 'AtnDjr'],
            ]);

            $this->syncById(Category::class, [
                ['id' => 1, 'name' => 'Общестроительные материалы'],
                ['id' => 2, 'name' => 'Стеновые и фасадные материалы'],
                ['id' => 3, 'name' => 'Сухие строительные смеси и гидроизоляция'],
                ['id' => 4, 'name' => 'Ручной инструмент'],
                ['id' => 5, 'name' => 'Защита лица, глаз, головы'],
            ]);

            $companies = [
                ['id' => 1, 'name' => 'М500'],
                ['id' => 2, 'name' => 'Изостронг'],
                ['id' => 3, 'name' => 'Knauf'],
                ['id' => 4, 'name' => 'MixMaster'],
                ['id' => 5, 'name' => 'ЛСР'],
                ['id' => 6, 'name' => 'ВОЛМА'],
                ['id' => 7, 'name' => 'Vinylon'],
                ['id' => 8, 'name' => 'Павловский завод'],
                ['id' => 9, 'name' => 'Weber'],
                ['id' => 10, 'name' => 'Hesler'],
                ['id' => 11, 'name' => 'Armero'],
                ['id' => 12, 'name' => 'Wenzo Roma'],
                ['id' => 13, 'name' => 'KILIMGRIN'],
                ['id' => 14, 'name' => 'Исток'],
                ['id' => 15, 'name' => 'RUIZ'],
                ['id' => 16, 'name' => 'Husqvarna'],
                ['id' => 17, 'name' => 'Delta'],
            ];

            $this->syncById(Manufacturer::class, $companies);
            $this->syncById(Supplier::class, $companies);

            $this->syncById(Unit::class, [
                ['id' => 1, 'name' => 'шт.'],
            ]);

            $this->syncById(Product::class, [
                ['id' => 1, 'article' => 'PMEZMH', 'name' => 'Цемент', 'description' => 'Цемент Евроцемент М500 Д0 ЦЕМ I 42,5 50 кг', 'category_id' => 1, 'manufacturer_id' => 1, 'supplier_id' => 1, 'unit_id' => 1, 'price' => 440.00, 'discount' => 8, 'stock_quantity' => 34, 'photo' => 'images/PMEZMH.jpg'],
                ['id' => 2, 'article' => 'BPV4MM', 'name' => 'Пленка техническая', 'description' => 'Пленка техническая полиэтиленовая Изостронг 60 мк 3 м рукав 1,5 м, пог.м', 'category_id' => 1, 'manufacturer_id' => 2, 'supplier_id' => 2, 'unit_id' => 1, 'price' => 8.00, 'discount' => 8, 'stock_quantity' => 2, 'photo' => 'images/BPV4MM.jpg'],
                ['id' => 3, 'article' => 'JVL42J', 'name' => 'Пленка техническая', 'description' => 'Пленка техническая полиэтиленовая Изостронг 100 мк 3 м рукав 1,5 м, пог.м', 'category_id' => 1, 'manufacturer_id' => 2, 'supplier_id' => 2, 'unit_id' => 1, 'price' => 13.00, 'discount' => 4, 'stock_quantity' => 34, 'photo' => 'images/JVL42J.jpg'],
                ['id' => 4, 'article' => 'F895RB', 'name' => 'Песок строительный', 'description' => 'Песок строительный 50 кг', 'category_id' => 1, 'manufacturer_id' => 3, 'supplier_id' => 3, 'unit_id' => 1, 'price' => 102.00, 'discount' => 6, 'stock_quantity' => 7, 'photo' => 'images/F895RB.jpg'],
                ['id' => 5, 'article' => '3XBOTN', 'name' => 'Керамзит фракция', 'description' => 'Керамзит фракция 10-20 мм 0,05 куб.м', 'category_id' => 1, 'manufacturer_id' => 4, 'supplier_id' => 4, 'unit_id' => 1, 'price' => 110.00, 'discount' => 5, 'stock_quantity' => 21, 'photo' => 'images/3XBOTN.jpg'],
                ['id' => 6, 'article' => '3L7RCZ', 'name' => 'Газобетон', 'description' => 'Газобетон ЛСР 100х250х625 мм D400', 'category_id' => 2, 'manufacturer_id' => 5, 'supplier_id' => 5, 'unit_id' => 1, 'price' => 7400.00, 'discount' => 2, 'stock_quantity' => 20, 'photo' => 'images/3L7RCZ.jpg'],
                ['id' => 7, 'article' => 'S72AM3', 'name' => 'Пазогребневая плита', 'description' => 'Пазогребневая плита ВОЛМА Гидро 667х500х80 мм полнотелая', 'category_id' => 2, 'manufacturer_id' => 6, 'supplier_id' => 6, 'unit_id' => 1, 'price' => 500.00, 'discount' => 5, 'stock_quantity' => 35, 'photo' => 'images/S72AM3.jpg'],
                ['id' => 8, 'article' => '2G3280', 'name' => 'Угол наружный', 'description' => 'Угол наружный Vinylon 3050 мм серо-голубой', 'category_id' => 2, 'manufacturer_id' => 7, 'supplier_id' => 7, 'unit_id' => 1, 'price' => 795.00, 'discount' => 9, 'stock_quantity' => 20, 'photo' => 'images/2G3280.jpg'],
                ['id' => 9, 'article' => 'MIO8YV', 'name' => 'Кирпич', 'description' => 'Кирпич рядовой Боровичи полнотелый М150 250х120х65 мм 1NF', 'category_id' => 2, 'manufacturer_id' => 6, 'supplier_id' => 6, 'unit_id' => 1, 'price' => 30.00, 'discount' => 9, 'stock_quantity' => 31, 'photo' => 'images/MIO8YV.jpg'],
                ['id' => 10, 'article' => 'UER2QD', 'name' => 'Скоба для пазогребневой плиты', 'description' => 'Скоба для пазогребневой плиты Knauf С1 120х100 мм', 'category_id' => 2, 'manufacturer_id' => 3, 'supplier_id' => 3, 'unit_id' => 1, 'price' => 25.00, 'discount' => 8, 'stock_quantity' => 27, 'photo' => 'images/UER2QD.jpg'],
                ['id' => 11, 'article' => 'ZR70B4', 'name' => 'Кирпич', 'description' => 'Кирпич рядовой силикатный Павловский завод полнотелый М200 250х120х65 мм 1NF', 'category_id' => 2, 'manufacturer_id' => 8, 'supplier_id' => 8, 'unit_id' => 1, 'price' => 16.00, 'discount' => 3, 'stock_quantity' => 0, 'photo' => null],
                ['id' => 12, 'article' => 'LPDDM4', 'name' => 'Штукатурка гипсовая', 'description' => 'Штукатурка гипсовая Knauf Ротбанд 30 кг', 'category_id' => 3, 'manufacturer_id' => 3, 'supplier_id' => 3, 'unit_id' => 1, 'price' => 500.00, 'discount' => 6, 'stock_quantity' => 38, 'photo' => null],
                ['id' => 13, 'article' => 'LQ48MW', 'name' => 'Штукатурка гипсовая', 'description' => 'Штукатурка гипсовая Knauf МП-75 машинная 30 кг', 'category_id' => 3, 'manufacturer_id' => 9, 'supplier_id' => 9, 'unit_id' => 1, 'price' => 462.00, 'discount' => 6, 'stock_quantity' => 33, 'photo' => null],
                ['id' => 14, 'article' => 'O43COU', 'name' => 'Шпаклевка', 'description' => 'Шпаклевка полимерная Weber.vetonit LR + для сухих помещений белая 20 кг', 'category_id' => 3, 'manufacturer_id' => 6, 'supplier_id' => 6, 'unit_id' => 1, 'price' => 750.00, 'discount' => 1, 'stock_quantity' => 16, 'photo' => null],
                ['id' => 15, 'article' => 'M26EXW', 'name' => 'Клей для плитки, керамогранита и камня', 'description' => 'Клей для плитки, керамогранита и камня Крепс Усиленный серый (класс С1) 25 кг', 'category_id' => 3, 'manufacturer_id' => 3, 'supplier_id' => 3, 'unit_id' => 1, 'price' => 340.00, 'discount' => 8, 'stock_quantity' => 0, 'photo' => null],
                ['id' => 16, 'article' => 'K0YACK', 'name' => 'Смесь цементно-песчаная', 'description' => 'Смесь цементно-песчаная (ЦПС) 300 по ТУ MixMaster Универсал 25 кг', 'category_id' => 3, 'manufacturer_id' => 4, 'supplier_id' => 4, 'unit_id' => 1, 'price' => 160.00, 'discount' => 8, 'stock_quantity' => 19, 'photo' => null],
                ['id' => 17, 'article' => 'ASPXSG', 'name' => 'Ровнитель', 'description' => 'Ровнитель (наливной пол) финишный Weber.vetonit 4100 самовыравнивающийся высокопрочный 20 кг', 'category_id' => 3, 'manufacturer_id' => 9, 'supplier_id' => 9, 'unit_id' => 1, 'price' => 711.00, 'discount' => 10, 'stock_quantity' => 20, 'photo' => null],
                ['id' => 18, 'article' => 'ZKQ5FF', 'name' => 'Лезвие для ножа', 'description' => 'Лезвие для ножа Hesler 18 мм прямое (10 шт.)', 'category_id' => 4, 'manufacturer_id' => 10, 'supplier_id' => 10, 'unit_id' => 1, 'price' => 65.00, 'discount' => 6, 'stock_quantity' => 6, 'photo' => null],
                ['id' => 19, 'article' => '4WZEOT', 'name' => 'Лезвие для ножа', 'description' => 'Лезвие для ножа Armero 18 мм прямое (10 шт.)', 'category_id' => 4, 'manufacturer_id' => 11, 'supplier_id' => 11, 'unit_id' => 1, 'price' => 110.00, 'discount' => 6, 'stock_quantity' => 17, 'photo' => null],
                ['id' => 20, 'article' => '4JR1HN', 'name' => 'Шпатель', 'description' => 'Шпатель малярный 100 мм с пластиковой ручкой', 'category_id' => 4, 'manufacturer_id' => 10, 'supplier_id' => 10, 'unit_id' => 1, 'price' => 26.00, 'discount' => 6, 'stock_quantity' => 7, 'photo' => null],
                ['id' => 21, 'article' => 'Z3XFSP', 'name' => 'Нож строительный', 'description' => 'Нож строительный Hesler 18 мм с ломающимся лезвием пластиковый корпус', 'category_id' => 4, 'manufacturer_id' => 10, 'supplier_id' => 10, 'unit_id' => 1, 'price' => 63.00, 'discount' => 8, 'stock_quantity' => 5, 'photo' => null],
                ['id' => 22, 'article' => 'I6MH89', 'name' => 'Валик', 'description' => 'Валик Wenzo Roma полиакрил 250 мм ворс 18 мм для красок грунтов и антисептиков на водной основе с рукояткой', 'category_id' => 4, 'manufacturer_id' => 12, 'supplier_id' => 12, 'unit_id' => 1, 'price' => 326.00, 'discount' => 12, 'stock_quantity' => 3, 'photo' => null],
                ['id' => 23, 'article' => '83M5ME', 'name' => 'Кисть', 'description' => 'Кисть плоская смешанная щетина 100х12 мм для красок и антисептиков на водной основе', 'category_id' => 4, 'manufacturer_id' => 11, 'supplier_id' => 11, 'unit_id' => 1, 'price' => 122.00, 'discount' => 9, 'stock_quantity' => 26, 'photo' => null],
                ['id' => 24, 'article' => '61PGH3', 'name' => 'Очки защитные', 'description' => 'Очки защитные Delta Plus KILIMANDJARO (KILIMGRIN) открытые с прозрачными линзами', 'category_id' => 5, 'manufacturer_id' => 13, 'supplier_id' => 13, 'unit_id' => 1, 'price' => 184.00, 'discount' => 6, 'stock_quantity' => 25, 'photo' => null],
                ['id' => 25, 'article' => 'GN6ICZ', 'name' => 'Каска защитная', 'description' => 'Каска защитная Исток (КАС001О) оранжевая', 'category_id' => 5, 'manufacturer_id' => 14, 'supplier_id' => 14, 'unit_id' => 1, 'price' => 154.00, 'discount' => 15, 'stock_quantity' => 8, 'photo' => null],
                ['id' => 26, 'article' => 'Z3LO0U', 'name' => 'Очки защитные', 'description' => 'Очки защитные Delta Plus RUIZ (RUIZ1VI) закрытые с прозрачными линзами', 'category_id' => 5, 'manufacturer_id' => 15, 'supplier_id' => 15, 'unit_id' => 1, 'price' => 228.00, 'discount' => 9, 'stock_quantity' => 11, 'photo' => null],
                ['id' => 27, 'article' => 'QHNOKR', 'name' => 'Маска защитная', 'description' => 'Маска защитная Исток (ЩИТ001) ударопрочная и термостойкая', 'category_id' => 5, 'manufacturer_id' => 14, 'supplier_id' => 14, 'unit_id' => 1, 'price' => 251.00, 'discount' => 2, 'stock_quantity' => 22, 'photo' => null],
                ['id' => 28, 'article' => 'EQ6RKO', 'name' => 'Подшлемник', 'description' => 'Подшлемник для каски одноразовый', 'category_id' => 5, 'manufacturer_id' => 16, 'supplier_id' => 16, 'unit_id' => 1, 'price' => 36.00, 'discount' => 17, 'stock_quantity' => 22, 'photo' => null],
                ['id' => 29, 'article' => '81F1WG', 'name' => 'Каска защитная', 'description' => 'Каска защитная Delta Plus BASEBALL DIAMOND V UP (DIAM5UPBCFLBS) белая', 'category_id' => 5, 'manufacturer_id' => 17, 'supplier_id' => 17, 'unit_id' => 1, 'price' => 1500.00, 'discount' => 2, 'stock_quantity' => 13, 'photo' => null],
                ['id' => 30, 'article' => '0YGHZ7', 'name' => 'Очки защитные', 'description' => 'Очки защитные Husqvarna Clear (5449638-01) открытые с прозрачными линзами', 'category_id' => 5, 'manufacturer_id' => 16, 'supplier_id' => 16, 'unit_id' => 1, 'price' => 700.00, 'discount' => 9, 'stock_quantity' => 36, 'photo' => null],
            ]);

            $this->syncById(PickupPoint::class, [
                ['id' => 1, 'address' => '420151, г. Лесной, ул. Вишневая, 32'],
                ['id' => 2, 'address' => '125061, г. Лесной, ул. Подгорная, 8'],
                ['id' => 3, 'address' => '630370, г. Лесной, ул. Шоссейная, 24'],
                ['id' => 4, 'address' => '400562, г. Лесной, ул. Зеленая, 32'],
                ['id' => 5, 'address' => '614510, г. Лесной, ул. Маяковского, 47'],
                ['id' => 6, 'address' => '410542, г. Лесной, ул. Светлая, 46'],
                ['id' => 7, 'address' => '620839, г. Лесной, ул. Цветочная, 8'],
                ['id' => 8, 'address' => '443890, г. Лесной, ул. Коммунистическая, 1'],
                ['id' => 9, 'address' => '603379, г. Лесной, ул. Спортивная, 46'],
                ['id' => 10, 'address' => '603721, г. Лесной, ул. Гоголя, 41'],
                ['id' => 11, 'address' => '410172, г. Лесной, ул. Северная, 13'],
                ['id' => 12, 'address' => '614611, г. Лесной, ул. Молодежная, 50'],
                ['id' => 13, 'address' => '454311, г. Лесной, ул. Новая, 19'],
                ['id' => 14, 'address' => '660007, г. Лесной, ул. Октябрьская, 19'],
                ['id' => 15, 'address' => '603036, г. Лесной, ул. Садовая, 4'],
                ['id' => 16, 'address' => '394060, г. Лесной, ул. Фрунзе, 43'],
                ['id' => 17, 'address' => '410661, г. Лесной, ул. Школьная, 50'],
                ['id' => 18, 'address' => '625590, г. Лесной, ул. Коммунистическая, 20'],
                ['id' => 19, 'address' => '625683, г. Лесной, ул. 8 Марта'],
                ['id' => 20, 'address' => '450983, г. Лесной, ул. Комсомольская, 26'],
                ['id' => 21, 'address' => '394782, г. Лесной, ул. Чехова, 3'],
                ['id' => 22, 'address' => '603002, г. Лесной, ул. Дзержинского, 28'],
                ['id' => 23, 'address' => '450558, г. Лесной, ул. Набережная, 30'],
                ['id' => 24, 'address' => '344288, г. Лесной, ул. Чехова, 1'],
                ['id' => 25, 'address' => '614164, г. Лесной, ул. Степная, 30'],
                ['id' => 26, 'address' => '394242, г. Лесной, ул. Коммунистическая, 43'],
                ['id' => 27, 'address' => '660540, г. Лесной, ул. Солнечная, 25'],
                ['id' => 28, 'address' => '125837, г. Лесной, ул. Шоссейная, 40'],
                ['id' => 29, 'address' => '125703, г. Лесной, ул. Партизанская, 49'],
                ['id' => 30, 'address' => '625283, г. Лесной, ул. Победы, 46'],
                ['id' => 31, 'address' => '614753, г. Лесной, ул. Полевая, 35'],
                ['id' => 32, 'address' => '426030, г. Лесной, ул. Маяковского, 44'],
                ['id' => 33, 'address' => '450375, г. Лесной ул. Клубная, 44'],
                ['id' => 34, 'address' => '625560, г. Лесной, ул. Некрасова, 12'],
                ['id' => 35, 'address' => '630201, г. Лесной, ул. Комсомольская, 17'],
                ['id' => 36, 'address' => '190949, г. Лесной, ул. Мичурина, 26'],
            ]);

            $this->syncById(OrderStatus::class, [
                ['id' => 1, 'name' => 'Завершен'],
                ['id' => 2, 'name' => 'Новый'],
            ]);

            $this->syncById(Order::class, [
                ['id' => 1, 'order_date' => '2025-02-27', 'delivery_date' => '2025-04-20', 'pickup_point_id' => 1, 'user_id' => 7, 'receive_code' => 901, 'status_id' => 1],
                ['id' => 2, 'order_date' => '2024-09-28', 'delivery_date' => '2025-04-21', 'pickup_point_id' => 11, 'user_id' => 8, 'receive_code' => 902, 'status_id' => 1],
                ['id' => 3, 'order_date' => '2025-03-21', 'delivery_date' => '2025-04-22', 'pickup_point_id' => 2, 'user_id' => 9, 'receive_code' => 903, 'status_id' => 1],
                ['id' => 4, 'order_date' => '2025-02-20', 'delivery_date' => '2025-04-23', 'pickup_point_id' => 11, 'user_id' => 10, 'receive_code' => 904, 'status_id' => 1],
                ['id' => 5, 'order_date' => '2025-03-17', 'delivery_date' => '2025-04-24', 'pickup_point_id' => 2, 'user_id' => 7, 'receive_code' => 905, 'status_id' => 1],
                ['id' => 6, 'order_date' => '2025-03-01', 'delivery_date' => '2025-04-25', 'pickup_point_id' => 15, 'user_id' => 8, 'receive_code' => 906, 'status_id' => 1],
                ['id' => 7, 'order_date' => '2025-02-28', 'delivery_date' => '2025-04-26', 'pickup_point_id' => 3, 'user_id' => 9, 'receive_code' => 907, 'status_id' => 1],
                ['id' => 8, 'order_date' => '2025-03-31', 'delivery_date' => '2025-04-27', 'pickup_point_id' => 19, 'user_id' => 10, 'receive_code' => 908, 'status_id' => 2],
                ['id' => 9, 'order_date' => '2025-04-02', 'delivery_date' => '2025-04-28', 'pickup_point_id' => 5, 'user_id' => 9, 'receive_code' => 909, 'status_id' => 2],
                ['id' => 10, 'order_date' => '2025-04-03', 'delivery_date' => '2025-04-29', 'pickup_point_id' => 19, 'user_id' => 10, 'receive_code' => 910, 'status_id' => 2],
            ]);

            $this->syncByKeys(OrderItem::class, [
                ['order_id' => 1, 'product_id' => 1, 'quantity' => 2],
                ['order_id' => 1, 'product_id' => 2, 'quantity' => 2],
                ['order_id' => 2, 'product_id' => 3, 'quantity' => 1],
                ['order_id' => 2, 'product_id' => 4, 'quantity' => 1],
                ['order_id' => 3, 'product_id' => 5, 'quantity' => 10],
                ['order_id' => 3, 'product_id' => 6, 'quantity' => 10],
                ['order_id' => 4, 'product_id' => 7, 'quantity' => 5],
                ['order_id' => 4, 'product_id' => 8, 'quantity' => 4],
                ['order_id' => 5, 'product_id' => 9, 'quantity' => 2],
                ['order_id' => 5, 'product_id' => 10, 'quantity' => 2],
                ['order_id' => 6, 'product_id' => 11, 'quantity' => 1],
                ['order_id' => 6, 'product_id' => 12, 'quantity' => 1],
                ['order_id' => 7, 'product_id' => 13, 'quantity' => 10],
                ['order_id' => 7, 'product_id' => 14, 'quantity' => 10],
                ['order_id' => 8, 'product_id' => 15, 'quantity' => 5],
                ['order_id' => 8, 'product_id' => 16, 'quantity' => 4],
                ['order_id' => 9, 'product_id' => 17, 'quantity' => 5],
                ['order_id' => 9, 'product_id' => 18, 'quantity' => 1],
                ['order_id' => 10, 'product_id' => 19, 'quantity' => 5],
                ['order_id' => 10, 'product_id' => 20, 'quantity' => 5],
            ], ['order_id', 'product_id']);
        });
    }

    private function syncById(string $modelClass, array $rows): void
    {
        foreach ($rows as $row) {
            $modelClass::query()->updateOrCreate(['id' => $row['id']], $row);
        }
    }

    private function syncByKeys(string $modelClass, array $rows, array $keys): void
    {
        foreach ($rows as $row) {
            $unique = Arr::only($row, $keys);
            $modelClass::query()->updateOrCreate($unique, $row);
        }
    }
}
