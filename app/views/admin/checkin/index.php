<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Check-in</h3>
                <p class="text-subtitle text-muted">Scan ticket codes for event check-in</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard&action=admin">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Check-in</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Ticket Check-in</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=attendee&action=checkin">
                    <div class="form-group">
                        <label for="ticket_code">Ticket Code</label>
                        <input type="text" class="form-control form-control-lg" id="ticket_code" name="ticket_code" required autofocus placeholder="Enter ticket code">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg mt-3">
                        <i class="bi bi-qr-code-scan"></i> Check-in
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>
