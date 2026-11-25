# Imperial

Proyecto base de Laravel 13 para Imperial Parts Import SAC.

El equipo trabajará frontend y backend en este mismo repositorio. Angular está previsto para la landing; todavía no se ha incorporado en este primer commit. Los archivos de Vite y la página de bienvenida son los originales de Laravel y no representan el frontend final.

## Requisitos

- PHP 8.3 o posterior compatible con Composer y las dependencias de composer.lock.
- Composer 2.
- Extensiones PHP habituales de Laravel, incluyendo PDO y pdo_sqlite para desarrollo local.
- Node.js y npm para la futura integración del frontend.

## Instalación local

Desde la raíz del repositorio:

```bash
composer install
cp .env.example .env
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate
php artisan serve
```

Abrir http://127.0.0.1:8000. En PowerShell, usar `Copy-Item .env.example .env` en lugar de `cp` si fuera necesario. Si PHP no está en PATH y se usa XAMPP, ejecutarlo mediante `C:\xampp\php\php.exe`.

La configuración inicial usa SQLite para desarrollo. Cada integrante genera su propia clave y base local. La base, `.env`, `vendor/` y `node_modules/` no se versionan.

## Comprobaciones

```bash
composer validate --strict
php artisan test
```

## Alcance de esta entrega

- Esqueleto oficial nuevo de Laravel, sin copiar lógica ni datos de SitioDigital.
- Dependencias PHP fijadas en composer.lock.
- Sin autenticación personalizada, catálogo, endpoints de negocio ni landing implementados todavía.
- Integración de Angular pendiente como siguiente etapa dentro del mismo proyecto.