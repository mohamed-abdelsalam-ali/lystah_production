# Multi-Tenant Company Registration System

This document outlines all the files and routes needed for the company registration system in a multi-tenant Laravel application.

## Database Structure

1. **Main Database (general)**
   - Contains user accounts and company information
   - Used for authentication and company management

2. **Company Databases**
   - Each company has its own database (e.g., `company_companyname`)
   - Contains company-specific data and permissions

## Required Files

### 1. Controllers
- `app/Http/Controllers/CompanyRegesterController.php`
  - Handles company registration
  - Manages database creation and user setup
  - Routes:
    - `POST /register` - Company registration endpoint
    - `GET /register` - Registration form view

### 2. Models
- `app/Models/User.php`
  - Handles user authentication
  - Manages database switching
  - Contains company-specific user data

### 3. Middleware
- `app/Http/Middleware/SwitchDatabase.php`
  - Handles database switching based on authenticated user
  - Registered in `app/Http/Kernel.php`

### 4. Database Migrations
- `database/migrations/2014_10_12_000000_create_users_table.php`
  - Base users table in general database
- `database/migrations/2014_10_12_100000_add_company_fields_to_users_table.php`
  - Additional fields for company management
- `database/migrations/2014_10_12_200000_create_roles_table.php`
  - Roles table for permission management
- `database/migrations/2014_10_12_300000_create_permissions_table.php`
  - Permissions table for role-based access

### 5. Views
- `resources/views/auth/register.blade.php`
  - Company registration form
- `resources/views/layouts/master.blade.php`
  - Base layout template
- `resources/views/company/dashboard.blade.php`
  - Company dashboard after registration

### 6. Routes
```php
// routes/web.php
Route::get('/register', [CompanyRegesterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [CompanyRegesterController::class, 'register'])->name('register.submit');
Route::get('/company/dashboard', [CompanyDashboardController::class, 'index'])->name('company.dashboard')->middleware(['auth', 'switch.database']);
```

### 7. Configuration Files
- `.env`
  - Database configuration
  - Base database settings
- `config/database.php`
  - Database connection settings
- `config/auth.php`
  - Authentication configuration

### 8. Seeders
- `database/seeders/DatabaseSeeder.php`
  - Main seeder for general database
- `database/seeders/RoleSeeder.php`
  - Default roles setup
- `database/seeders/PermissionSeeder.php`
  - Default permissions setup

## Registration Flow

1. User accesses `/register` route
2. Fills out registration form with:
   - Company name
   - Company logo (optional)
   - User name
   - Email
   - Password

3. System processes registration:
   - Validates input
   - Creates user in general database
   - Creates company-specific database
   - Sets up permissions and roles
   - Logs in user
   - Redirects to company dashboard

## Database Switching Process

1. User logs in
2. `SwitchDatabase` middleware:
   - Checks authenticated user
   - Switches to company database
   - Maintains connection throughout session
   - Restores original database on logout

## Required Dependencies

```json
{
    "require": {
        "laravel/framework": "^10.0",
        "spatie/laravel-permission": "^5.0"
    }
}
```

## Setup Instructions

1. Create main database (general)
2. Run migrations:
   ```bash
   php artisan migrate
   ```
3. Run seeders:
   ```bash
   php artisan db:seed
   ```
4. Configure `.env` file with database credentials
5. Set proper permissions for storage directory
6. Configure web server (Apache/Nginx)

## Security Considerations

1. Database isolation between companies
2. Role-based access control
3. Secure password handling
4. Input validation
5. File upload security
6. Session management

## Troubleshooting

1. Check `storage/logs/laravel.log` for errors
2. Verify database permissions
3. Check file permissions
4. Verify database connections
5. Check session configuration

## Support

For issues or questions, please check:
1. Laravel documentation
2. Spatie Permission package documentation
3. Server logs
4. Application logs