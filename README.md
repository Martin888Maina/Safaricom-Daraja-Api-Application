# M-Pesa Daraja API Portfolio Application

> A standalone portfolio project demonstrating real-world integration with Safaricom's M-Pesa Daraja API — built with Laravel, PHP, MySQL, and pure CSS.

![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-009900?style=flat-square)

---

## What This App Does

A visitor (potential employer or recruiter) opens the web app and can:

| Feature | Description |
|---|---|
| **STK Push** | Trigger a payment prompt on a customer's phone via M-Pesa Express |
| **STK Query** | Check the status of a previous STK Push by Checkout Request ID |
| **C2B Simulate** | Simulate a customer paying a business shortcode (Paybill / Buy Goods) |
| **B2C Payment** | Simulate a business sending money to a customer's M-Pesa wallet |
| **Transaction History** | View a paginated log of all API calls with full request & response JSON |

All flows run against the **Daraja Sandbox** environment — no real money is transferred.

---

## Tech Stack

- **Backend** — Laravel 13, PHP 8.3
- **Frontend** — Laravel Blade, Pure CSS (no frameworks)
- **Database** — MySQL (production) / SQLite (local dev)
- **API** — Safaricom Daraja REST API (Sandbox)
- **Deployment** — DigitalOcean Droplet, Nginx, PHP-FPM, Certbot SSL

---

## Daraja APIs Implemented

| # | API | Endpoint |
|---|---|---|
| 1 | OAuth Token | `GET /oauth/v1/generate` |
| 2 | STK Push | `POST /mpesa/stkpush/v1/processrequest` |
| 3 | STK Query | `POST /mpesa/stkpushquery/v1/query` |
| 4 | C2B Simulate | `POST /mpesa/c2b/v1/simulate` |
| 5 | B2C Payment | `POST /mpesa/b2c/v1/paymentrequest` |
| 6 | Callback Receiver | `POST /mpesa/callback` (our route) |

---

## Local Setup

### Prerequisites

- PHP 8.2+
- Composer 2.x
- MySQL or SQLite
- Daraja sandbox credentials ([register free at developer.safaricom.co.ke](https://developer.safaricom.co.ke))

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/Martin888Maina/Safaricom-Daraja-Api-Application.git
cd Safaricom-Daraja-Api-Application

# 2. Install dependencies
composer install

# 3. Copy environment template
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Add your Daraja sandbox credentials to .env
#    MPESA_CONSUMER_KEY, MPESA_CONSUMER_SECRET, MPESA_PASSKEY, etc.
nano .env

# 6. Run migrations
php artisan migrate

# 7. Start the development server
php artisan serve
```

Visit `http://localhost:8000` — the dashboard loads with all four API cards.

### Sandbox Credentials

| Field | Value |
|---|---|
| Shortcode | `174379` |
| Test Phone | `254708374149` |
| Environment | Sandbox |
| Passkey | Available on the Daraja portal Simulator tab |

> **Note on callbacks:** Daraja cannot POST to `localhost`. Use [ngrok](https://ngrok.com) to expose your local server and set the HTTPS URL as `MPESA_CALLBACK_URL` in `.env`.

---

## Project Structure

```
app/
├── Http/Controllers/
│   ├── MpesaController.php      # 10 actions — dashboard, forms, POST handlers, history
│   └── CallbackController.php   # Receives async Daraja callbacks
├── Models/
│   └── MpesaTransaction.php     # Eloquent model for transaction log
└── Services/
    └── MpesaService.php         # Core Daraja API client (OAuth, STK, C2B, B2C)

config/
└── mpesa.php                    # M-Pesa config — reads from .env

database/migrations/
└── ..._create_mpesa_transactions_table.php

resources/views/
├── layouts/app.blade.php        # Master layout with nav and CSS design system
├── partials/response-card.blade.php
└── mpesa/
    ├── index.blade.php          # Dashboard
    ├── stk-push.blade.php
    ├── stk-query.blade.php
    ├── c2b-simulate.blade.php
    ├── b2c-payment.blade.php
    └── history.blade.php
```

---

## Security

- All Daraja credentials stored in `.env` only — never committed to git
- CSRF protection on all user-facing forms
- `/mpesa/callback` exempt from CSRF (Daraja POSTs without a token)
- Server-side validation on every form input before calling Daraja
- `APP_DEBUG=false` and `APP_ENV=production` enforced on the server

---

## Live Demo

> Link will be added after DigitalOcean deployment.

---

## License

MIT License — see [LICENSE.txt](LICENSE.txt)

Copyright (c) 2026 Martin Kamau Maina
