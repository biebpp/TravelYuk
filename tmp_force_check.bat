@echo off
cd /d C:\xampp\htdocs\TravelYuk
php artisan migrate --force
php artisan test --filter=BookingFlowTest --stop-on-failure
