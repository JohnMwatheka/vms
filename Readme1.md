
# Vendor Management System – Take-home Project

A fully functional, production-ready Vendor Onboarding & Approval Pipeline built with Laravel 12.

**Live end-to-end flow works perfectly in under 2 minutes** — from creation to final approval.

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

## How to Run (5 minutes)

```bash
git clone https://github.com/yourusername/vms.git
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

## Recommended Demo Flow (90 seconds)

1. Click **Initiator** -Create vendor (use `vendor@test.com`)
2. Click **"Send to Vendor"** on dashboard
3. Switch to **Vendor** - Complete profile and upload a PDF cert or doc
4. Click **Submit for Review**
5. Rapid-fire approve: Checker to Procurement to Legal to Finance to **Directors**
6. Final approve instantly appears in **Approved Vendors** list
7. Show history timeline and document download

## Architecture Highlights

- Clean separation: Models → Enums → Service → Controllers → Views
- Full audit trail via `VendorHistory`
- Real document upload with secure access
- Email-based vendor portal (real-world pattern)
- No fake "vendor" role — matches production systems
- Role switcher makes demo flawless

## Assumptions & Smart Decisions

Vendor portal uses email matching instead of fake role 
Reject always returns to With Vendor
Manual "Send to Vendor" button  for better user experience
Used Laravel Enums  and accessors for type safety
Real file uploads not just names 
