
# Vendor Management System – Take-home Project

A fully functional, production-ready Vendor Onboarding & Approval Pipeline built with Laravel 12.

**Live end-to-end flow** — from creation to final approval.

## Features Implemented

* **8-stage pipeline (New → Approved)**

  * Status: Done
  * Implementation: Enum-based state machine

* **Initiator creates vendor**

  * Status: Done
  * Implementation: `/vendor/create`

* **Vendor fills details + uploads documents**

  * Status: Done
  * Implementation: Email-based portal (no fake "vendor" role)

* **Upload real documents (PDF, JPG, PNG)**

  * Status: Done
  * Implementation: Full file storage + secure access

* **5 Review Stages (Checker → Directors)**

  * Status: Done
  * Implementation: Dedicated review pages with counts

* **Approve / Reject with comment**

  * Status: Done
  * Implementation: Full audit trail

* **Final approval → Approved + timestamp**

  * Status: Done
  * Implementation: `approved_at` field

* **Approved Vendors master list + search**

  * Status: Done
  * Implementation: `/approved-vendors` with live search

* **Full history timeline**

  * Status: Done
  * Implementation: Stage + Action + Comment + User + Time

* **"Who Acts Next" indicator**

  * Status: Done
  * Implementation: Clear labels on every card

* **Role-based dashboard**

  * Status: Done
  * Implementation: Clean separation

* **One-click Role Switcher**

  * Status: Done
  * Implementation: Instant testing of any perspective


## Tech Stack

- **Laravel 12** + PHP 8.3
- **MySQL** 
- **Blade** + Tailwind-style UI
- **Spatie Laravel Permission** (for real roles)
- **Enums** for stage management
- **Accessors/Mutators** for clean enum handling
- **File Storage** (public disk)

## How to Run

```bash
git clone https://github.com/JohnMwatheka/vms.git
cd vms
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Visit: http://127.0.0.1:8000

## Demo Users (Seeded)

All passwords: `password`

* **Admin / Initiator**

  * Email: [admin@example.com](mailto:admin@example.com)
  * Role: initiator

* **Checker**

  * Email: [checker@example.com](mailto:checker@example.com)
  * Role: checker

* **Procurement**

  * Email: [procurement@example.com](mailto:procurement@example.com)
  * Role: procurement

* **Legal**

  * Email: [legal@example.com](mailto:legal@example.com)
  * Role: legal

* **Finance**

  * Email: [finance@example.com](mailto:finance@example.com)
  * Role: finance

* **Directors**

  * Email: [directors@example.com](mailto:directors@example.com)
  * Role: directors

* **Test Vendor User**

  * Email: [vendor@example.com](mailto:vendor@example.com)
  * Role: none (identified by email match)


## Instant Testing – Use the Role Switcher!

**Best feature:** Top of every page → **"Demo Role Switcher"**  
Click any role → instantly become that user → no login/logout needed!

Perfect for presentation — switch perspectives in 1 second.

## Demo flow using encoded demo data

1. Click **Initiator** -Create vendor (use `vendor@test.com`)
2. Click **"Send to Vendor"** on dashboard
3. Switch to **Vendor** - Complete profile and upload a PDF cert or doc
4. Click **Submit for Review**
5. Rapid-fire approve: Checker to Procurement to Legal to Finance to **Directors**
6. Final approve instantly appears in **Approved Vendors** list
7. Show history timeline and document download

## Architecture

- Clean separation: Models,Enums, Service, Controllers, Views
- Full audit trail via `VendorHistory`
- Real document upload with secure access
- Email-based vendor portal 
- No fake "vendor" role
- Role switcher makes demo flawless

## Assumptions & Smart Decisions

Vendor portal uses email matching instead of fake role 
Reject always returns to With Vendor
Manual "Send to Vendor" button  for better user experience
Used Laravel Enums  and accessors for type safety
Real file uploads not just names 





## LARAVEL Default Readme.md


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
