# Imperial Parts Import SAC

Landing page y catálogo de autopartes de **Imperial Parts Import SAC**, con un panel de administración para gestionar productos y usuarios.

Desarrollado por **Yointi Latam**.

## Stack

| Capa | Tecnología |
|---|---|
| Backend | Laravel 13 (PHP 8.3+) |
| Vistas públicas | Blade, renderizado en servidor (SEO del catálogo) |
| Panel de administración | Livewire 4 + Alpine.js (incluido en Livewire) |
| Estilos | Tailwind CSS 4 vía Vite |
| Base de datos | MySQL 8 |
| Autenticación | Sesión de Laravel (login propio, sin registro público) |

Es un único repositorio: no hay API ni frontend separado.

## Roles

| Rol | Permisos |
|---|---|
| `superadmin` | Todo: productos, marcas y **usuarios** |
| `trabajador` | Productos y marcas; editar su propio perfil |
| Visitante | Ver inicio, catálogo, detalle de producto y marcas |

- No hay registro público: los usuarios los crea el superadmin.
- No hay recuperación de contraseña por correo: el login indica escribir a contacto.
- Los roles se manejan con un enum (`App\Enums\Role`) y Policies (`app/Policies`).

## Módulos

**Sitio público**
- Inicio: hero, buscador "Encuentra tu repuesto", productos, novedades (los más recientes), marcas, nosotros y contacto.
- Catálogo: búsqueda por pieza o referencia (nombre, código, OEM, aplicación) y filtros por sistema, marca y OEM.
- Detalle de producto: imagen, marca con su logo, código, OEM, descripción, aplicación y productos similares (mismo sistema).
- Marcas: listado de marcas; cada una lleva al catálogo filtrado.

**Panel (`/admin`)**
- Productos: listar, crear, editar, ver, eliminar y compartir el enlace público. Las marcas nuevas se crean desde el mismo formulario.
- Usuarios (solo superadmin).
- Mi perfil.

## Modelo de datos

```
users ──(created_by / updated_by)──┐
marcas   ──<                       ▼
sistemas ──<  productos
```

| Tabla | Columnas |
|---|---|
| `users` | nombres, apellidos, dni (único), email (único, login), telefono, role, password, timestamps, soft delete |
| `marcas` | nombre (único), slug, logo_path |
| `sistemas` | nombre (único), slug |
| `productos` | nombre, slug, marca_id, sistema_id, codigo (único), oem, precio, stock, descripcion, aplicacion, imagen_path, created_by, updated_by, timestamps, soft delete |

- **Sistema** es la parte del vehículo a la que pertenece el repuesto (Frenos, Suspensión, Motor...) y hace de categoría del catálogo. La lista se carga con `SistemaSeeder`.
- **Aplicación** es el vehículo en el que se usa (Volvo FH, Scania P...).
- "EN STOCK / FUERA DE STOCK" se calcula con `stock > 0` (`$producto->en_stock`).
- El slug del producto se genera al crearlo (`nombre-codigo`) y no cambia, para no romper enlaces compartidos.
- `created_by` / `updated_by` se llenan solos con el usuario autenticado.
- No se puede borrar una marca o un sistema que tenga productos. Productos y usuarios usan soft delete.
- Contacto, redes y textos de "Nosotros" van en `config/imperial.php`, no en la BD.

## Rutas

```
/                       Inicio
/catalogo               Catálogo (filtros por query string)
/catalogo/{slug}        Detalle de producto
/marcas                 Marcas
/login, /logout

/admin                  Panel (auth)
/admin/productos        trabajador + superadmin
/admin/perfil
/admin/usuarios         solo superadmin
```

## Estructura de carpetas

```
app/
  Enums/                        Role y otros enums
  Http/Controllers/Auth/        Login / logout
  Http/Controllers/Public/      Inicio, catálogo, detalle
  Http/Middleware/              Restricción por rol
  Livewire/Admin/               Componentes del panel (lógica)
  Models/
  Policies/                     ProductoPolicy, UserPolicy
database/
  migrations/  factories/  seeders/
resources/
  views/layouts/                Layout público y layout del panel
  views/components/             Componentes Blade reutilizables (header, footer, tarjeta de producto)
  views/public/                 Páginas del sitio
  views/auth/                   Login
  views/admin/                  Páginas del panel
  views/livewire/admin/         Vistas de los componentes Livewire
  css/  js/
public/images/                  Logo, imágenes estáticas del diseño
storage/app/public/             Imágenes subidas (productos, marcas) → public/storage
```

Los componentes Livewire se hacen **con clase** (`app/Livewire/Admin`) y su vista aparte (`resources/views/livewire/admin`), para que la lógica no se toque cuando llegue el diseño final.

## Instalación local

Requisitos: PHP 8.3+, Composer, Node.js 20.19+ / 22+ y MySQL 8 (Laragon, XAMPP o similar).

```bash
git clone https://github.com/YointiLatam/Imperial.git
cd Imperial
composer install
cp .env.example .env          # en Windows: copy .env.example .env
php artisan key:generate
```

Crear la base de datos y ajustar `DB_*` en `.env` según tu MySQL (por defecto: `127.0.0.1:3306`, BD `imperial`, usuario `root` sin contraseña). Completar también `ADMIN_EMAIL` y `ADMIN_PASSWORD`: con esos datos se crea el primer superadmin.

```sql
CREATE DATABASE imperial CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

```bash
php artisan migrate --seed    # sistemas, superadmin y (en local) datos de prueba
php artisan storage:link
npm install
npm run build
php artisan serve             # http://localhost:8000
```

### Comandos frecuentes

```bash
npm run dev                                       # Vite con recarga en caliente
php artisan migrate
php artisan make:livewire Admin/Productos --class
php artisan test
```

## Convenciones

- Tablas, columnas y modelos del dominio en español (`Producto`, `Marca`, `Sistema`). Si un modelo nuevo no pluraliza bien con las reglas de Laravel, se declara `$table`.
- Validación en FormRequests o en los componentes Livewire.
- Imágenes subidas siempre en el disco `public`; en BD solo se guarda la ruta.
- Tests de Feature para los permisos por rol.
