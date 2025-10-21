# Changelog

All notable changes to this project will be documented in this file.

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