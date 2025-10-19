@echo off
:loop
php artisan schedule:run
timeout /t 10
goto loop