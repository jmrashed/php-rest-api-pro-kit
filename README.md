# PHP REST API Pro Kit

A production-ready Raw PHP REST API Starter Kit with JWT authentication, user management, file uploads, caching, rate limiting, and Docker support.

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Docker](https://img.shields.io/badge/Docker-Ready-blue.svg)](docker-compose.yml)

## 🚀 Features

- ✅ **JWT Authentication** - Secure token-based authentication
- ✅ **User Management** - Complete CRUD operations
- ✅ **File Upload/Download** - Secure file handling
- ✅ **Rate Limiting** - API request throttling
- ✅ **Input Validation** - Request data validation
- ✅ **Caching System** - File-based caching
- ✅ **Error Handling** - Centralized error management
- ✅ **Logging** - Request/error logging
- ✅ **Health Checks** - System monitoring
- ✅ **Docker Support** - Containerized deployment
- ✅ **PHPUnit Testing** - Comprehensive test suite
- ✅ **API Documentation** - Complete endpoint docs
- ✅ **Debug Bar** - Development debugging toolbar with performance monitoring
- ✅ **CLI Support** - Command-line interface for development tasks

## 📁 Project Structure

```
├── app/
│   ├── cli/            # CLI commands and console
│   ├── config/          # Configuration files
│   ├── controllers/     # Request handlers
│   ├── core/           # Core framework classes
│   ├── database/       # Migrations and seeders
│   ├── debugbar/       # Debug bar system
│   ├── exceptions/     # Exception handlers
│   ├── helpers/        # Utility classes
│   ├── middleware/     # Request middleware
│   ├── models/         # Data models
│   ├── routes/         # Route definitions
│   ├── services/       # Business logic
│   └── tests/          # Test files
├── console             # CLI entry point
├── bootstrap/          # Application bootstrap
├── docs/              # API documentation
├── public/            # Web server document root
├── storage/           # Logs, cache, uploads
└── vendor/            # Composer dependencies
```

## ⚡ Quick Start

### 1. Clone Repository
```bash
git clone https://github.com/jmrashed/php-rest-api-pro-kit.git
cd php-rest-api-pro-kit
composer install
```

### 2. Environment Setup
```bash
cp .env.example .env
# Edit .env with your database credentials
```

**Debug Bar Configuration (Optional)**
```bash
# Enable debug bar for development
DEBUGBAR_ENABLED=true
DEBUGBAR_ALLOWED_IPS=127.0.0.1,::1
```

### 3. Database Setup
```bash
# Option 1: Import the complete database schema
mysql -u root -p < app/database/Database.sql

# Option 2: Use migration system (recommended)
php migrate.php fresh  # Creates database, runs migrations and seeders
```

#### Migration Commands
```bash
# Using CLI (recommended)
php console migrate
php console migrate seed
php console migrate rollback
php console migrate fresh

# Or legacy commands
php migrate.php migrate
php migrate.php seed
php migrate.php rollback
php migrate.php fresh
```

### 4. Start Development Server
```bash
# Using CLI command (recommended)
php console serve

# Or PHP Built-in Server
php -S localhost:8000 -t public

# Or with Docker
docker-compose up -d
```

### 5. Test the API
```bash
curl http://localhost:8000/api/health
```

### 6. Sample Login Credentials
After running migrations and seeders, use these credentials to test the API:

```bash
# Admin User
Email: admin@hrms.com
Password: admin123

# HR Manager
Email: hr@hrms.com
Password: admin123

# Employees
Email: john@hrms.com, jane@hrms.com, mike@hrms.com
Password: admin123
```

## 📚 API Endpoints

### Authentication
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout user

### Users (Protected)
- `GET /api/users` - Get all users (paginated)
- `GET /api/users/{id}` - Get user by ID
- `POST /api/users` - Create user
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user

### Files (Protected)
- `POST /api/files/upload` - Upload file
- `DELETE /api/files/{id}` - Delete file

### System
- `GET /api/health` - Health check
- `GET /api/health/info` - System info

### Example Usage
```bash
# Register
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123"}'

# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"password123"}'

# Get users (with token)
curl -X GET http://localhost:8000/api/users \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

## 🗄️ Database Schema

The HRMS includes the following tables:

- **users** - Employee accounts with roles (admin, hr, employee)
- **departments** - Company departments
- **employee_profiles** - Extended employee information
- **attendance** - Daily attendance tracking
- **leave_types** - Leave categories (Annual, Sick, etc.)
- **leave_requests** - Leave applications with approval workflow
- **payroll** - Monthly salary processing
- **performance_reviews** - Employee performance evaluations
- **tokens** - JWT authentication tokens

## 🏗️ Architecture

### Core Components
- **Application**: Main app bootstrap and container
- **Router**: URL routing and middleware pipeline
- **Request/Response**: HTTP abstraction layer
- **Database**: PDO wrapper with query builder
- **Authentication**: JWT-based auth system
- **Cache**: File-based caching system
- **Validation**: Input validation and sanitization

### Security Features
- Password hashing (bcrypt)
- JWT token authentication
- Rate limiting (60 requests/hour per IP)
- Input sanitization and validation
- CORS middleware
- SQL injection protection (prepared statements)

## 🔧 Debug Bar

The built-in debug bar provides real-time development insights with minimal performance impact.

### Features
- **Performance Monitoring** - Execution time and memory usage tracking
- **Database Queries** - All SQL queries with timing information
- **Debug Messages** - Categorized logging (info, warning, error)
- **Request Data** - HTTP method, URI, headers, and parameters
- **Custom Timers** - Measure specific code execution times

### Configuration

Add to your `.env` file:
```bash
# Enable debug bar (disabled by default)
DEBUGBAR_ENABLED=true

# Optional: Restrict access by IP (comma-separated)
DEBUGBAR_ALLOWED_IPS=127.0.0.1,::1,192.168.1.100
```

### Usage

#### Debug Messages
```php
// Log debug messages with different levels
debug('User login attempt', 'info');
debug('Invalid credentials', 'warning');
debug('Database connection failed', 'error');
```

#### Performance Timing
```php
// Measure execution time
timer_start('api_call');
// ... your code ...
timer_stop('api_call');
```

#### Automatic Features
- **Database Queries**: All PDO queries are automatically tracked
- **Memory Usage**: Current and peak memory consumption
- **Request Info**: HTTP method, URI, headers automatically captured

### Output Modes

**HTML Pages**: Debug toolbar appears at the bottom of the page
**JSON APIs**: Debug data included in `X-Debugbar-Data` response header (Base64 encoded JSON)

### Security
- Automatically disabled when `DEBUGBAR_ENABLED=false`
- IP whitelist support for production-like environments
- No sensitive data exposure (credentials are filtered)
- Zero performance impact when disabled

### Test Debug Bar
Visit `http://localhost:8000/welcome` to see the debug bar in action.

## 💻 CLI Support

The framework includes a powerful command-line interface for development tasks.

### Available Commands

```bash
# Start development server
php console serve [host] [port]

# Database migrations
php console migrate [fresh|rollback|seed]

# Run tests
php console test [specific-test-file]

# Cache management
php console cache clear

# Generate files
php console make controller ControllerName
php console make model ModelName

# Show help
php console help
```

### Usage Examples

```bash
# Start server on custom host/port
php console serve localhost 8080

# Fresh migration with seeders
php console migrate fresh

# Generate a new controller
php console make controller ProductController

# Generate a new model
php console make model Product

# Run specific test
php console test app/tests/Unit/UserTest.php

# Clear application cache
php console cache clear
```

## 🧪 Testing

```bash
# Run all tests
vendor/bin/phpunit

# Run specific test
vendor/bin/phpunit app/tests/Unit/UserTest.php
```

## 🐳 Docker Deployment

```bash
# Build and run
docker-compose up -d

# View logs
docker-compose logs -f app

# Stop services
docker-compose down
```

## 🛠️ Requirements

- PHP 8.0+
- MySQL 5.7+
- Composer
- Docker (optional)

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.


## Author

**Md Rasheduzzaman**  
Full-Stack Software Engineer & Technical Project Manager  

Building scalable, secure & AI-powered SaaS platforms across ERP, HRMS, CRM, LMS, and E-commerce domains.  
Over 10 years of experience leading full-stack teams, cloud infrastructure, and enterprise-grade software delivery.

**🌐 Portfolio:** [jmrashed.github.io](https://jmrashed.github.io/)  
**✉️ Email:** [jmrashed@gmail.com](mailto:jmrashed@gmail.com)  
**💼 LinkedIn:** [linkedin.com/in/jmrashed](https://www.linkedin.com/in/jmrashed/)  
**📝 Blog:** [medium.com/@jmrashed](https://medium.com/@jmrashed)  
**💻 GitHub:** [github.com/jmrashed](https://github.com/jmrashed)

---

> *“Need a Reliable Software Partner? I build scalable, secure & modern solutions for startups and enterprises.”*