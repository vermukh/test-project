#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

log() {
    echo
    echo "[setup] $1"
}

fail() {
    echo
    echo "[setup][error] $1" >&2
    exit 1
}

detect_pm() {
    if command -v apt-get >/dev/null 2>&1; then echo apt; return; fi
    if command -v dnf >/dev/null 2>&1; then echo dnf; return; fi
    if command -v yum >/dev/null 2>&1; then echo yum; return; fi
    if command -v pacman >/dev/null 2>&1; then echo pacman; return; fi
    if command -v zypper >/dev/null 2>&1; then echo zypper; return; fi
    echo ""
}

require_php() {
    command -v php >/dev/null 2>&1 || fail "PHP не найден. Установите PHP 8.3+."
}

module_loaded() {
    local module="$1"
    php -m | awk '{print tolower($0)}' | awk -v m="$module" '$0 == m { ok=1 } END { exit(ok ? 0 : 1) }'
}

install_extensions() {
    local required=(bcmath ctype fileinfo json mbstring openssl pdo pdo_mysql tokenizer xml gd curl zip)
    local missing=()

    for ext in "${required[@]}"; do
        module_loaded "$ext" || missing+=("$ext")
    done

    if [[ ${#missing[@]} -eq 0 ]]; then
        log "PHP-расширения в порядке."
        return
    fi

    log "Не найдены расширения: ${missing[*]}"

    local pm
    pm="$(detect_pm)"
    [[ -n "$pm" ]] || fail "Пакетный менеджер не найден. Установите расширения вручную: ${missing[*]}"

    case "$pm" in
        apt)
            local v
            v="$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')"
            local packages=()
            for ext in "${missing[@]}"; do
                case "$ext" in
                    bcmath) packages+=("php${v}-bcmath") ;;
                    mbstring) packages+=("php${v}-mbstring") ;;
                    xml) packages+=("php${v}-xml") ;;
                    pdo_mysql) packages+=("php${v}-mysql") ;;
                    gd) packages+=("php${v}-gd") ;;
                    curl) packages+=("php${v}-curl") ;;
                    zip) packages+=("php${v}-zip") ;;
                esac
            done
            if [[ ${#packages[@]} -gt 0 ]]; then
                sudo apt-get update
                if ! sudo apt-get install -y "${packages[@]}"; then
                    # fallback на неверсированные пакеты
                    local fallback=()
                    for ext in "${missing[@]}"; do
                        case "$ext" in
                            bcmath) fallback+=("php-bcmath") ;;
                            mbstring) fallback+=("php-mbstring") ;;
                            xml) fallback+=("php-xml") ;;
                            pdo_mysql) fallback+=("php-mysql") ;;
                            gd) fallback+=("php-gd") ;;
                            curl) fallback+=("php-curl") ;;
                            zip) fallback+=("php-zip") ;;
                        esac
                    done
                    [[ ${#fallback[@]} -gt 0 ]] && sudo apt-get install -y "${fallback[@]}"
                fi
            fi
            ;;
        dnf)
            sudo dnf install -y php-bcmath php-mbstring php-xml php-mysqlnd php-gd php-curl php-zip || true
            ;;
        yum)
            sudo yum install -y php-bcmath php-mbstring php-xml php-mysqlnd php-gd php-curl php-zip || true
            ;;
        pacman)
            sudo pacman -Sy --noconfirm php php-gd
            ;;
        zypper)
            sudo zypper --non-interactive install php8-bcmath php8-mbstring php8-xml php8-mysql php8-gd php8-curl php8-zip || true
            ;;
    esac

    # Финальная проверка после попытки установки
    local still_missing=()
    for ext in "${required[@]}"; do
        module_loaded "$ext" || still_missing+=("$ext")
    done

    if [[ ${#still_missing[@]} -gt 0 ]]; then
        fail "Не удалось установить все PHP-модули. Установите вручную: ${still_missing[*]}"
    fi

    log "Нужные PHP-расширения установлены."
}

ensure_composer() {
    if command -v composer >/dev/null 2>&1; then
        return
    fi

    local pm
    pm="$(detect_pm)"
    [[ -n "$pm" ]] || fail "Composer не найден. Установите его вручную."

    log "Composer не найден. Устанавливаю через ${pm}..."
    case "$pm" in
        apt) sudo apt-get update && sudo apt-get install -y composer ;;
        dnf) sudo dnf install -y composer ;;
        yum) sudo yum install -y composer ;;
        pacman) sudo pacman -Sy --noconfirm composer ;;
        zypper) sudo zypper --non-interactive install composer ;;
    esac
}

create_env() {
    if [[ ! -f .env ]]; then
        if [[ -f .env.example ]]; then
            cp .env.example .env
        else
            cat > .env <<'EOF'
APP_NAME="Stroy Materials"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=ru
APP_FALLBACK_LOCALE=ru
APP_FAKER_LOCALE=ru_RU

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stroy_materials
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

CACHE_STORE=file
EOF
        fi
    fi
}

set_env_value() {
    local key="$1"
    local value="$2"
    if grep -q "^${key}=" .env; then
        sed -i "s|^${key}=.*|${key}=${value}|" .env
    else
        echo "${key}=${value}" >> .env
    fi
}

ask_db_credentials() {
    log "Введите параметры подключения к БД."

    read -r -p "DB_HOST [127.0.0.1]: " db_host
    read -r -p "DB_PORT [3306]: " db_port
    read -r -p "DB_DATABASE [stroy_materials]: " db_name
    read -r -p "DB_USERNAME [root]: " db_user
    read -r -s -p "DB_PASSWORD [пусто]: " db_pass
    echo

    db_host="${db_host:-127.0.0.1}"
    db_port="${db_port:-3306}"
    db_name="${db_name:-stroy_materials}"
    db_user="${db_user:-root}"

    set_env_value DB_CONNECTION mysql
    set_env_value DB_HOST "$db_host"
    set_env_value DB_PORT "$db_port"
    set_env_value DB_DATABASE "$db_name"
    set_env_value DB_USERNAME "$db_user"
    set_env_value DB_PASSWORD "$db_pass"
}

prepare_laravel_paths() {
    log "Подготавливаю директории Laravel (cache/views/sessions)..."
    mkdir -p \
        bootstrap/cache \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/logs

    chmod -R ug+rw storage bootstrap/cache 2>/dev/null || true
}

start_laravel() {
    local host="0.0.0.0"
    local port="8000"
    local pid_file="storage/logs/laravel-serve.pid"
    local log_file="storage/logs/laravel-serve.log"

    mkdir -p storage/logs

    if [[ -f "$pid_file" ]]; then
        local old_pid
        old_pid="$(cat "$pid_file" 2>/dev/null || true)"
        if [[ -n "${old_pid:-}" ]] && kill -0 "$old_pid" >/dev/null 2>&1; then
            log "Laravel уже запущен (PID ${old_pid})."
            log "Откройте: http://127.0.0.1:${port}"
            return
        fi
    fi

    log "Запускаю Laravel: php artisan serve --host=${host} --port=${port}"
    nohup php artisan serve --host="${host}" --port="${port}" >"$log_file" 2>&1 &
    echo "$!" > "$pid_file"

    sleep 1
    if kill -0 "$(cat "$pid_file")" >/dev/null 2>&1; then
        log "Laravel запущен в фоне. URL: http://127.0.0.1:${port}"
        log "Логи: ${log_file}"
    else
        fail "Не удалось запустить Laravel. Проверьте лог: ${log_file}"
    fi
}

require_php
install_extensions
ensure_composer

log "Устанавливаю зависимости Composer..."
composer install --no-interaction --prefer-dist

create_env
ask_db_credentials
prepare_laravel_paths

log "Генерирую APP_KEY..."
php artisan key:generate --force

log "Выполняю миграции и сиды..."
php artisan migrate --seed --force

start_laravel

log "Готово. Приложение развернуто."
log "Тестовый админ: 94d5ous@gmail.com / uzWC67"
