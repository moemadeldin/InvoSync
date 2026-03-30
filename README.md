# InvoSync

A multi-tenant invoicing and billing management system built with Laravel 12.

## Features

- **Customer Management** - Create, edit, search, and filter customers with statement history
- **Invoice Management** - Full CRUD with line items, PDF generation, and print view
- **Payment Tracking** - Record payments via multiple methods (Cash, Bank Transfer, Cheque, Credit Card)
- **Sales Returns** - Process returns on paid invoices with approval workflow
- **Reports** - Daily/monthly sales, top customers, and profit analysis
- **Overdue Tracking** - Monitor overdue invoices with days-overdue calculation
- **Customer Statements** - View complete transaction history per customer
- **External API** - Sync invoices and provision users from external systems

## Tech Stack

- **Backend:** PHP 8.2+, Laravel 12, MySQL
- **Frontend:** Blade, Tailwind CSS 4, Alpine.js
- **Build:** Vite
- **API:** Laravel Sanctum
- **PDF:** DomPDF

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL 8.0+

## Setup

```bash
# Clone the repository
git clone https://github.com/moemadeldin/InvoSync.git
cd InvoSync

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Update .env with your database credentials
# DB_CONNECTION=mysql
# DB_DATABASE=invosync
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Build assets
npm run build

# Start the application
composer dev
```

Or use the automated setup:
```bash
composer run setup
```

## Development

Start all services (server, queue worker, Vite dev):
```bash
composer dev
```

Run tests:
```bash
composer test
```

## Database Schema

| Table | Description |
|-------|-------------|
| `users` | User accounts (multi-tenant owners) |
| `customers` | Customer records per user |
| `invoices` | Invoice headers with status tracking |
| `invoice_items` | Invoice line items |
| `payments` | Payment records linked to invoices |
| `sales_returns` | Sales return headers |
| `sales_return_items` | Sales return line items |

All tables use UUIDs and soft deletes.

## API Endpoints

External API endpoints (requires `X-Sync-Token` header):

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/provision-teacher` | Register a new user |
| POST | `/api/v1/external-invoice` | Sync external invoice |

## License

MIT
