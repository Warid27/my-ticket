<?php $old = $_SESSION['old'] ?? []; ?>
<?php unset($_SESSION['old']); ?>

<?php
// helper biar gak ribet
function oldOrVoucher($key, $voucher, $old) {
    return $old[$key] ?? $voucher[$key] ?? '';
}
?>

<div class="page-heading">
    <h3>Edit Voucher</h3>
    <p class="text-subtitle text-muted">Update voucher information</p>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Voucher Information</h4>
            </div>
            <div class="card-body">

                <form method="POST" action="index.php?page=voucher&action=update">
                    <input type="hidden" name="id" value="<?= $voucher['id'] ?>">

                    <div class="form-group mb-3">
                        <label for="code">Code</label>
                        <input type="text" class="form-control" id="code" name="code"
                            value="<?= htmlspecialchars(oldOrVoucher('code', $voucher, $old)) ?>"
                            required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="discount" id="discountLabel">Discount (Rp)</label>
                        <input type="number" class="form-control" id="discount" name="discount"
                            value="<?= oldOrVoucher('discount', $voucher, $old) ?>"
                            required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="quota">Quota</label>
                        <input type="number" class="form-control" id="quota" name="quota"
                            value="<?= oldOrVoucher('quota', $voucher, $old) ?>"
                            required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="type">Tipe</label>
                        <?php $typeValue = oldOrVoucher('type', $voucher, $old); ?>
                        <select class="form-select" id="type" name="type">
                            <option value="value" <?= $typeValue === 'value' ? 'selected' : '' ?>>Nominal</option>
                            <option value="percentage" <?= $typeValue === 'percentage' ? 'selected' : '' ?>>Persentase</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <?php $statusValue = oldOrVoucher('status', $voucher, $old); ?>
                        <select class="form-select" id="status" name="status">
                            <option value="aktif" <?= $statusValue === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= $statusValue === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            Update
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
        } else {
            discountInput.removeAttribute('max');
            discountLabel.innerText = 'Discount (Rp)';
        }
    }

    typeSelect.addEventListener('change', updateDiscountUI);

    discountInput.addEventListener('input', function () {
        if (typeSelect.value === 'percentage' && this.value > 100) {
            this.value = 100;
        }
    });

    window.addEventListener('load', updateDiscountUI);
</script>