@echo off
:loop
php artisan schedule:run
timeout /t 60
goto loop