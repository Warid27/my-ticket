# MyTicket - Event Ticket Ordering System

A web-based ticket ordering system built with PHP Native and MySQL.

## Features

- **Admin Features**
  - User management (CRUD) with PDF/Excel export
  - Venue management (CRUD) with PDF/Excel export
  - Event management (CRUD) with PDF/Excel export
  - Ticket management (CRUD) with PDF/Excel export
  - Voucher management (CRUD) with PDF/Excel export
  - Order management with PDF/Excel export
  - Check-in system
  - Dashboard with statistics and charts

- **Customer Features**
  - User registration and login
  - Browse events
  - Purchase tickets
  - View order history
  - QR code tickets

## Tech Stack

- **Backend**: PHP Native
- **Database**: MySQL
- **Architecture**: MVC + BaseModel
- **Frontend**: Pure HTML (no CSS, no Bootstrap)
- **External Libraries**: CDN-based (QR codes, charts, export)

## Installation

1. Clone the repository
2. Create database `myticket` in MySQL
3. Run migration: `php db/create_db.php` then `php db/migration.php`
4. Configure web server to point to project root
5. Access at `http://localhost/myticket-simple/`

## Default Login

- **Admin**: admin@myticket.com / admin123
- **Petugas** (Staff): petugas@myticket.com / petugas123
- **Customer**: Register through the system

## Project Structure

```
myticket-simple/
├── db/
│   ├── db.php              # PDO connection
│   ├── migration.php       # Database migration & seeder
│   └── create_db.php       # Database creation
├── app/
│   ├── core/
│   │   └── BaseModel.php   # Shared CRUD methods
│   ├── models/             # Data models
│   ├── controllers/        # Controllers
│   └── views/              # Views
├── uploads/                # Event images
└── index.php               # Front controller
```

## Notes

- This is a boilerplate implementation following SDD specifications
- No CSS or styling included - pure HTML focus
- Uses transactions for order processing
- QR codes generated client-side using CDN library

## CDN Libraries Used

### Export Functionality
- **jsPDF** (v2.5.1): PDF generation library
  - CDN: `https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js`
- **jsPDF-AutoTable** (v3.5.31): Plugin for table generation in PDF
  - CDN: `https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js`
- **SheetJS** (v0.18.5): Excel file generation library
  - CDN: `https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js`

### QR Code Generation
- **QRCode.js** (v1.0.0): Client-side QR code generator
  - CDN: `https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js`
  - Used in customer ticket display

## Export Features

- **PDF Export**: Uses jsPDF with autoTable plugin
- **Excel Export**: Uses SheetJS library
- Available on all admin list pages (Users, Venues, Events, Tickets, Vouchers, Orders)
- Simple one-click export with automatic filename generation

## Admin Access

- Admin users can manage all user accounts through the admin dashboard
- Admin cannot delete their own account (security feature)
- Password updates are optional when editing users
