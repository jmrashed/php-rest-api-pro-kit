# Changelog

All notable changes to this project will be documented in this file.

## [1.5.0] - 2025-11-20

### Added
- **phpMyAdmin Integration** - Web-based database management interface
  - Automatic phpMyAdmin service in Docker Compose
  - Pre-configured connection to project database
  - Access via http://localhost:8081
  - Secure authentication with MySQL credentials

### Improved
- **Docker Configuration** - Enhanced containerized deployment
  - Fixed environment variable loading in Config class
  - Improved docker-entrypoint.sh for reliable startup
  - Better database connection handling in containers
  - Optimized migration execution during container startup

### Fixed
- **Environment Configuration** - Config class now properly reads Docker environment variables
- **Docker Entrypoint** - Changed from 'fresh' to 'migrate' to avoid foreign key constraint issues
- **Database Connection** - Resolved connection issues in Docker containers

### Docker Services
- **App Service** - PHP 8.1 Apache container on port 8000
- **Database Service** - MySQL 8.0 container on port 3307
- **phpMyAdmin Service** - Web interface on port 8081

## [1.4.0] - 2025-10-21

### Added
- **Queue System** - Background job processing with multiple drivers
  - Database and Redis queue drivers
  - Email queue processing with retry logic
  - File processing jobs (resize, compress, convert)
  - Queue worker with CLI commands
  - Job retry and failure handling
  - Helper functions for easy job dispatching
  - Automatic table creation for database driver
  - Failed job tracking and management

### Features
- `QueueManager` - Central queue management system
- `JobInterface` - Standard job contract
- `BaseJob` - Abstract job class with retry logic
- `SendEmailJob` - Built-in email processing
- `ProcessFileJob` - Built-in file processing
- `QueueWorker` - Background job processor
- `DatabaseDriver` - MySQL/PostgreSQL queue storage
- `RedisDriver` - Redis-based queue storage

### CLI Commands
- `php console queue work [queue] [max-jobs]` - Start queue worker
- `php console queue status [queue]` - Check queue status
- `php console queue clear [queue]` - Clear queue

### Helper Functions
- `dispatch($job, $queue)` - Dispatch jobs to queue
- `queue_email($to, $subject, $message)` - Queue email jobs
- `queue_file_processing($path, $operation, $options)` - Queue file jobs
- `queue_status($queue)` - Get queue size

### Configuration
- `QUEUE_DRIVER` - Set queue driver (database/redis)
- `REDIS_HOST` - Redis server host
- `REDIS_PORT` - Redis server port

## [1.3.0] - 2025-10-21

### Added
- **API Versioning System** - Multiple API versions with backward compatibility
  - URI path versioning (/api/v1/, /api/v2/)
  - Header-based version detection (X-API-Version, Accept)
  - Version-specific controllers and responses
  - Automatic version middleware integration
  - Enhanced V2 response format with metadata
  - Structured error codes and messages
  - Backward compatibility support

### Features
- `ApiVersionMiddleware` - Automatic version detection
- `VersionedRouter` - Route handling for different versions
- V1 Controllers - Standard JSON responses
- V2 Controllers - Enhanced responses with metadata
- Multiple version detection methods
- Flexible response transformations

### API Versions
- **V1**: Standard responses, basic error handling
- **V2**: Enhanced metadata, structured errors, timestamps

### Version Detection
- URI path: `/api/v1/users`, `/api/v2/users`
- Header: `X-API-Version: v2`
- Accept: `application/vnd.api+json;version=2`

## [1.2.0] - 2025-10-21

### Added
- **CLI Support** - Command-line interface for development tasks
  - Console application with command registration system
  - Built-in commands: serve, migrate, test, cache, make
  - File generation: controllers and models
  - Development server management
  - Database migration management
  - Test runner integration
  - Cache management utilities
  - Colored console output for better UX
  - Cross-platform compatibility

### Commands
- `php console serve` - Start development server
- `php console migrate [action]` - Database migrations
- `php console test [file]` - Run PHPUnit tests
- `php console cache clear` - Clear application cache
- `php console make controller|model <name>` - Generate files
- `php console help` - Show available commands

## [1.1.0] - 2025-10-21

### Added
- **Debug Bar System** - Lightweight development debugging toolbar
  - Real-time performance monitoring (execution time, memory usage)
  - Database query tracking with timing information
  - Debug message logging with different severity levels
  - HTTP request information capture
  - Timer functionality for measuring code execution
  - Automatic PDO query interception and logging
  - Safe by default - only enabled via environment configuration
  - IP whitelist support for security
  - Minimal overhead when disabled

### Features
- `CollectorInterface` - Base interface for debug collectors
- `DebugBar` - Main singleton manager for debug functionality
- Built-in collectors:
  - `TimerCollector` - Measures execution times
  - `MemoryCollector` - Tracks memory usage
  - `MessageCollector` - Logs debug messages
  - `QueryCollector` - Records database queries
  - `RequestCollector` - Captures HTTP request data
- `DebugPDO` - PDO wrapper for automatic query tracking
- Helper functions: `debug()`, `timer_start()`, `timer_stop()`
- Responsive debug toolbar UI with tabbed interface
- Dual output support: HTML toolbar for web pages, JSON headers for APIs

### Configuration
- `DEBUGBAR_ENABLED` - Enable/disable debug bar
- `DEBUGBAR_ALLOWED_IPS` - IP whitelist for security

### Fixed
- PHP 8+ compatibility issues with PDO return types
- `getallheaders()` function compatibility for CLI environments

### Security
- Debug bar automatically disabled in production
- IP-based access control
- No sensitive data exposure in debug output