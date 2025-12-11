# ++i Starter Kit

This is a **+ starter kit** designed to jumpstart development for our company's invoice application projects. It provides a robust foundation built on the latest technologies, pre-configured and ready to go.

## 🚀 What is this?

This project is a modernized Laravel boilerplate that comes pre-packaged with essential tools for building admin-heavy applications. It saves setup time by including the administration panel, frontend styling framework, and a complete Dockerized development environment out of the box.

## ✨ What's Included & Changed?
Unlike a fresh Laravel installation, this starter kit includes a suite of enterprise-ready features:

### 👤 User Management & Authentication
- **Custom Registration Flow:** Tailored registration process with approval workflows (`Register.php`).
- **Role-Based Access Control (RBAC):** Built-in `RoleEnum` and `PermissionEnum` system (not dependent on external packages).
- **User CLI Commands:** Create Super Admins instantly via `php artisan user:create`.
- **Profile Management:** Custom `EditProfile` page allowing users to update their details.
- **Security Workflows:** Full support for Email Verification, Password Reset, and Email Change with custom mail templates (`InviteUserMail`, `ResetPasswordMail`, etc.).

### 🛡️ Security & Middleware
- **Maintenance Mode:** `CheckForDashboardMaintenanceMiddleware` allows Super Admins to access the dashboard while blocking regular users during maintenance.
- **Activity Tracking:** `UpdateUserLastSeenAtMiddleware` automatically tracks user activity timestamps.
- **Super Admin Privileges:** Bypasses for critical operations and maintenance screens.

### 🎨 UI & Configuration
- **Dynamic Settings:** `Setting` model integration for controlling dashboard colors, navigation, and notifications without code changes.
- **Custom Theme:** Pre-configured 'Poppins' font and custom CSS assets.
- **Global Notifications:** System-wide alert banners configurable via settings.

### 🛠 Developer Experience
- **Filament v4 Pre-installed:** Ready-to-use admin panel structure with auto-discovery.
- **Dockerized Environment:** `compose.yaml` configured with MySQL and Redis via Laravel Sail.
- **Helper Scripts:** Simplified `composer` commands for setup and deployment.

## 📦 Installation

### 🐳 Docker (Laravel Sail)

If you prefer using Docker for everything (no local PHP/Node required), you can use Laravel Sail.

1. **Start Containers**
   ```bash
   ./vendor/bin/sail up -d
   ```

2. **Run Setup (via Sail)**
   ```bash
   ./vendor/bin/sail composer run setup
   ```

## 🧪 Testing

### Running Tests

Run all tests:
```bash
./vendor/bin/sail test
```

Run specific test file:
```bash
./vendor/bin/sail test tests/Unit/Models/ClientTest.php
```

Run specific test method:
```bash
./vendor/bin/sail test --filter=test_balance_accessor_returns_correct_value
```

### Test Coverage

Run tests with coverage report:
```bash
./vendor/bin/sail test --coverage
```

Run tests with minimum coverage threshold:
```bash
./vendor/bin/sail test --coverage --min=80
```

View detailed coverage for specific directory:
```bash
./vendor/bin/sail test tests/Unit/Models/ --coverage
```

### Coverage Goals
- **Models**: 100% coverage
- **Services**: 100% coverage
- **Controllers**: 90%+ coverage
- **Overall**: 90%+ coverage
