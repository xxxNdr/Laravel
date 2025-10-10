# Istruzioni Installazione

```bash
git clone https://github.com/xxxNdr/Laravel.git
cd Laravel/18
composer install
# Importa estetica.sql nel tuo MySQL e configura .env con le tue credenziali database
php artisan key:generate
php artisan serve
# Apri http://localhost:8000
