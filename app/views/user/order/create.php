<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buy Ticket</h3>
                <p class="text-subtitle text-muted">Purchase event tickets</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard&action=user">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Buy Ticket</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Complete Your Purchase</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0"><i class="bi bi-ticket-perforated me-2"></i>Event & Ticket Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="bg-light rounded-circle p-2 me-3">
                                                <i class="bi bi-calendar-event text-primary"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Event</small>
                                                <strong class="fs-6"><?= htmlspecialchars($event['name']) ?></strong>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="bg-light rounded-circle p-2 me-3">
                                                <i class="bi bi-tag text-primary"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Ticket Type</small>
                                                <strong class="fs-6"><?= htmlspecialchars($ticket['name']) ?></strong>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="bg-light rounded-circle p-2 me-3">
                                                <i class="bi bi-currency-dollar text-primary"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Price per Ticket</small>
                                                <strong class="fs-5 text-primary">Rp <?= number_format($ticket['price']) ?></strong>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start">
                                            <div class="bg-light rounded-circle p-2 me-3">
                                                <i class="bi bi-ticket text-primary"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Available Tickets</small>
                                                <strong class="fs-6"><?= $ticket['quota'] ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <h5 class="mb-3">Order Information</h5>
                                <form method="POST" action="index.php?page=order&action=store">
                                    <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                                    <div class="form-group mb-3">
                                        <label for="qty" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="qty" name="qty" min="1" max="<?= $ticket['quota'] ?>" required>
                                        <small class="text-muted">Maximum: <?= $ticket['quota'] ?> tickets</small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="voucher_code" class="form-label">Voucher Code <span class="text-muted">(Optional)</span></label>
                                        <input type="text" class="form-control" id="voucher_code" name="voucher_code" placeholder="Enter voucher code">
                                    </div>
                                    <div class="d-flex gap-2 mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-cart-check me-1"></i> Buy Now
                                        </button>
                                        <a href="index.php?page=dashboard&action=user" class="btn btn-secondary">
                                            <i class="bi bi-x-lg me-1"></i> Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function applyVoucher() {
    const voucherCode = document.getElementById('voucher_code').value;
    const messageDiv = document.getElementById('voucher_message');
    const discountDiv = document.getElementById('discount_info');
    const discountText = document.getElementById('discount_text');
    
    if (!voucherCode.trim()) {
        messageDiv.innerHTML = '<div class="alert alert-warning py-2">Please enter a voucher code</div>';
        discountDiv.style.display = 'none';
        return;
    }
    
    const formData = new FormData();
    formData.append('voucher_code', voucherCode);
    
    fetch('index.php?page=order&action=applyVoucher', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageDiv.innerHTML = '<div class="alert alert-success py-2">' + data.message + '</div>';
            if (data.type === 'percentage') {
                discountText.textContent = 'Discount: ' + data.discount + '% off';
            } else {
                discountText.textContent = 'Discount: Rp ' + parseInt(data.discount).toLocaleString('id-ID');
            }
            discountDiv.style.display = 'block';
        } else {
            messageDiv.innerHTML = '<div class="alert alert-danger py-2">' + data.message + '</div>';
            discountDiv.style.display = 'none';
        }
    })
    .catch(error => {
        messageDiv.innerHTML = '<div class="alert alert-danger py-2">Error applying voucher</div>';
        discountDiv.style.display = 'none';
    });
}
</script>
