<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Voucher</h3>
                <p class="text-subtitle text-muted">Update voucher information</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard&action=admin">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php?page=voucher&action=index">Vouchers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Voucher</li>
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
                <h4 class="card-title">Voucher Information</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=voucher&action=update">
                    <input type="hidden" name="id" value="<?= $voucher['id'] ?>">
                    <div class="form-group mb-3">
                        <label for="code">Code</label>
                        <input type="text" class="form-control" id="code" name="code" value="<?= htmlspecialchars($voucher['code']) ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="discount">Discount (Rp)</label>
                        <input type="number" class="form-control" id="discount" name="discount" value="<?= $voucher['discount'] ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="quota">Quota</label>
                        <input type="number" class="form-control" id="quota" name="quota" value="<?= $voucher['quota'] ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="aktif" <?= $voucher['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="inaktif" <?= $voucher['status'] == 'inaktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Update
                        </button>
                        <a href="index.php?page=voucher&action=index" class="btn btn-secondary">
                            <i class="bi bi-x-lg"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
