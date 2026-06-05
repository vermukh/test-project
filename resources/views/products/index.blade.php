@extends('layout')

@section('title', 'Список товаров')

@section('styles')
<style>
    .toolbar {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: flex-end;
        margin-bottom: 18px;
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 14px;
    }
    .toolbar .grow { flex: 1; min-width: 220px; }
    .toolbar input, .toolbar select { width: 100%; }
    .product-card {
        display: flex;
        gap: 16px;
        background: var(--card);
        border: 1px solid var(--graphite);
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 12px;
        align-items: stretch;
    }
    .product-card.clickable { cursor: pointer; }
    .product-card.clickable:hover { outline: 2px solid var(--accent); }
    /* подсветка по размеру действующей скидки */
    .product-card.big-discount { background: var(--discount-bg); }
    /* товара нет на складе */
    .product-card.out-of-stock { background: var(--out-of-stock-bg); }
    .photo-box {
        width: 130px;
        min-height: 110px;
        border: 1px solid var(--line);
        border-radius: 6px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .photo-box img { max-width: 120px; max-height: 100px; object-fit: contain; }
    .info { flex: 1; font-size: 14px; line-height: 1.55; }
    .info .head { font-weight: 700; margin-bottom: 4px; }
    .price-old { text-decoration: line-through; color: #c01818; }
    .price-final { color: #000; font-weight: 600; }
    .discount-box {
        width: 120px;
        flex-shrink: 0;
        border: 1px solid var(--graphite);
        border-radius: 6px;
        background: rgba(255, 255, 255, .65);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        font-size: 13px;
        text-align: center;
    }
    .discount-box b { font-size: 22px; }
    .row-actions { display: flex; flex-direction: column; justify-content: center; }
    .top-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
    .counter { color: var(--muted); font-size: 13px; }
</style>
@endsection

@section('content')
<div class="top-actions">
    <h1 style="margin-bottom:0">Список товаров</h1>
    @if ($role === 'Администратор')
        <div style="display:flex; gap:10px">
            <a class="btn btn-dark" href="{{ route('orders.index') }}">Заказы</a>
            <a class="btn" href="{{ route('products.create') }}">Добавить товар</a>
        </div>
    @elseif ($role === 'Менеджер')
        <a class="btn btn-dark" href="{{ route('orders.index') }}">Заказы</a>
    @endif
</div>

@if ($canFilter)
    {{-- поиск, сортировка и фильтрация работают в реальном времени и применяются совместно --}}
    <form method="GET" action="{{ route('products.index') }}" id="filterForm" class="toolbar">
        <div class="grow">
            <label for="search">Поиск по всем текстовым данным</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}"
                   placeholder="Например: Knauf штукатурка">
        </div>
        <div>
            <label for="manufacturer">Производитель</label>
            <select id="manufacturer" name="manufacturer">
                <option value="">Все производители</option>
                @foreach ($manufacturers as $manufacturer)
                    <option value="{{ $manufacturer->id }}" @selected(request('manufacturer') == $manufacturer->id)>
                        {{ $manufacturer->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="sort">Сортировка</label>
            <select id="sort" name="sort">
                <option value="">Без сортировки</option>
                <option value="stock_quantity" @selected(request('sort') === 'stock_quantity')>По количеству на складе</option>
                <option value="price" @selected(request('sort') === 'price')>По цене</option>
                <option value="discount" @selected(request('sort') === 'discount')>По действующей скидке</option>
            </select>
        </div>
        <div>
            <label for="direction">Направление</label>
            <select id="direction" name="direction">
                <option value="asc" @selected(request('direction') !== 'desc')>По возрастанию</option>
                <option value="desc" @selected(request('direction') === 'desc')>По убыванию</option>
            </select>
        </div>
    </form>
@endif

<p class="counter">Показано товаров: {{ $products->count() }}</p>

@forelse ($products as $product)
    <div class="product-card
            @if ($product->stock_quantity === 0) out-of-stock
            @elseif ($product->discount > 12) big-discount @endif
            @if ($role === 'Администратор') clickable @endif"
        @if ($role === 'Администратор')
            onclick="window.location='{{ route('products.edit', $product) }}'"
            title="Нажмите для редактирования товара"
        @endif
    >
        <div class="photo-box">
            <img src="{{ $product->photo_url }}" alt="{{ $product->name }}">
        </div>
        <div class="info">
            <div class="head">{{ $product->category->name }} | {{ $product->name }}</div>
            <div>Описание товара: {{ $product->description }}</div>
            <div>Производитель: {{ $product->manufacturer->name }}</div>
            <div>Поставщик: {{ $product->supplier->name }}</div>
            <div>Цена:
                @if ($product->discount > 0)
                    {{-- основная цена перечёркнута красным, рядом итоговая чёрным --}}
                    <span class="price-old">{{ number_format($product->price, 2, ',', ' ') }} руб.</span>
                    <span class="price-final">{{ number_format($product->final_price, 2, ',', ' ') }} руб.</span>
                @else
                    <span class="price-final">{{ number_format($product->price, 2, ',', ' ') }} руб.</span>
                @endif
            </div>
            <div>Единица измерения: {{ $product->unit->name }}</div>
            <div>Количество на складе: {{ $product->stock_quantity }}</div>
        </div>
        <div class="discount-box">
            <span>Действующая скидка</span>
            <b>{{ $product->discount }}%</b>
            @if ($role === 'Администратор')
                <form method="POST" action="{{ route('products.destroy', $product) }}"
                      onsubmit="return confirm('Внимание! Товар «{{ $product->name }}» будет удалён безвозвратно. Продолжить?')"
                      onclick="event.stopPropagation()">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="padding:5px 10px; font-size:12px">Удалить</button>
                </form>
            @endif
        </div>
    </div>
@empty
    <div class="flash warning">
        <span class="icon">⚠</span>
        <div>
            <b>Ничего не найдено</b>
            По заданным условиям поиска и фильтрации товары не найдены. Измените условия и попробуйте снова.
        </div>
    </div>
@endforelse

@if ($canFilter)
<script>
    // поиск, фильтрация и сортировка применяются сразу, без кнопки «Найти»
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    let debounceTimer = null;

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => filterForm.submit(), 500);
    });

    ['manufacturer', 'sort', 'direction'].forEach(function (id) {
        document.getElementById(id).addEventListener('change', () => filterForm.submit());
    });

    // курсор остаётся в строке поиска после перезагрузки страницы
    if (searchInput.value) {
        searchInput.focus();
        searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
    }
</script>
@endif
@endsection
