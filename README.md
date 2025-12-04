# LaraShop

A modern e-commerce application built with Laravel 12, featuring a complete shopping experience with session-based cart, guest checkout, and user authentication.

## 🌐 Live Demo

Visit the live application: **[https://larashop-master-xhow8t.laravel.cloud/](https://larashop-master-xhow8t.laravel.cloud/)**

## ✨ Features

### Shopping Experience
- 🛍️ Product browsing with detailed product pages
- 🛒 Session-based shopping cart
- 📦 Guest checkout (no account required)
- 👤 Authenticated user checkout with pre-filled forms
- 📋 Order history and order details
- 💳 Order tracking with status badges

### User Management
- 🔐 User registration and authentication
- 🔗 Automatic customer linking (guest → registered user)
- 📧 Session-based customer tracking

### Product Management
- 💰 Price handling (stored in cents, displayed in euros)
- 🖼️ Product images and descriptions
- 📊 Inventory tracking

### Order Management
- 📝 Complete order creation workflow
- 💾 Product snapshot preservation (price, name, description)
- 🚚 Shipping information storage
- 📈 Order status tracking (pending, processing, completed, cancelled)

## 🛠️ Tech Stack

- **Framework:** Laravel 12
- **PHP:** 8.4
- **Frontend:** Vite + Tailwind CSS
- **Database:** MySQL (production) / SQLite (testing)
- **Testing:** Pest v4 (142 tests, 291 assertions)
- **Code Style:** Laravel Pint
- **CI/CD:** GitHub Actions

## 📦 Local Setup

### Prerequisites

- PHP 8.4 or higher
- Composer
- Node.js 20 or higher
- SQLite
- Git

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd larashop
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your use SQLite for simplicity:**
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/database/database.sqlite
   ```

   Then create the database file:
   ```bash
   touch database/database.sqlite
   ```

6. **Run migrations and seed the database**
   ```bash
   php artisan migrate --seed
   ```

   This will create:
   - 3 products (Woman's t-shirt, Men's t-shirt, Unisex Cap)
   - Sample orders 

7. **Start the development server**
   ```bash
   composer run dev
   ```

9. **Visit the application**

   Open your browser and navigate to: `http://localhost:8000`

## 🧪 Testing

The project includes comprehensive test coverage:

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suites
```bash
# Unit tests only
php artisan test tests/Unit

# Feature tests only
php artisan test tests/Feature

# Specific test file
php artisan test tests/Feature/CartTest.php
```

### Code Style Check
```bash
vendor/bin/pint --test
```

### Fix Code Style
```bash
vendor/bin/pint
```

## 🚀 Deployment

### GitHub Actions CI/CD

The project includes automated testing on pull requests:

- ✅ Runs all tests
- ✅ Checks code style with Pint
- ✅ Builds frontend assets
- ✅ Uses PHP 8.4 with SQLite
- ✅ Prevents merging if tests fail

**Workflow file:** `.github/workflows/tests.yml`

### Production Deployment

The application is hosted on laravel.cloud and is deployed automatically every time a successful PR is merged.

```

## 📝 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Product listing (homepage) |
| GET | `/product/{id}` | Product details |
| POST | `/cart` | Add to cart |
| PATCH | `/cart/{id}` | Update cart quantity |
| DELETE | `/cart/{id}` | Remove from cart |
| GET | `/checkout` | Checkout page |
| POST | `/orders` | Create order |
| GET | `/orders` | Order history |
| GET | `/orders/{id}` | Order details |
| GET | `/register` | Registration page |
| POST | `/register` | Register user |
| GET | `/login` | Login page |
| POST | `/login` | Authenticate |
| DELETE | `/logout` | Logout |

## 🐛 Troubleshooting

### Vite manifest not found
```bash
npm run build
```

### Tests failing locally but passing in CI
Ensure you're running the same PHP version (8.4) and have run:
```bash
composer install
npm install
npm run build
php artisan migrate:fresh
```

### Permission errors
```bash
chmod -R 775 storage bootstrap/cache
```

---

**Live Demo:** [https://larashop-master-xhow8t.laravel.cloud/](https://larashop-master-xhow8t.laravel.cloud/)
