<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyTicket - Event Ticketing Platform</title>
    <link href="app/assets/compiled/css/app.css" rel="stylesheet">
    <link href="app/assets/extensions/bootstrap-icons/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold" href="index.php">
                    <i class="bi bi-ticket-perforated-fill text-primary"></i> MyTicket
                </a>
                <div class="d-flex gap-2">
                    <a href="index.php?page=auth&action=login" class="btn btn-outline-primary">Login</a>
                    <a href="index.php?page=auth&action=register" class="btn btn-primary">Register</a>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="py-5 bg-light">
            <div class="container py-5">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="display-4 fw-bold mb-3">Discover Amazing Events</h1>
                        <p class="lead text-muted mb-4">Book tickets for concerts, conferences, workshops, and more. Your one-stop platform for event ticketing.</p>
                        <div class="d-flex gap-3">
                            <a href="index.php?page=auth&action=register" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus"></i> Get Started
                            </a>
                            <a href="index.php?page=auth&action=login" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-4 mt-lg-0">
                        <div class="text-center">
                            <i class="bi bi-calendar-event" style="font-size: 12rem; color: #6c5ce7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold">Why Choose MyTicket?</h2>
                    <p class="text-muted">Simple, fast, and secure ticket booking</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-lightning-charge text-primary" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="card-title fw-bold">Fast Booking</h5>
                                <p class="card-text text-muted">Book your tickets in seconds with our streamlined checkout process.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="card-title fw-bold">Secure Payments</h5>
                                <p class="card-text text-muted">Your transactions are protected with industry-standard security.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-qr-code text-warning" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="card-title fw-bold">Digital Tickets</h5>
                                <p class="card-text text-muted">Get QR code tickets directly to your device for easy check-in.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-5 bg-primary text-white">
            <div class="container text-center py-4">
                <h2 class="fw-bold mb-3">Ready to Book Your Next Event?</h2>
                <p class="mb-4 opacity-75">Join thousands of users who trust MyTicket for their event tickets.</p>
                <a href="index.php?page=auth&action=register" class="btn btn-light btn-lg fw-bold">
                    Create Free Account
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-4 bg-white border-top">
            <div class="container text-center text-muted">
                <p class="mb-0">&copy; 2024 MyTicket. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>