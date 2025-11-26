
## 🚀 Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/Mayfair-Technology-Partners/perspective-folio-02.git
cd perspective-folio-02
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Environment Setup

Copy the example environment file:
```bash
cp .env.example .env
```

Generate the application key:
```bash
php artisan key:generate
```

### 4. Database Setup
Create a new MySQL database (e.g., folio).
```bash
mysql -u your_db_user -p your_db_name < dump-folio.sql
```
Update your .env file with the correct database credentials:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```
### 5. Serve the Application
```bash
php artisan serve
```
Open your browser at http://127.0.0.1:8000.

---

## 🛠 Common Issues

#### Permissions
Ensure storage and bootstrap/cache directories are writable:
```bash
chmod -R 775 storage bootstrap/cache
```
#### Missing PHP Extensions
Required PHP extensions: openssl, pdo, mbstring, tokenizer, xml, ctype, json

#### Database Connection Errors
Verify your .env file database settings and that your MySQL service is running.

