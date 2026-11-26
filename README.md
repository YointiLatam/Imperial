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
| `superadmin` | Todo: productos, catálogos (categorías, marcas) y **usuarios** |
| `trabajador` | Productos y catálogos; editar su propio perfil |
| Visitante | Ver inicio, catálogo, detalle de producto y marcas |

- No hay registro público: los usuarios los crea el superadmin.
- No hay recuperación de contraseña por correo: el login indica escribir a contacto.
- Los roles se manejan con un enum (`app/Enums`) y Policies (`app/Policies`).

## Módulos

**Sitio público**
- Inicio: hero, catálogo destacado, marcas líderes, nosotros (visión / misión), contacto.
- Catálogo: búsqueda, filtros por categoría / tipo / marca, orden por precio y paginación.
- Detalle de producto: galería de imágenes, descripción, características y productos similares.

**Panel (`/admin`)**
- Productos: listar, crear, editar, ver, eliminar y compartir el enlace público.
- Categorías, tipos y marcas.
- Usuarios (solo superadmin).
- Mi perfil.

## Modelo de datos (propuesto)

```
users                     nombres, apellidos, dni, email, telefono_1, telefono_2, role, activo, password
categorias                nombre, slug, activo
tipos                     nombre, slug
marcas                    nombre, slug, logo_path, orden, destacada
productos                 codigo, nombre, slug, descripcion, caracteristicas_principales,
                          categoria_id, tipo_id, marca_id, precio, stock, publicado,
                          created_by, updated_by, timestamps, soft deletes
producto_imagenes         producto_id, path, orden, es_principal
producto_caracteristicas  producto_id, nombre, valor, orden
settings                  clave / valor (contacto, redes, WhatsApp, textos de nosotros)
```

> Pendiente de confirmar: si "tipo" es distinto de "categoría" o una subcategoría (`categorias.parent_id`).

## Rutas

```
/                       Inicio
/catalogo               Catálogo (filtros por query string)
/catalogo/{slug}        Detalle de producto
/login, /logout

/admin                  Panel (auth)
/admin/productos        trabajador + superadmin
/admin/categorias
/admin/marcas
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

Crear la base de datos y ajustar `DB_*` en `.env` según tu MySQL (por defecto: `127.0.0.1:3306`, BD `imperial`, usuario `root` sin contraseña):

```sql
CREATE DATABASE imperial CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

```bash
php artisan migrate --seed
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

- Tablas, columnas y modelos del dominio en español (`Producto`, `Categoria`, `ProductoImagen`). Cuando la pluralización de Laravel no coincide con la tabla (p. ej. `producto_imagenes`), se declara `$table` en el modelo.
- Validación en FormRequests o en los componentes Livewire.
- Imágenes subidas siempre en el disco `public`; en BD solo se guarda la ruta.
- Tests de Feature para los permisos por rol.
