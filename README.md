# Laravel Project - Installation Guide

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

---

## 📌 Requirements

Before installing the project, make sure you have the following installed:

- PHP >= 8.x
- Composer
- MySQL or any supported database
- Node.js & npm (optional for frontend)

---

## ⚙️ Installation خطوات التثبيت

### 1. Clone the repository

```bash
git clone https://github.com/hafnaouichaima557-cell/docummentaire-back-end-.git
cd docummentaire-back-end-
```
2. Install dependencies
```bash
composer install
```
3. Create environment file
```bash
cp .env.example .env
```
4. Generate application key
```bash
php artisan key:generate
```
5. Configure database

Open .env file and update:
```bash
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=
```
6. Run migrations
```bash
php artisan migrate
```
(Optional: with seeders)

php artisan migrate --seed
7. Install frontend dependencies (optional)
```bash
npm install
npm run dev
```
8. Start the server
```bash
php artisan serve
```
Then open:
```
http://127.0.0.1:8000
```
