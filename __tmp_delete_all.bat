@echo off
cd /d C:\xampp\htdocs\TravelYuk
del /q tmp_force_check.bat 2>nul
del /q cleanup*.bat 2>nul
del /q runcheck*.txt 2>nul
del /q script*.bat 2>nul
del /q check*.bat 2>nul
del /q final_check*.bat 2>nul
del /q server_run*.bat 2>nul
del /q run_real_check*.bat 2>nul
del /q __tmp_delete_all.bat 2>nul
echo cleanup complete
