php artisan migrate:fresh --seed --force && php artisan test --filter=BookingFlowTest --stop-on-failure && php artisan test --filter=AuthenticationTest --stop-on-failure
