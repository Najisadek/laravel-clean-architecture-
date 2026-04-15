# Laravel Clean Architecture

A Laravel project demonstrating Clean Architecture principles while staying idiomatic to Laravel.

## Purpose

This project serves as a learning reference for implementing Clean Architecture in Laravel applications. It shows the balance between strict CA and Laravel's built-in features.

## Architecture Overview

```
app/
├── Domain/                 # Business logic - framework agnostic
│   ├── User/
│   │   ├── User.php       # Domain entity
│   │   ├── Contracts/     # Repository interfaces
│   │   └── Exceptions/    # Domain exceptions
│
├── Application/           # Use cases / Actions
│   └── User/
│       ├── Actions/       # Business operations
│       └── DTOs/          # Data transfer objects
│
├── Infrastructure/       # External concerns
│   └── Persistence/      # Eloquent repositories
│
└── Http/                 # Presentation layer
    ├── Controllers/
    ├── Requests/         # Form validation
    └── Resources/        # API transformers
```

## Key Decisions

| Layer | Approach |
|-------|----------|
| **Domain** | Domain entity wrapping Eloquent model |
| **Repository** | Interface in Domain, Implementation in Infrastructure |
| **Authentication** | Laravel's Auth + Sanctum |
| **Password Hashing** | Laravel's Hash facade |
| **Validation** | FormRequest classes |
| **Exceptions** | Handled in Controller (or use Exception Handler) |

## What Was Removed (vs. Strict CA)

- **Value Objects** - Email, Password, UserId → Use strings + validation
- **Custom Interfaces** - PasswordHasher, TokenGenerator → Use Laravel facades
- **DTOs from Request** - Can use FormRequest directly
- **Middleware** - Domain exceptions handled in controller

## Trade-offs

**Kept**: Repository interface (good for testing, swappable implementations)

**Simplified**: Domain entity uses Eloquent internally (Laravel-idiomatic, less mapping code)

## API Endpoints

```
POST   /api/register    - Register new user
POST   /api/login       - Login user
POST   /api/logout      - Logout user
GET    /api/user        - Get authenticated user
```

## Getting Started

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Testing

```bash
php artisan test
```

## Learning Points

This project demonstrates:
1. When to keep Clean Architecture strict (Domain layer)
2. When to be pragmatic with Laravel (Infrastructure, Auth)
3. How to wrap Eloquent models in domain entities
4. Repository pattern implementation with interface