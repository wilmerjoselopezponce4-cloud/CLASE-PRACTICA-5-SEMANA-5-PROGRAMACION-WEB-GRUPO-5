@echo off
setlocal
cd /d "%~dp0"
set "PHPCMD=php"
if exist "C:\xampp\php\php.exe" set "PHPCMD=C:\xampp\php\php.exe"
if not exist "vendor\autoload.php" (
  echo Faltan las dependencias Laravel. Abre CMD y ejecuta: composer install
  pause
  exit /b 1
)
if not exist ".env" (
  echo Falta el archivo .env. Ejecuta: copy .env.example .env
  pause
  exit /b 1
)
echo Iniciando NEXOTASK - CLASE PRÁCTICA 5...
"%PHPCMD%" artisan serve --host=127.0.0.1 --port=8000
pause
