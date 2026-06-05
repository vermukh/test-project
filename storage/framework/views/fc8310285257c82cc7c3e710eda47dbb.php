

<?php $__env->startSection('title', 'Список товаров'); ?>

<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="top-actions">
    <h1 style="margin-bottom:0">Список товаров</h1>
    <?php if($role === 'Администратор'): ?>
        <div style="display:flex; gap:10px">
            <a class="btn btn-dark" href="<?php echo e(route('orders.index')); ?>">Заказы</a>
            <a class="btn" href="<?php echo e(route('products.create')); ?>">Добавить товар</a>
        </div>
    <?php elseif($role === 'Менеджер'): ?>
        <a class="btn btn-dark" href="<?php echo e(route('orders.index')); ?>">Заказы</a>
    <?php endif; ?>
</div>

<?php if($canFilter): ?>
    
    <form method="GET" action="<?php echo e(route('products.index')); ?>" id="filterForm" class="toolbar">
        <div class="grow">
            <label for="search">Поиск по всем текстовым данным</label>
            <input type="text" id="search" name="search" value="<?php echo e(request('search')); ?>"
                   placeholder="Например: Knauf штукатурка">
        </div>
        <div>
            <label for="manufacturer">Производитель</label>
            <select id="manufacturer" name="manufacturer">
                <option value="">Все производители</option>
                <?php $__currentLoopData = $manufacturers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manufacturer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($manufacturer->id); ?>" <?php if(request('manufacturer') == $manufacturer->id): echo 'selected'; endif; ?>>
                        <?php echo e($manufacturer->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label for="sort">Сортировка</label>
            <select id="sort" name="sort">
                <option value="">Без сортировки</option>
                <option value="stock_quantity" <?php if(request('sort') === 'stock_quantity'): echo 'selected'; endif; ?>>По количеству на складе</option>
                <option value="price" <?php if(request('sort') === 'price'): echo 'selected'; endif; ?>>По цене</option>
                <option value="discount" <?php if(request('sort') === 'discount'): echo 'selected'; endif; ?>>По действующей скидке</option>
            </select>
        </div>
        <div>
            <label for="direction">Направление</label>
            <select id="direction" name="direction">
                <option value="asc" <?php if(request('direction') !== 'desc'): echo 'selected'; endif; ?>>По возрастанию</option>
                <option value="desc" <?php if(request('direction') === 'desc'): echo 'selected'; endif; ?>>По убыванию</option>
            </select>
        </div>
    </form>
<?php endif; ?>

<p class="counter">Показано товаров: <?php echo e($products->count()); ?></p>

<?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="product-card
            <?php if($product->stock_quantity === 0): ?> out-of-stock
            <?php elseif($product->discount > 12): ?> big-discount <?php endif; ?>
            <?php if($role === 'Администратор'): ?> clickable <?php endif; ?>"
        <?php if($role === 'Администратор'): ?>
            onclick="window.location='<?php echo e(route('products.edit', $product)); ?>'"
            title="Нажмите для редактирования товара"
        <?php endif; ?>
    >
        <div class="photo-box">
            <img src="<?php echo e($product->photo_url); ?>" alt="<?php echo e($product->name); ?>">
        </div>
        <div class="info">
            <div class="head"><?php echo e($product->category->name); ?> | <?php echo e($product->name); ?></div>
            <div>Описание товара: <?php echo e($product->description); ?></div>
            <div>Производитель: <?php echo e($product->manufacturer->name); ?></div>
            <div>Поставщик: <?php echo e($product->supplier->name); ?></div>
            <div>Цена:
                <?php if($product->discount > 0): ?>
                    
                    <span class="price-old"><?php echo e(number_format($product->price, 2, ',', ' ')); ?> руб.</span>
                    <span class="price-final"><?php echo e(number_format($product->final_price, 2, ',', ' ')); ?> руб.</span>
                <?php else: ?>
                    <span class="price-final"><?php echo e(number_format($product->price, 2, ',', ' ')); ?> руб.</span>
                <?php endif; ?>
            </div>
            <div>Единица измерения: <?php echo e($product->unit->name); ?></div>
            <div>Количество на складе: <?php echo e($product->stock_quantity); ?></div>
        </div>
        <div class="discount-box">
            <span>Действующая скидка</span>
            <b><?php echo e($product->discount); ?>%</b>
            <?php if($role === 'Администратор'): ?>
                <form method="POST" action="<?php echo e(route('products.destroy', $product)); ?>"
                      onsubmit="return confirm('Внимание! Товар «<?php echo e($product->name); ?>» будет удалён безвозвратно. Продолжить?')"
                      onclick="event.stopPropagation()">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger" style="padding:5px 10px; font-size:12px">Удалить</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="flash warning">
        <span class="icon">⚠</span>
        <div>
            <b>Ничего не найдено</b>
            По заданным условиям поиска и фильтрации товары не найдены. Измените условия и попробуйте снова.
        </div>
    </div>
<?php endif; ?>

<?php if($canFilter): ?>
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
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/vermilion/test-project/resources/views/products/index.blade.php ENDPATH**/ ?>