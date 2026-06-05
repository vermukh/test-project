<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>@yield('title') — СтройМатериалы</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <style>
        :root {
            --graphite: #2b2d31;
            --graphite-light: #3c3f45;
            --concrete: #f0eee9;
            --card: #ffffff;
            --accent: #e8702a;
            --accent-dark: #c75a1c;
            --line: #d8d4cc;
            --text: #26272b;
            --muted: #76726a;
            --discount-bg: #F4A460;
            --out-of-stock-bg: #add8e6;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', 'PT Sans', Verdana, sans-serif;
            background: var(--concrete);
            color: var(--text);
            min-height: 100vh;
        }
        header {
            background: var(--graphite);
            color: #fff;
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            border-bottom: 4px solid var(--accent);
        }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand img { height: 40px; width: 40px; object-fit: contain; background: #fff; border-radius: 6px; padding: 2px; }
        .brand b { font-size: 18px; letter-spacing: .4px; }
        .who { display: flex; align-items: center; gap: 14px; font-size: 14px; }
        .who .name { font-weight: 600; }
        .who .role { color: #cfcabf; font-size: 12px; display: block; text-align: right; }
        main { max-width: 1080px; margin: 0 auto; padding: 24px 16px 48px; }
        h1 { font-size: 22px; margin-bottom: 18px; letter-spacing: .3px; }
        .btn {
            display: inline-block;
            background: var(--accent);
            border: none;
            color: #fff;
            padding: 9px 18px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            font-family: inherit;
        }
        .btn:hover { background: var(--accent-dark); }
        .btn-dark { background: var(--graphite-light); }
        .btn-dark:hover { background: var(--graphite); }
        .btn-danger { background: #b3372f; }
        .btn-danger:hover { background: #92281f; }
        .flash {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 18px;
            border: 1px solid;
            background: var(--card);
        }
        .flash .icon { font-size: 20px; line-height: 1.2; }
        .flash b { display: block; margin-bottom: 2px; }
        .flash.success { border-color: #3d8b40; }
        .flash.success .icon { color: #3d8b40; }
        .flash.error { border-color: #b3372f; }
        .flash.error .icon { color: #b3372f; }
        .flash.warning { border-color: #c98a08; }
        .flash.warning .icon { color: #c98a08; }
        input, select, textarea {
            font-family: inherit;
            font-size: 14px;
            padding: 8px 10px;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: #fff;
            color: var(--text);
        }
        input:focus, select:focus, textarea:focus { outline: 2px solid var(--accent); border-color: var(--accent); }
        label { font-size: 13px; color: var(--muted); display: block; margin-bottom: 4px; }
    </style>
    @yield('styles')
</head>
<body>
<header>
    <div class="brand">
        
        <img src="{{ asset('images/logo.png') }}" alt="Логотип СтройМатериалы">
        <b>СтройМатериалы</b>
    </div>
    <div class="who">
        @if (session('user'))
            <div>
                
                <span class="name">{{ session('user.full_name') }}</span>
                <span class="role">{{ session('user.role') }}</span>
            </div>
            <a class="btn btn-dark" href="{{ route('logout') }}">Выход</a>
        @elseif (!request()->routeIs('login'))
            <div>
                <span class="name">Гость</span>
                <span class="role">просмотр без авторизации</span>
            </div>
            <a class="btn btn-dark" href="{{ route('login') }}">Войти</a>
        @endif
    </div>
</header>
<main>
    @foreach (['success' => '✔', 'error' => '✖', 'warning' => '⚠'] as $type => $icon)
        @if (session($type))
            <div class="flash {{ $type }}">
                <span class="icon">{{ $icon }}</span>
                <div>
                    <b>{{ ['success' => 'Успешно', 'error' => 'Ошибка', 'warning' => 'Предупреждение'][$type] }}</b>
                    {{ session($type) }}
                </div>
            </div>
        @endif
    @endforeach
    @if ($errors->any())
        <div class="flash error">
            <span class="icon">✖</span>
            <div>
                <b>Ошибка заполнения формы</b>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif
    @yield('content')
</main>
</body>
</html>
