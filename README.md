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
- ✅ **API Versioning** - Multiple API versions with backward compatibility
- ✅ **Queue System** - Background job processing with Redis/Database drivers

## 📁 Project Structure

```
├── app/
│   ├── api/            # API versioning system
│   ├── cli/            # CLI commands and console
│   ├── config/          # Configuration files
│   ├── controllers/     # Request handlers
│   │   ├── v1/         # Version 1 controllers
│   │   └── v2/         # Version 2 controllers
│   ├── core/           # Core framework classes
│   ├── database/       # Migrations and seeders
│   ├── debugbar/       # Debug bar system
│   ├── exceptions/     # Exception handlers
│   ├── helpers/        # Utility classes
│   ├── middleware/     # Request middleware
│   ├── models/         # Data models
│   ├── queue/          # Queue system
│   │   ├── Drivers/    # Queue drivers (Database, Redis)
│   │   ├── Jobs/       # Job classes
│   │   └── Processors/ # Queue workers
│   ├── routes/         # Route definitions
│   │   ├── api.php     # Legacy API routes (backward compatibility)
│   │   ├── api_v1.php  # Version 1 API routes
│   │   ├── api_v2.php  # Version 2 API routes
│   │   └── web.php     # Web routes
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

**Queue System Configuration (Optional)**
```bash
# Queue driver (database or redis)
QUEUE_DRIVER=database
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
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

### Versioned Endpoints

#### V1 API (Standard Format)
- `GET /api/v1/health` - Health check
- `POST /api/v1/auth/register` - Register new user
- `POST /api/v1/auth/login` - Login user
- `GET /api/v1/users` - Get all users (paginated)
- `GET /api/v1/users/{id}` - Get user by ID
- `POST /api/v1/users` - Create user
- `PUT /api/v1/users/{id}` - Update user
- `DELETE /api/v1/users/{id}` - Delete user

#### V2 API (Enhanced Format)
- `GET /api/v2/health` - Health check with metadata
- `POST /api/v2/auth/register` - Register with enhanced response
- `POST /api/v2/auth/login` - Login with structured response
- `GET /api/v2/users` - Get users with enhanced pagination
- `GET /api/v2/users/{id}` - Get user with metadata
- `POST /api/v2/users` - Create user with structured response
- `PUT /api/v2/users/{id}` - Update user with action tracking
- `DELETE /api/v2/users/{id}` - Delete user with confirmation

### Legacy Endpoints (Backward Compatibility)
**Note:** These endpoints default to V1 behavior for backward compatibility
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout user
- `GET /api/users` - Get all users (paginated)
- `GET /api/users/{id}` - Get user by ID
- `POST /api/users` - Create user
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user
- `POST /api/files/upload` - Upload file
- `DELETE /api/files/{id}` - Delete file
- `GET /api/health` - Health check
- `GET /api/health/info` - System info

### Example Usage
```bash
# V1 API (Explicit versioning - Recommended)
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123"}'

# V2 API (Enhanced responses)
curl -X POST http://localhost:8000/api/v2/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123"}'

# Legacy API (Backward compatibility)
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123"}'

# Version via header
curl -X GET http://localhost:8000/api/users \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "X-API-Version: v2"
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

## 🔄 API Versioning

The framework supports multiple API versions with backward compatibility and flexible version detection.

### Version Detection Methods

1. **URI Path** (Recommended)
```bash
GET /api/v1/users
GET /api/v2/users
```

2. **X-API-Version Header**
```bash
curl -H "X-API-Version: v2" http://localhost:8000/api/users
```

3. **Accept Header**
```bash
curl -H "Accept: application/vnd.api+json;version=2" http://localhost:8000/api/users
```

### Available Versions

#### Version 1 (v1)
- Standard JSON responses
- Basic error handling
- Simple data structure

```json
{
  "status": "success",
  "data": {...},
  "version": "v1"
}
```

#### Version 2 (v2)
- Enhanced response format
- Structured error codes
- Metadata inclusion
- Timestamp tracking

```json
{
  "success": true,
  "data": {...},
  "meta": {
    "version": "v2",
    "timestamp": "2024-10-21T10:30:00+00:00",
    "action": "created"
  }
}
```

### Version-Specific Features

**V1 Features:**
- Basic CRUD operations
- Simple response format
- Standard HTTP status codes

**V2 Features:**
- Enhanced error handling with error codes
- Metadata in responses
- Improved pagination info
- Structured error responses

### Creating New Versions

1. Create version directory: `app/controllers/v3/`
2. Create versioned controllers
3. Add route file: `app/routes/api_v3.php`
4. Update Application.php to load new routes

### Migration Strategy

**For New Projects:**
- Use explicit versioning from the start: `/api/v1/`
- Avoid legacy endpoints

**For Existing Projects:**
- Legacy endpoints (`/api/`) remain unchanged
- Gradually migrate clients to versioned endpoints
- Deprecate legacy endpoints in future versions

**Best Practices:**
- Always specify version in new integrations
- Use semantic versioning for major changes
- Maintain at least 2 versions simultaneously
- Provide migration guides for version changes

## 🔄 Queue System

The framework includes a powerful queue system for background job processing with support for multiple drivers.

### Features
- **Background Job Processing** - Asynchronous task execution
- **Multiple Drivers** - Database and Redis support
- **Email Queues** - Reliable email delivery
- **File Processing** - Image resize, file conversion, compression
- **Job Retry Logic** - Automatic retry with exponential backoff
- **Failed Job Handling** - Dead letter queue for failed jobs
- **CLI Workers** - Command-line queue processors

### Configuration

Add to your `.env` file:
```bash
# Queue driver (database or redis)
QUEUE_DRIVER=database

# Redis configuration (if using Redis driver)
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### Usage

#### Dispatching Jobs
```php
// Email jobs
queue_email('user@example.com', 'Welcome!', 'Welcome message');

// File processing jobs
queue_file_processing('/path/to/image.jpg', 'resize', ['width' => 800, 'height' => 600]);

// Custom jobs
use App\Queue\Jobs\SendEmailJob;
$job = new SendEmailJob('user@example.com', 'Subject', 'Message');
dispatch($job, 'emails');
```

#### Processing Jobs
```bash
# Start queue worker
php console queue work default

# Process specific queue
php console queue work emails

# Process limited number of jobs
php console queue work files 10

# Check queue status
php console queue status emails
```

### Built-in Job Types

**SendEmailJob** - Email delivery
- Automatic retry on failure
- SMTP configuration support
- HTML/text email support

**ProcessFileJob** - File processing
- Image resizing
- File compression
- Format conversion
- Batch processing support

### Creating Custom Jobs

```php
use App\Queue\Jobs\BaseJob;

class CustomJob extends BaseJob
{
    private $data;
    
    public function __construct($data)
    {
        $this->data = $data;
        $this->maxRetries = 3;
        $this->delay = 30; // seconds
    }
    
    public function handle(): bool
    {
        // Your job logic here
        return true;
    }
    
    public function failed(\Exception $exception): void
    {
        // Handle job failure
    }
}
```

### Queue Drivers

**Database Driver**
- Uses MySQL/PostgreSQL for job storage
- Automatic table creation
- Transaction support
- No external dependencies

**Redis Driver**
- High performance
- Atomic operations
- Delayed job support
- Requires Redis extension

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

# Queue management
php console queue work [queue] [max-jobs]
php console queue status [queue]
php console queue clear [queue]

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

# Start queue worker
php console queue work emails

# Check queue status
php console queue status default
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

### Docker Services

The Docker setup includes three services:

- **App Service** - PHP 8.1 Apache server on `http://localhost:8000`
- **Database Service** - MySQL 8.0 on port `3307`
- **phpMyAdmin Service** - Web database interface on `http://localhost:8081`

### Database Management

Access phpMyAdmin at `http://localhost:8081` to manage your database:
- **Server**: db
- **Username**: root
- **Password**: password
- **Database**: test_db

This provides a user-friendly web interface for database administration, table management, and query execution.

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