<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Add Ticket</h3>
                <p class="text-subtitle text-muted">Create a new ticket</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard&action=admin">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php?page=ticket&action=index">Tickets</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Ticket</li>
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
                <h4 class="card-title">Ticket Information</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=ticket&action=store">
                    <div class="form-group mb-3">
                        <label for="event_id">Event</label>
                        <select class="form-select" id="event_id" name="event_id" required>
                            <option value="">Select Event</option>
                            <?php foreach ($events as $e): ?>
                                <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['name']) ?> (<?= $e['venue_name'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">Ticket Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter ticket name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="price">Price (Rp)</label>
                        <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="quota">Quota</label>
                        <input type="number" class="form-control" id="quota" name="quota" placeholder="Enter quota" required>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Save
                        </button>
                        <a href="index.php?page=ticket&action=index" class="btn btn-secondary">
                            <i class="bi bi-x-lg"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
