@echo off
setlocal enabledelayedexpansion

:: Colors for output
set "RED=[91m"
set "GREEN=[92m"
set "YELLOW=[93m"
set "NC=[0m"

:: Function to print status messages
call :print_status "Starting deployment process..."

:: Check if running as administrator
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo %RED%Please run as administrator%NC%
    exit /b 1
)

:: Check required commands
where php >nul 2>&1
if %errorLevel% neq 0 (
    echo %RED%PHP is required but not installed%NC%
    exit /b 1
)

where composer >nul 2>&1
if %errorLevel% neq 0 (
    echo %RED%Composer is required but not installed%NC%
    exit /b 1
)

where mysql >nul 2>&1
if %errorLevel% neq 0 (
    echo %RED%MySQL is required but not installed%NC%
    exit /b 1
)

:: Create main database
call :print_status "Creating main database (general)..."
mysql -e "CREATE DATABASE IF NOT EXISTS general CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
if %errorLevel% neq 0 (
    call :print_error "Failed to create main database"
    exit /b 1
)
call :print_success "Main database created successfully"

:: Install dependencies
call :print_status "Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader
if %errorLevel% neq 0 (
    call :print_error "Failed to install dependencies"
    exit /b 1
)
call :print_success "Dependencies installed successfully"

:: Copy environment file
call :print_status "Setting up environment file..."
if not exist .env (
    copy .env.example .env
    call :print_success "Environment file created"
) else (
    call :print_status "Environment file already exists"
)

:: Generate application key
call :print_status "Generating application key..."
php artisan key:generate
if %errorLevel% neq 0 (
    call :print_error "Failed to generate application key"
    exit /b 1
)
call :print_success "Application key generated"

:: Set proper permissions (Windows specific)
call :print_status "Setting file permissions..."
icacls storage /grant "IUSR:(OI)(CI)F" /T
icacls bootstrap/cache /grant "IUSR:(OI)(CI)F" /T
call :print_success "Permissions set successfully"

:: Run migrations
call :print_status "Running database migrations..."
php artisan migrate --force
if %errorLevel% neq 0 (
    call :print_error "Failed to run migrations"
    exit /b 1
)
call :print_success "Migrations completed successfully"

:: Run seeders
call :print_status "Running database seeders..."
php artisan db:seed --force
if %errorLevel% neq 0 (
    call :print_error "Failed to run seeders"
    exit /b 1
)
call :print_success "Seeders completed successfully"

:: Clear cache
call :print_status "Clearing application cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
call :print_success "Cache cleared successfully"

:: Optimize application
call :print_status "Optimizing application..."
php artisan optimize
if %errorLevel% neq 0 (
    call :print_error "Failed to optimize application"
    exit /b 1
)
call :print_success "Application optimized successfully"

:: Create storage link
call :print_status "Creating storage link..."
php artisan storage:link
if %errorLevel% neq 0 (
    call :print_error "Failed to create storage link"
    exit /b 1
)
call :print_success "Storage link created successfully"

:: Set up Windows Task Scheduler for Laravel scheduler
call :print_status "Setting up Laravel scheduler task..."
schtasks /create /tn "Laravel Scheduler" /tr "php %CD%\artisan schedule:run" /sc minute /mo 1 /ru SYSTEM
call :print_success "Scheduler task set up successfully"

:: Final message
call :print_success "Deployment completed successfully!"
echo Next steps:
echo 1. Configure your web server (IIS/Apache)
echo 2. Set up SSL certificate if needed
echo 3. Configure your domain
echo 4. Test the registration process

exit /b 0

:print_status
echo %YELLOW%[*] %~1%NC%
exit /b 0

:print_success
echo %GREEN%[+] %~1%NC%
exit /b 0

:print_error
echo %RED%[-] %~1%NC%
exit /b 0 