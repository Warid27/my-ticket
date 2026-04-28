# MyTicket - Event Management System

## General Information

MyTicket is a comprehensive event management and ticketing system built with PHP. The system provides a complete solution for managing events, venues, tickets, orders, and attendees with role-based access control for administrators and regular users.

**Key Features:**
- Multi-role authentication (Admin/User)
- Event and venue management
- Ticket creation and sales
- Order processing with payment integration
- Attendee management and check-in
- Voucher system
- QR code generation and scanning
- Export functionality (PDF/Excel)
- Real-time notifications

## Features

### Core Modules
- **Authentication System**: Secure login/logout with password reset functionality
- **Dashboard**: Role-based dashboards for admins and users
- **Event Management**: Create, edit, and manage events with venue assignments
- **Venue Management**: Add and manage event venues
- **Ticket Management**: Create different ticket types for events
- **Order Processing**: Complete order lifecycle with payment integration
- **Attendee Management**: Track and manage event attendees
- **Voucher System**: Create and manage discount vouchers
- **Notifications**: Real-time notification system

### User Roles
- **Administrator**: Full access to all modules and system configuration
- **User**: Limited access to events, tickets, and personal orders

### Technical Features
- **Payment Integration**: Xendit payment gateway integration
- **QR Code Support**: Generate tickets with QR codes and scanning functionality
- **Export Capabilities**: Export data to PDF and Excel formats
- **Email Services**: Email notifications via Resend API
- **Security**: Session management, password hashing, and secure authentication

## Assets Used

### Frontend Framework
- **Mazer**: Modern admin dashboard template for UI components and styling

### JavaScript Libraries
- **jsPDF**: PDF generation library for creating downloadable reports
- **XLSX**: Excel file generation and manipulation library
- **QRCode**: QR code generation library for ticket creation
- **QRScanner**: QR code scanning library for ticket validation

### External Services
- **Xendit**: Payment gateway for processing ticket payments
- **Resend**: Email service for sending notifications and confirmations

### Database
- **MySQL**: Relational database for storing application data

## System Architecture

The application follows an MVC (Model-View-Controller) architecture:

### Controllers
- Handle HTTP requests and route to appropriate actions
- Implement business logic and data validation
- Manage user authentication and authorization

### Models
- Represent database entities and handle data operations
- Implement CRUD operations for all system entities
- Manage relationships between different data entities

### Views
- Render HTML templates with dynamic data
- Include responsive UI components from Mazer framework
- Provide user interfaces for all system functionalities

### Services
- **EmailService**: Handle email communications
- **XenditService**: Manage payment processing

## Database Schema

The system uses the following main tables:
- `users` - User accounts and authentication
- `events` - Event information and details
- `venues` - Event venue data
- `tickets` - Ticket types and pricing
- `orders` - Order information and status
- `order_details` - Line items for orders
- `attendees` - Event attendee records
- `vouchers` - Discount voucher management
- `notifications` - System notifications

## Security Features

- Password hashing using PHP's built-in password functions
- Session-based authentication with secure cookie settings
- Role-based access control
- Input validation and sanitization
- SQL injection prevention through prepared statements
- CSRF protection considerations

## Configuration

The system uses environment variables and configuration constants for:
- Database connections
- API keys (Xendit, Resend)
- Application settings
- Security parameters

## Summary

MyTicket is a full-featured event management system that provides comprehensive ticketing solutions with modern web technologies. It offers robust user management, secure payment processing, and extensive reporting capabilities through its export functionality. The system is designed to be scalable and maintainable, following best practices in PHP development and web security.

The integration of modern JavaScript libraries for PDF/Excel export, QR code functionality, and the Mazer UI framework provides a professional user experience while maintaining clean, organized code structure through the MVC pattern.
