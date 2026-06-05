@echo off
setlocal EnableExtensions EnableDelayedExpansion

cd /d "%~dp0"

call :require_php
call :check_extensions
call :ensure_composer

call :log "Устанавливаю зависимости Composer..."
composer install --no-interaction --prefer-dist || call :fail "Не удалось выполнить composer install."

call :create_env
call :ask_db_credentials
call :prepare_laravel_paths

call :log "Генерирую APP_KEY..."
php artisan key:generate --force || call :fail "Не удалось сгенерировать APP_KEY."

call :log "Выполняю миграции и сиды..."
php artisan migrate --seed --force || call :fail "Не удалось выполнить миграции/сиды."

call :start_laravel

call :log "Готово. Приложение развернуто."
call :log "Тестовый админ: 94d5ous@gmail.com / uzWC67"
exit /b 0

:log
echo.
echo [setup] %~1
exit /b 0

:fail
echo.
echo [setup][error] %~1
exit /b 1

:require_php
where php >nul 2>&1 || call :fail "PHP не найден. Установите PHP 8.3+ и добавьте в PATH."
exit /b 0

:has_module
set "MODULE=%~1"
php -m | findstr /I /X "%MODULE%" >nul 2>&1
exit /b %errorlevel%

:check_extensions
set "MISSING="
for %%E in (bcmath ctype fileinfo json mbstring openssl pdo pdo_mysql tokenizer xml gd curl zip) do (
    call :has_module %%E
    if errorlevel 1 (
        if defined MISSING (
            set "MISSING=!MISSING!, %%E"
        ) else (
            set "MISSING=%%E"
        )
    )
)

if not defined MISSING (
    call :log "PHP-расширения в порядке."
    exit /b 0
)

call :fail "Не найдены PHP-расширения: !MISSING!. Установите/включите их в php.ini и запустите снова."
exit /b 1

:ensure_composer
where composer >nul 2>&1 && exit /b 0
call :fail "Composer не найден. Установите Composer for Windows и добавьте в PATH."
exit /b 1

:create_env
if exist ".env" exit /b 0

if exist ".env.example" (
    copy /Y ".env.example" ".env" >nul
    exit /b 0
)

(
echo APP_NAME="Stroy Materials"
echo APP_ENV=local
echo APP_KEY=
echo APP_DEBUG=true
echo APP_URL=http://localhost
echo.
echo APP_LOCALE=ru
echo APP_FALLBACK_LOCALE=ru
echo APP_FAKER_LOCALE=ru_RU
echo.
echo DB_CONNECTION=mysql
echo DB_HOST=127.0.0.1
echo DB_PORT=3306
echo DB_DATABASE=stroy_materials
echo DB_USERNAME=root
echo DB_PASSWORD=
echo.
echo SESSION_DRIVER=file
echo SESSION_LIFETIME=120
echo SESSION_ENCRYPT=false
echo SESSION_PATH=/
echo SESSION_DOMAIN=null
echo.
echo BROADCAST_CONNECTION=log
echo FILESYSTEM_DISK=local
echo QUEUE_CONNECTION=sync
echo.
echo CACHE_STORE=file
) > ".env"
exit /b 0

:set_env_value
set "KEY=%~1"
set "VALUE=%~2"
powershell -NoProfile -ExecutionPolicy Bypass -Command ^
    "$path='.env'; $key='%KEY%'; $value='%VALUE%';" ^
    "$content = @(); if (Test-Path $path) { $content = Get-Content -Path $path };" ^
    "$pattern = '^' + [regex]::Escape($key) + '=';" ^
    "$updated = $false;" ^
    "$result = foreach ($line in $content) { if ($line -match $pattern) { $updated = $true; ""$key=$value"" } else { $line } };" ^
    "if (-not $updated) { $result += ""$key=$value"" };" ^
    "Set-Content -Path $path -Value $result -Encoding UTF8"
if errorlevel 1 call :fail "Не удалось обновить .env (%KEY%)."
exit /b 0

:ask_db_credentials
call :log "Введите параметры подключения к БД."

set "DB_HOST=127.0.0.1"
set /p DB_HOST_INPUT=DB_HOST [127.0.0.1]:
if not "%DB_HOST_INPUT%"=="" set "DB_HOST=%DB_HOST_INPUT%"

set "DB_PORT=3306"
set /p DB_PORT_INPUT=DB_PORT [3306]:
if not "%DB_PORT_INPUT%"=="" set "DB_PORT=%DB_PORT_INPUT%"

set "DB_NAME=stroy_materials"
set /p DB_NAME_INPUT=DB_DATABASE [stroy_materials]:
if not "%DB_NAME_INPUT%"=="" set "DB_NAME=%DB_NAME_INPUT%"

set "DB_USER=root"
set /p DB_USER_INPUT=DB_USERNAME [root]:
if not "%DB_USER_INPUT%"=="" set "DB_USER=%DB_USER_INPUT%"

set "DB_PASS="
set /p DB_PASS=DB_PASSWORD [пусто]:

call :set_env_value DB_CONNECTION mysql
call :set_env_value DB_HOST "%DB_HOST%"
call :set_env_value DB_PORT "%DB_PORT%"
call :set_env_value DB_DATABASE "%DB_NAME%"
call :set_env_value DB_USERNAME "%DB_USER%"
call :set_env_value DB_PASSWORD "%DB_PASS%"
exit /b 0

:prepare_laravel_paths
call :log "Подготавливаю директории Laravel (cache/views/sessions)..."
for %%D in (
    "bootstrap\cache"
    "storage\app\public"
    "storage\framework\cache\data"
    "storage\framework\sessions"
    "storage\framework\testing"
    "storage\framework\views"
    "storage\logs"
) do (
    if not exist %%~D mkdir %%~D
)
exit /b 0

:start_laravel
set "HOST=0.0.0.0"
set "PORT=8000"
set "PID_FILE=storage\logs\laravel-serve.pid"
set "LOG_FILE=storage\logs\laravel-serve.log"

if not exist "storage\logs" mkdir "storage\logs"

if exist "%PID_FILE%" (
    set /p OLD_PID=<"%PID_FILE%"
    if not "%OLD_PID%"=="" (
        powershell -NoProfile -ExecutionPolicy Bypass -Command "if (Get-Process -Id %OLD_PID% -ErrorAction SilentlyContinue) { exit 0 } else { exit 1 }"
        if not errorlevel 1 (
            call :log "Laravel уже запущен (PID %OLD_PID%)."
            call :log "Откройте: http://127.0.0.1:%PORT%"
            exit /b 0
        )
    )
)

call :log "Запускаю Laravel: php artisan serve --host=%HOST% --port=%PORT%"
set "NEW_PID="
for /f %%P in (`powershell -NoProfile -ExecutionPolicy Bypass -Command "$log='%LOG_FILE%'; $p=Start-Process php -ArgumentList @('artisan','serve','--host=%HOST%','--port=%PORT%') -RedirectStandardOutput $log -RedirectStandardError $log -PassThru; $p.Id"`) do (
    set "NEW_PID=%%P"
)

if "%NEW_PID%"=="" call :fail "Не удалось запустить Laravel."
echo %NEW_PID%>"%PID_FILE%"

powershell -NoProfile -ExecutionPolicy Bypass -Command "if (Get-Process -Id %NEW_PID% -ErrorAction SilentlyContinue) { exit 0 } else { exit 1 }"
if errorlevel 1 call :fail "Не удалось запустить Laravel. Проверьте лог: %LOG_FILE%"

call :log "Laravel запущен в фоне. URL: http://127.0.0.1:%PORT%"
call :log "Логи: %LOG_FILE%"
exit /b 0
