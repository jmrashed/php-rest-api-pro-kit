# API Documentation

## Authentication Endpoints

### POST /api/auth/login
Login with email and password.

**Request:**
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "token": "jwt_token_here",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com"
    }
}
```

### POST /api/auth/register
Register a new user.

**Request:**
```json
{
    "name": "John Doe",
    "email": "user@example.com",
    "password": "password123"
}
```

### POST /api/auth/logout
Logout current user (requires authentication).

## User Endpoints

### GET /api/users
Get all users (with pagination).

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 10)

### GET /api/users/{id}
Get user by ID.

### POST /api/users
Create new user.

### PUT /api/users/{id}
Update user by ID.

### DELETE /api/users/{id}
Delete user by ID.

## File Endpoints

### POST /api/files/upload
Upload a file.

### DELETE /api/files/{id}
Delete a file.

## Health Endpoints

### GET /api/health
Check API health status.

### GET /api/health/info
Get system information.