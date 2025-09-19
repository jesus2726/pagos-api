# Payment API - Payment Management System

A REST API developed in Laravel for managing payments to be disbursed through platforms, including clients, beneficiaries, payment orders and transaction auditing.

## 📋 System Requirements

### Minimum Requirements
- **PHP**: 8.2 or higher
- **Composer**: Latest stable version

## 🚀 Step-by-Step Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd pagos-api
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Configure Environment Variables

Create the `.env` file based on Laravel configuration:

```bash
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```
### 5. Run Migrations

```bash
php artisan migrate
```

## 📚 API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Clients
- `POST /api/clients` - Create client
- `GET /api/clients` - List clients
- `GET /api/clients/{uuid}` - Get client
- `PUT /api/clients/{uuid}` - Update client
- `DELETE /api/clients/{uuid}` - Delete client

### Beneficiaries
- `POST /api/beneficiaries` - Create beneficiary
- `GET /api/beneficiaries/client/{uuid}` - List client beneficiaries
- `GET /api/beneficiaries/{uuid}` - Get beneficiary
- `PUT /api/beneficiaries/{uuid}` - Update beneficiary
- `DELETE /api/beneficiaries/{uuid}` - Delete beneficiary

### Payment Orders
- `POST /api/payment-orders` - Create payment order
- `GET /api/payment-orders` - List payment orders
- `GET /api/payment-orders/{uuid}` - Get payment order

### Audit
- `GET /api/audit/clients/{uuid}/history` - Client audit history
- `GET /api/audit/transactions/{uuid}/{transactionType}` - Transaction audit

### System Information
- `GET /api/info` - System information

## 📖 API Documentation

```
http://localhost:8000/docs/api
```

## 🗂️ Project Structure

```
pagos-api/
├── app/
│   ├── Exceptions/          # Custom exceptions
│   ├── Helpers/            # Helper functions
│   ├── Http/
│   │   ├── Controllers/    # API controllers
│   │   ├── Requests/       # Input validation
│   │   └── Resources/      # Response transformers
│   ├── Models/             # Eloquent models
│   ├── Repositories/       # Data repositories
│   └── Services/           # Business logic
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Test data
├── routes/
│   └── api.php            # API routes
└── tests/                 # Automated tests
```
