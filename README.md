# Laravel LaunchKit

Laravel LaunchKit is a clean open source Laravel starter kit for developers who want to start new projects faster with a modern dashboard, authentication, roles, settings, activity logs and a simple file manager.

**GitHub project name:** Laravel LaunchKit  
**GitHub repository slug:** `laravel-launchkit`  
**Repository URL:** https://github.com/mchtylmz/laravel-launchkit

## Why This Project Exists

Most Laravel projects need the same foundation: authentication, profile management, roles, settings, UI layout, activity tracking and basic file handling. Laravel LaunchKit provides those pieces in a simple, readable and GitHub-friendly codebase without a heavy admin panel dependency.

## Features

- Authentication: login, register and logout
- User profile page
- Password change
- Avatar upload
- Role and permission infrastructure
- Settings page
- Dark mode with local preference
- Responsive sidebar
- Notification dropdown
- Activity log
- Simple file manager
- Dashboard statistics cards
- Demo user seeders

## Tech Stack

- Laravel 13
- PHP 8.4 compatible
- SQLite by default
- Vite
- TailwindCSS
- Alpine.js
- Spatie Laravel Permission
- Spatie Laravel Activitylog
- Laravel Pint

## Installation

### Local Installation

```bash
git clone https://github.com/mchtylmz/laravel-launchkit.git
cd laravel-launchkit
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan storage:link
php artisan migrate:fresh --seed
npm run build
```

Run the project locally:

```bash
php artisan serve
```

For frontend development:

```bash
npm run dev
```

### Docker Installation

Build and start the services:

```bash
docker compose up -d --build
```

Install PHP dependencies:

```bash
docker compose exec app composer install
```

Install and build frontend assets:

```bash
docker compose run --rm node npm install
docker compose run --rm node npm run build
```

Prepare the application:

```bash
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan storage:link
docker compose exec app php artisan migrate:fresh --seed
```

Open the application:

```text
http://localhost:8000
```

## Environment Variables

The project works with SQLite by default.

```env
APP_NAME="Laravel LaunchKit"
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
```

For Docker, use the MySQL service values from `docker-compose.yml`:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_launchkit
DB_USERNAME=launchkit
DB_PASSWORD=secret
REDIS_HOST=redis
MAIL_HOST=mailpit
MAIL_PORT=1025
```

Make sure the SQLite database file exists:

```bash
touch database/database.sqlite
```

## Migration and Seed Commands

```bash
php artisan migrate:fresh --seed
```

## Demo User Credentials

| Role | Email | Password |
| --- | --- | --- |
| Super Admin | superadmin@example.com | password |
| Admin | admin@example.com | password |
| User | user@example.com | password |

## Usage Examples

Start the Laravel development server:

```bash
php artisan serve
```

Start the frontend development server:

```bash
npm run dev
```

Build frontend assets for production:

```bash
npm run build
```

Format the codebase:

```bash
./vendor/bin/pint
```

## Screenshots

These are generated placeholder screenshots. Replace with real screenshots before publishing a production release.

![Landing](docs/screenshots/landing.png)
![Dashboard](docs/screenshots/dashboard.png)
![Profile](docs/screenshots/profile.png)
![Settings](docs/screenshots/settings.png)
![Users](docs/screenshots/users.png)
![Dark Mode](docs/screenshots/dark-mode.png)

## Roadmap

- Add email verification
- Add password reset emails
- Add richer notification storage
- Add file previews
- Add browser test coverage
- Improve Docker production notes

## Contributing

Contributions are welcome. Please keep changes small, readable and aligned with the goal of a simple Laravel starter kit.

## License

This project is open source and available under the MIT License.

## Author

Developed by Mucahit Yilmaz.

LinkedIn: https://www.linkedin.com/in/mmucahityilmazz/
