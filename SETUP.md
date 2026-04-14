# Belinze Portfolio — Setup Instructions

## Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL

## Setup Steps

### 1. Install PHP dependencies
```bash
composer install
```

### 2. Environment configuration
```bash
cp .env.example .env
php artisan key:generate
```
Edit `.env` and set your MySQL credentials:
```
DB_DATABASE=belinze_portfolio
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Create the database
```sql
CREATE DATABASE belinze_portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run migrations and seed data
```bash
php artisan migrate
php artisan db:seed
```

### 5. Create storage symlink
```bash
php artisan storage:link
```

### 6. Install and build frontend assets
```bash
npm install
npm run build
```

### 7. Start the server
```bash
php artisan serve
```

Visit: http://localhost:8000

## Admin Panel

URL: http://localhost:8000/admin/login  
Password: `admin123` (set `ADMIN_PASSWORD` in `.env` to change)

## Development (hot reload)

Run both in separate terminals:
```bash
php artisan serve
npm run dev
```

## Notes
- Profile photo and project/company logos are stored in `storage/app/public`
- The public portfolio is at `/`
- The admin panel is at `/admin`
- All profile data (Belinze's work, education) is pre-seeded
