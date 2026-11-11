@echo off
cd /d C:\xampp\htdocs\TravelYuk
call _delete_temp.bat
del /q _run_cleanup*.bat 2>nul
