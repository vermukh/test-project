<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private const PHOTO_MAX_WIDTH  = 300;
    private const PHOTO_MAX_HEIGHT = 200;

    public function index(Request $request)
    {
        $role = session('user.role', 'Гость');
        $canFilter = in_array($role, ['Менеджер', 'Администратор'], true);

        $query = Product::with(['category', 'manufacturer', 'supplier', 'unit']);

        // поиск, сортировка и фильтрация доступны только менеджеру и администратору
        if ($canFilter) {
            if ($search = trim((string) $request->input('search'))) {
                // поиск по всем текстовым атрибутам, в том числе по связанным таблицам
                $query->where(function ($q) use ($search) {
                    $like = '%' . $search . '%';
                    $q->where('name', 'like', $like)
                        ->orWhere('article', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhereHas('category', fn ($r) => $r->where('name', 'like', $like))
                        ->orWhereHas('manufacturer', fn ($r) => $r->where('name', 'like', $like))
                        ->orWhereHas('supplier', fn ($r) => $r->where('name', 'like', $like))
                        ->orWhereHas('unit', fn ($r) => $r->where('name', 'like', $like));
                });
            }

            if ($manufacturerId = $request->input('manufacturer')) {
                $query->where('manufacturer_id', $manufacturerId);
            }

            $sortable = ['stock_quantity', 'price', 'discount'];
            $sort = $request->input('sort');
            $direction = $request->input('direction') === 'desc' ? 'desc' : 'asc';
            if (in_array($sort, $sortable, true)) {
                $query->orderBy($sort, $direction);
            }
        }

        return view('products.index', [
            'products'      => $query->orderBy('id')->get(),
            'manufacturers' => Manufacturer::orderBy('name')->get(),
            'role'          => $role,
            'canFilter'     => $canFilter,
        ]);
    }

    public function create()
    {
        return view('products.form', [
            'product' => null,
        ] + $this->dictionaries());
    }

    public function edit(Product $product)
    {
        return view('products.form', [
            'product' => $product,
        ] + $this->dictionaries());
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $product = new Product($data);

        if ($request->hasFile('photo')) {
            $product->photo = $this->savePhoto($request, $data['article']);
        }
        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Товар «' . $product->name . '» успешно добавлен.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product->id);

        if ($request->hasFile('photo')) {
            // при замене изображения старое фото удаляется из папки
            if ($product->photo && file_exists(public_path($product->photo))) {
                unlink(public_path($product->photo));
            }
            $data['photo'] = $this->savePhoto($request, $data['article']);
        }
        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Товар «' . $product->name . '» успешно изменён.');
    }

    public function destroy(Product $product)
    {
        // товар, который присутствует в заказе, удалить нельзя
        if ($product->orderItems()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'Удаление невозможно: товар «' . $product->name
                    . '» присутствует в заказах. Сначала удалите соответствующие заказы.');
        }

        if ($product->photo && file_exists(public_path($product->photo))) {
            unlink(public_path($product->photo));
        }
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Товар «' . $product->name . '» удалён.');
    }

    private function dictionaries(): array
    {
        return [
            'categories'    => Category::orderBy('name')->get(),
            'manufacturers' => Manufacturer::orderBy('name')->get(),
            'suppliers'     => Supplier::orderBy('name')->get(),
            'units'         => Unit::orderBy('name')->get(),
        ];
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'article'         => 'required|string|max:20|unique:products,article' . ($ignoreId ? ',' . $ignoreId : ''),
            'name'            => 'required|string|max:150',
            'description'     => 'nullable|string',
            'category_id'     => 'required|exists:categories,id',
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'supplier_id'     => 'required|exists:suppliers,id',
            'unit_id'         => 'required|exists:units,id',
            'price'           => 'required|numeric|min:0',
            'discount'        => 'required|integer|min:0|max:100',
            'stock_quantity'  => 'required|integer|min:0',
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'article.required'        => 'Укажите артикул товара.',
            'article.unique'          => 'Товар с таким артикулом уже существует. Укажите другой артикул.',
            'name.required'           => 'Укажите наименование товара.',
            'category_id.required'    => 'Выберите категорию товара из списка.',
            'manufacturer_id.required' => 'Выберите производителя из списка.',
            'supplier_id.required'    => 'Выберите поставщика из списка.',
            'unit_id.required'        => 'Выберите единицу измерения.',
            'price.required'          => 'Укажите цену товара.',
            'price.numeric'           => 'Цена должна быть числом (допускаются сотые части).',
            'price.min'               => 'Цена не может быть отрицательной.',
            'discount.required'       => 'Укажите действующую скидку (0, если скидки нет).',
            'discount.min'            => 'Скидка не может быть отрицательной.',
            'discount.max'            => 'Скидка не может превышать 100%.',
            'stock_quantity.required' => 'Укажите количество товара на складе.',
            'stock_quantity.integer'  => 'Количество должно быть целым числом.',
            'stock_quantity.min'      => 'Количество не может быть отрицательным.',
            'photo.image'             => 'Файл изображения должен быть в формате JPG или PNG.',
            'photo.max'               => 'Размер файла изображения не должен превышать 5 МБ.',
        ]);
    }

    /**
     * Сохраняет фото в папку с приложением (public/images),
     * уменьшая его до ограничения 300x200 пикселей. Возвращает путь для БД.
     */
    private function savePhoto(Request $request, string $article): string
    {
        $file = $request->file('photo');
        $src = $file->getMimeType() === 'image/png'
            ? imagecreatefrompng($file->getPathname())
            : imagecreatefromjpeg($file->getPathname());

        $w = imagesx($src);
        $h = imagesy($src);
        $scale = min(self::PHOTO_MAX_WIDTH / $w, self::PHOTO_MAX_HEIGHT / $h, 1);
        $nw = (int) round($w * $scale);
        $nh = (int) round($h * $scale);

        $dst = imagecreatetruecolor($nw, $nh);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        $name = 'images/' . $article . '_' . time() . '.jpg';
        imagejpeg($dst, public_path($name), 90);
        imagedestroy($src);
        imagedestroy($dst);

        return $name;
    }
}
