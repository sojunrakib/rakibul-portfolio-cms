# Rakibul Hasan Portfolio CMS

Custom PHP 8.2 MVC portfolio and admin panel for Rakibul Hasan.

## Local Setup

1. Start Apache/PHP and MySQL from XAMPP.
2. Copy `.env.example` to `.env` and adjust database/mail values if needed.
3. Create a MySQL database named `rakibul_portfolio`.
4. Import `database/migrations.sql`, then `database/seeders.sql`.
5. Generate Composer autoload files if needed:

```bash
composer dump-autoload
```

6. Run the PHP built-in server:

```bash
php -S localhost:8000 -t public public/router.php
```

Public site: `http://localhost:8000`

Admin panel: `http://localhost:8000/admin`

Default admin login:

- Email: `admin@example.com`
- Password: `ChangeMe123!`

Change the admin email/password immediately after first login.

## Deployment

For GitHub + Render deployment steps, see [DEPLOYMENT.md](DEPLOYMENT.md).

## Notes

- Placeholder social/contact values such as `[ADD EMAIL ADDRESS]`, `[ADD LINKEDIN URL]`, and `[ADD GITHUB URL]` are seeded intentionally and editable in the admin panel.
- Resume upload is managed from Website Settings by editing the `resume_pdf` row. Set the type to `file` and upload the PDF in the value field.
- Tailwind CLI configuration is included. A production-ready CSS file is already committed at `public/assets/css/app.css` so the project renders even before Node tooling is installed.
- The generated hero image used by the public site lives at `public/assets/img/rakibul-hero.png`.
