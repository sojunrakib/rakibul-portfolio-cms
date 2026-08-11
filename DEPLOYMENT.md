# Render deployment guide

## 1. Push to GitHub

1. Create a new GitHub repository.
2. Push this project to the repository:

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin <your-github-repo-url>
git push -u origin main
```

## 2. Prepare the app for Render

This project expects:
- PHP 8.2+
- Composer dependencies
- A MySQL database
- Writable folders for uploads, sessions, and cache

### Required environment variables

Set these in Render:

```text
APP_ENV=production
APP_URL=https://your-app-name.onrender.com
APP_KEY=replace-with-a-long-random-string
APP_SECURE_COOKIES=true

DB_HOST=your-database-host
DB_PORT=3306
DB_NAME=your-database-name
DB_USER=your-database-user
DB_PASS=your-database-password

# Or use one full connection string if your database provider gives one:
# DATABASE_URL=mysql://user:password@host:3306/database

MAIL_FROM=no-reply@example.com
MAIL_TO=admin@example.com
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=ChangeMe123!
```

## 3. Create the database

Use a Render MySQL service or any external MySQL-compatible provider.

Import the SQL files:

```bash
mysql -u <user> -p <database_name> < database/migrations.sql
mysql -u <user> -p <database_name> < database/seeders.sql
```

## 4. Deploy on Render

1. Go to Render.
2. Click New > Web Service.
3. Connect the GitHub repository.
4. Choose `GitHub` and select the repository.
5. Choose branch `main`.
6. Use these settings:
   - Build Command: `composer install --no-dev --optimize-autoloader`
   - Start Command: `php -S 0.0.0.0:$PORT -t public public/router.php`
   - Health Check Path: `/`
7. Add the environment variables from section 2 in Render's service settings.
8. Deploy.

### Render environment variables

Set these values in Render:

```text
APP_ENV=production
APP_URL=https://your-app-name.onrender.com
APP_KEY=replace-with-a-long-random-string
APP_SECURE_COOKIES=true

DB_HOST=your-database-host
DB_PORT=3306
DB_NAME=your-database-name
DB_USER=your-database-user
DB_PASS=your-database-password

MAIL_FROM=no-reply@example.com
MAIL_TO=admin@example.com
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=ChangeMe123!
```

If your provider gives a full MySQL URL, set `DATABASE_URL` instead of splitting the values manually. The app also understands common aliases such as `MYSQL_HOST`, `MYSQL_DATABASE`, `MYSQL_USER`, and `MYSQL_PASSWORD`.

## 5. Important notes

- The app writes to folders under `storage/` and `public/uploads/`; ensure they are writable on the host.
- If your host does not support the built-in PHP server, you may need to point it to the public directory with Apache/Nginx instead of the Render start command.
- The default admin account should be changed after first login.
