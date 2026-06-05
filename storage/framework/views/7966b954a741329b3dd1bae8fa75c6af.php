

<?php $__env->startSection('title', $product ? 'Редактирование товара' : 'Добавление товара'); ?>

<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h1><?php echo e($product ? 'Редактирование товара' : 'Добавление товара'); ?></h1>

<div class="form-card">
    <form method="POST" enctype="multipart/form-data"
          action="<?php echo e($product ? route('products.update', $product) : route('products.store')); ?>">
        <?php echo csrf_field(); ?>
        <?php if($product): ?>
            <?php echo method_field('PUT'); ?>
        <?php endif; ?>

        <div class="grid">
            <?php if($product): ?>
                <div>
                    
                    <label for="id">ID товара</label>
                    <input type="text" id="id" value="<?php echo e($product->id); ?>" readonly
                           style="background:#eee" title="ID присваивается автоматически и не изменяется">
                </div>
            <?php endif; ?>
            <div>
                <label for="article">Артикул *</label>
                <input type="text" id="article" name="article" maxlength="20" required
                       value="<?php echo e(old('article', $product->article ?? '')); ?>">
            </div>
            <div class="<?php if(!$product): ?> full <?php endif; ?>">
                <label for="name">Наименование товара *</label>
                <input type="text" id="name" name="name" maxlength="150" required
                       value="<?php echo e(old('name', $product->name ?? '')); ?>">
            </div>
            <div class="full">
                <label for="description">Описание товара</label>
                <textarea id="description" name="description" rows="2"><?php echo e(old('description', $product->description ?? '')); ?></textarea>
            </div>
            <div>
                <label for="category_id">Категория товара *</label>
                <select id="category_id" name="category_id" required>
                    <option value="">— выберите категорию —</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>"
                            <?php if(old('category_id', $product->category_id ?? null) == $category->id): echo 'selected'; endif; ?>>
                            <?php echo e($category->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label for="manufacturer_id">Производитель *</label>
                <select id="manufacturer_id" name="manufacturer_id" required>
                    <option value="">— выберите производителя —</option>
                    <?php $__currentLoopData = $manufacturers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manufacturer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($manufacturer->id); ?>"
                            <?php if(old('manufacturer_id', $product->manufacturer_id ?? null) == $manufacturer->id): echo 'selected'; endif; ?>>
                            <?php echo e($manufacturer->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label for="supplier_id">Поставщик *</label>
                <select id="supplier_id" name="supplier_id" required>
                    <option value="">— выберите поставщика —</option>
                    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($supplier->id); ?>"
                            <?php if(old('supplier_id', $product->supplier_id ?? null) == $supplier->id): echo 'selected'; endif; ?>>
                            <?php echo e($supplier->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label for="unit_id">Единица измерения *</label>
                <select id="unit_id" name="unit_id" required>
                    <option value="">— выберите единицу —</option>
                    <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($unit->id); ?>"
                            <?php if(old('unit_id', $product->unit_id ?? null) == $unit->id): echo 'selected'; endif; ?>>
                            <?php echo e($unit->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label for="price">Цена, руб. *</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required
                       value="<?php echo e(old('price', $product->price ?? '')); ?>">
                <div class="note">Допускаются сотые части, отрицательные значения запрещены</div>
            </div>
            <div>
                <label for="discount">Действующая скидка, % *</label>
                <input type="number" id="discount" name="discount" min="0" max="100" required
                       value="<?php echo e(old('discount', $product->discount ?? 0)); ?>">
            </div>
            <div>
                <label for="stock_quantity">Количество на складе *</label>
                <input type="number" id="stock_quantity" name="stock_quantity" min="0" required
                       value="<?php echo e(old('stock_quantity', $product->stock_quantity ?? 0)); ?>">
            </div>
            <div class="full">
                <label for="photo">Фото товара</label>
                <div class="photo-preview">
                    <img id="photoPreview"
                         src="<?php echo e($product?->photo_url ?? asset('images/picture.png')); ?>"
                         alt="Фото товара">
                    <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png">
                </div>
                <div class="note">JPG или PNG. Изображение будет сохранено в папку приложения с ограничением размера 300×200 пикселей.</div>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn"><?php echo e($product ? 'Сохранить изменения' : 'Добавить товар'); ?></button>
            <a class="btn btn-dark" href="<?php echo e(route('products.index')); ?>">Назад</a>
        </div>
    </form>
</div>

<script>
    // предпросмотр выбранного изображения до сохранения
    document.getElementById('photo').addEventListener('change', function () {
        if (this.files && this.files[0]) {
            document.getElementById('photoPreview').src = URL.createObjectURL(this.files[0]);
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/vermilion/test-project/resources/views/products/form.blade.php ENDPATH**/ ?>