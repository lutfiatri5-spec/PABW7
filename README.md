# LaporBanjir

Aplikasi web pelaporan banjir berbasis Laravel.

## Persyaratan

- PHP dan Composer
- MySQL
- Node.js dan npm

## Instalasi

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Atur koneksi database pada file `.env`, lalu jalankan:

```bash
php artisan migrate
php artisan storage:link
npm install
npm run build
php artisan serve
```

Aplikasi tersedia di `http://localhost:8000`.
