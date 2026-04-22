<?php $error = $_SESSION['error'] ?? null; ?>
<?php $old = $_SESSION['old'] ?? []; ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6">
                <h3>Add Voucher</h3>
                <p class="text-subtitle text-muted">Create a new discount voucher</p>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Voucher Information</h4>
            </div>
            <div class="card-body">
                <!-- ERROR -->
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                        <?php unset($_SESSION['error'], $_SESSION['old']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=voucher&action=store">

                    <div class="form-group mb-3">
                        <label for="code">Code</label>
                        <input type="text" class="form-control" id="code" name="code" value="<?= $old['code'] ?? '' ?>"
                            placeholder="Enter voucher code" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="discount" id="discountLabel">Discount (Rp)</label>
                        <input type="number" class="form-control" id="discount" name="discount"
                            value="<?= $old['discount'] ?? '' ?>" placeholder="Enter discount amount" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="quota">Quota</label>
                        <input type="number" class="form-control" id="quota" name="quota"
                            value="<?= $old['quota'] ?? '' ?>" placeholder="Enter voucher quota" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="type">Tipe</label>
                        <select class="form-select" id="type" name="type">
                            <option value="value" <?= (($old['type'] ?? '') === 'value') ? 'selected' : '' ?>>Nominal
                            </option>
                            <option value="percentage" <?= (($old['type'] ?? '') === 'percentage') ? 'selected' : '' ?>>
                                Persentase</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="aktif" <?= (($old['status'] ?? '') === 'aktif') ? 'selected' : '' ?>>Aktif
                            </option>
                            <option value="nonaktif" <?= (($old['status'] ?? '') === 'nonaktif') ? 'selected' : '' ?>>
                                Nonaktif</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                        <a href="index.php?page=voucher&action=index" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
    const typeSelect = document.getElementById('type');
    const discountInput = document.getElementById('discount');
    const discountLabel = document.getElementById('discountLabel');

    function updateDiscountUI() {
        if (typeSelect.value === 'percentage') {
            discountInput.max = 100;
            discountLabel.innerText = 'Discount (%)';
            discountInput.placeholder = 'Enter discount (max 100)';
        } else {
            discountInput.removeAttribute('max');
            discountLabel.innerText = 'Discount (Rp)';
            discountInput.placeholder = 'Enter discount amount';
        }
    }

    typeSelect.addEventListener('change', updateDiscountUI);

    discountInput.addEventListener('input', function () {
        if (typeSelect.value === 'percentage' && this.value > 100) {
            this.value = 100;
        }
    });

    // trigger saat load (biar old value ikut ke-set)
    window.addEventListener('load', updateDiscountUI);
</script>