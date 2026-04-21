<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Add Voucher</h3>
                <p class="text-subtitle text-muted">Create a new discount voucher</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard&action=admin">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php?page=voucher&action=index">Vouchers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Voucher</li>
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
                <form method="POST" action="index.php?page=voucher&action=store">
                    <div class="form-group mb-3">
                        <label for="code">Code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Enter voucher code" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="discount">Discount (Rp)</label>
                        <input type="number" class="form-control" id="discount" name="discount" placeholder="Enter discount amount" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="quota">Quota</label>
                        <input type="number" class="form-control" id="quota" name="quota" placeholder="Enter voucher quota" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Save
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
