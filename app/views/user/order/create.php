<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6">
                <h3>Buy Ticket</h3>
                <p class="text-subtitle text-muted">Purchase event tickets</p>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">

                <div class="row">
                    <!-- LEFT -->
                    <div class="col-md-5">
                        <h5><?= htmlspecialchars($event['name']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($ticket['name']) ?></p>

                        <p>Price: <strong>Rp <?= number_format($ticket['price']) ?></strong></p>
                        <p>Available: <strong><?= $ticket['quota'] ?></strong></p>
                    </div>

                    <!-- RIGHT -->
                    <div class="col-md-7">

                        <form method="POST" action="index.php?page=order&action=store">
                            <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">

                            <!-- QTY -->
                            <div class="mb-3">
                                <label>Quantity</label>
                                <input type="number" class="form-control" id="qty" name="qty" min="1"
                                    max="<?= $ticket['quota'] ?>" required>
                            </div>

                            <!-- VOUCHER -->
                            <label>Voucher (optional)</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="voucher_code" name="voucher_code"
                                    placeholder="Enter voucher code">

                                <button type="button" class="btn btn-outline-primary" onclick="applyVoucher()">
                                    Apply
                                </button>
                            </div>

                            <div id="voucher_message"></div>

                            <div id="discount_info" class="alert alert-info mt-2" style="display:none;">
                                <span id="discount_text"></span>
                            </div>

                            <!-- SUMMARY -->
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h6>Order Summary</h6>

                                    <div class="d-flex justify-content-between">
                                        <span>Subtotal</span>
                                        <span id="subtotal">Rp 0</span>
                                    </div>

                                    <div class="d-flex justify-content-between text-success">
                                        <span>Discount</span>
                                        <span id="discount">Rp 0</span>
                                    </div>

                                    <hr>

                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total</span>
                                        <span id="total">Rp 0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- BUTTON -->
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    Buy Now
                                </button>
                                <a href="index.php?page=dashboard&action=user" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </section>
</div>

<script>
    const price = <?= (int) $ticket['price'] ?>;

    const qtyInput = document.getElementById('qty');
    const subtotalEl = document.getElementById('subtotal');
    const discountEl = document.getElementById('discount');
    const totalEl = document.getElementById('total');

    let currentDiscount = 0;

    function formatRupiah(num) {
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function updateSummary() {
        const qty = parseInt(qtyInput.value) || 0;
        const subTotal = qty * price;

        let total = subTotal - currentDiscount;
        if (total < 0) total = 0;

        subtotalEl.textContent = formatRupiah(subTotal);
        discountEl.textContent = formatRupiah(currentDiscount);
        totalEl.textContent = formatRupiah(total);
    }

    qtyInput.addEventListener('input', () => {
        currentDiscount = 0;

        document.getElementById('voucher_message').innerHTML = '';
        document.getElementById('discount_info').style.display = 'none';

        updateSummary();
    });

    function applyVoucher() {
        const voucherCode = document.getElementById('voucher_code').value;
        const qty = parseInt(qtyInput.value) || 0;
        const subTotal = qty * price;

        const messageDiv = document.getElementById('voucher_message');
        const discountDiv = document.getElementById('discount_info');
        const discountText = document.getElementById('discount_text');

        if (!voucherCode.trim()) {
            messageDiv.innerHTML = '<div class="alert alert-warning py-2">Enter voucher code</div>';
            return;
        }

        if (subTotal <= 0) {
            messageDiv.innerHTML = '<div class="alert alert-warning py-2">Select quantity first</div>';
            return;
        }

        const formData = new FormData();
        formData.append('voucher_code', voucherCode);
        formData.append('subtotal', subTotal);

        fetch('index.php?page=order&action=applyVoucher', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    currentDiscount = parseInt(data.discount);

                    messageDiv.innerHTML =
                        '<div class="alert alert-success py-2">' + data.message + '</div>';

                    discountText.textContent =
                        'You save ' + formatRupiah(currentDiscount);

                    discountDiv.style.display = 'block';

                    updateSummary();
                } else {
                    currentDiscount = 0;

                    messageDiv.innerHTML =
                        '<div class="alert alert-danger py-2">' + data.message + '</div>';

                    discountDiv.style.display = 'none';

                    updateSummary();
                }
            })
            .catch(() => {
                messageDiv.innerHTML =
                    '<div class="alert alert-danger py-2">Error applying voucher</div>';
            });
    }

    // init
    updateSummary();
</script>