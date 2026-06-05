@extends('layout')

@section('title', $product ? 'Редактирование товара' : 'Добавление товара')

@section('styles')
<style>
    .form-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-top: 4px solid var(--accent);
        border-radius: 10px;
        padding: 26px;
        max-width: 760px;
    }
    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 18px; }
    .grid .full { grid-column: 1 / -1; }
    .grid input, .grid select, .grid textarea { width: 100%; }
    .photo-preview {
        display: flex;
        gap: 16px;
        align-items: center;
        margin-bottom: 6px;
    }
    .photo-preview img {
        max-width: 150px;
        max-height: 100px;
        border: 1px solid var(--line);
        border-radius: 6px;
        background: #fff;
        object-fit: contain;
    }
    .actions { margin-top: 20px; display: flex; gap: 10px; }
    .note { font-size: 12px; color: var(--muted); margin-top: 3px; }
</style>
@endsection

@section('content')
<h1>{{ $product ? 'Редактирование товара' : 'Добавление товара' }}</h1>

<div class="form-card">
    <form method="POST" enctype="multipart/form-data"
          action="{{ $product ? route('products.update', $product) : route('products.store') }}">
        @csrf
        @if ($product)
            @method('PUT')
        @endif

        <div class="grid">
            @if ($product)
                <div>
                    
                    <label for="id">ID товара</label>
                    <input type="text" id="id" value="{{ $product->id }}" readonly
                           style="background:#eee" title="ID присваивается автоматически и не изменяется">
                </div>
            @endif
            <div>
                <label for="article">Артикул *</label>
                <input type="text" id="article" name="article" maxlength="20" required
                       value="{{ old('article', $product->article ?? '') }}">
            </div>
            <div class="@if(!$product) full @endif">
                <label for="name">Наименование товара *</label>
                <input type="text" id="name" name="name" maxlength="150" required
                       value="{{ old('name', $product->name ?? '') }}">
            </div>
            <div class="full">
                <label for="description">Описание товара</label>
                <textarea id="description" name="description" rows="2">{{ old('description', $product->description ?? '') }}</textarea>
            </div>
            <div>
                <label for="category_id">Категория товара *</label>
                <select id="category_id" name="category_id" required>
                    <option value="">— выберите категорию —</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            @selected(old('category_id', $product->category_id ?? null) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="manufacturer_id">Производитель *</label>
                <select id="manufacturer_id" name="manufacturer_id" required>
                    <option value="">— выберите производителя —</option>
                    @foreach ($manufacturers as $manufacturer)
                        <option value="{{ $manufacturer->id }}"
                            @selected(old('manufacturer_id', $product->manufacturer_id ?? null) == $manufacturer->id)>
                            {{ $manufacturer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="supplier_id">Поставщик *</label>
                <select id="supplier_id" name="supplier_id" required>
                    <option value="">— выберите поставщика —</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}"
                            @selected(old('supplier_id', $product->supplier_id ?? null) == $supplier->id)>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="unit_id">Единица измерения *</label>
                <select id="unit_id" name="unit_id" required>
                    <option value="">— выберите единицу —</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}"
                            @selected(old('unit_id', $product->unit_id ?? null) == $unit->id)>
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="price">Цена, руб. *</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required
                       value="{{ old('price', $product->price ?? '') }}">
                <div class="note">Допускаются сотые части, отрицательные значения запрещены</div>
            </div>
            <div>
                <label for="discount">Действующая скидка, % *</label>
                <input type="number" id="discount" name="discount" min="0" max="100" required
                       value="{{ old('discount', $product->discount ?? 0) }}">
            </div>
            <div>
                <label for="stock_quantity">Количество на складе *</label>
                <input type="number" id="stock_quantity" name="stock_quantity" min="0" required
                       value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}">
            </div>
            <div class="full">
                <label for="photo">Фото товара</label>
                <div class="photo-preview">
                    <img id="photoPreview"
                         src="{{ $product?->photo_url ?? asset('images/picture.png') }}"
                         alt="Фото товара">
                    <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png">
                </div>
                <div class="note">JPG или PNG. Изображение будет сохранено в папку приложения с ограничением размера 300×200 пикселей.</div>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn">{{ $product ? 'Сохранить изменения' : 'Добавить товар' }}</button>
            <a class="btn btn-dark" href="{{ route('products.index') }}">Назад</a>
        </div>
    </form>
</div>

<script>
    document.getElementById('photo').addEventListener('change', function () {
        if (this.files && this.files[0]) {
            document.getElementById('photoPreview').src = URL.createObjectURL(this.files[0]);
        }
    });
</script>
@endsection
