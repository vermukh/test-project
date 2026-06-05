

<?php $__env->startSection('title', 'Вход в систему'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .login-wrap { display: flex; justify-content: center; padding-top: 6vh; }
    .login-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-top: 4px solid var(--accent);
        border-radius: 10px;
        padding: 32px;
        width: 380px;
        box-shadow: 0 10px 30px rgba(43, 45, 49, .08);
    }
    .login-card h1 { text-align: center; margin-bottom: 6px; }
    .login-card p.hint { text-align: center; color: var(--muted); font-size: 13px; margin-bottom: 22px; }
    .login-card .field { margin-bottom: 14px; }
    .login-card input { width: 100%; }
    .login-card .btn { width: 100%; margin-top: 6px; }
    .divider { text-align: center; color: var(--muted); font-size: 12px; margin: 16px 0 10px; }
    .guest-link { display: block; text-align: center; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="login-wrap">
    <div class="login-card">
        <h1>Вход в систему</h1>
        <p class="hint">Введите логин и пароль вашей учётной записи</p>
        <form method="POST" action="<?php echo e(route('login.attempt')); ?>">
            <?php echo csrf_field(); ?>
            <div class="field">
                <label for="login">Логин</label>
                <input type="text" id="login" name="login" value="<?php echo e(old('login')); ?>"
                       placeholder="например, user@mail.com" required>
            </div>
            <div class="field">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" placeholder="Введите пароль" required>
            </div>
            <button type="submit" class="btn">Войти</button>
        </form>
        <div class="divider">или</div>
        <a class="btn btn-dark guest-link" href="<?php echo e(route('guest')); ?>">Просмотр товаров в роли гостя</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/vermilion/test-project/resources/views/login.blade.php ENDPATH**/ ?>