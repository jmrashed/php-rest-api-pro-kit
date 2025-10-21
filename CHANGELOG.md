# Changelog

All notable changes to this project will be documented in this file.

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