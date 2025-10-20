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

## 📁 Project Structure

```
├── app/
│   ├── config/          # Configuration files
│   ├── controllers/     # Request handlers
│   ├── core/           # Core framework classes
│   ├── database/       # Migrations and seeders
│   ├── exceptions/     # Exception handlers
│   ├── helpers/        # Utility classes
│   ├── middleware/     # Request middleware
│   ├── models/         # Data models
│   ├── routes/         # Route definitions
│   ├── services/       # Business logic
│   └── tests/          # Test files
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

### 3. Database Setup
```bash
# Option 1: Import the complete database schema
mysql -u root -p < app/database/Database.sql

# Option 2: Use migration system (recommended)
php migrate.php fresh  # Creates database, runs migrations and seeders
```

#### Migration Commands
```bash
# Run migrations only
php migrate.php migrate

# Run seeders only
php migrate.php seed

# Rollback all migrations
php migrate.php rollback

# Fresh migration (rollback + migrate + seed)
php migrate.php fresh
```

### 4. Start Development Server
```bash
# PHP Built-in Server
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