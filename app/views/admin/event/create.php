<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Add Event</h3>
                <p class="text-subtitle text-muted">Create a new event</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard&action=admin">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php?page=event&action=index">Events</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Event</li>
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
                <h4 class="card-title">Event Information</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=event&action=store" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter event name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="date">Date</label>
                        <input type="datetime-local" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="venue_id">Venue</label>
                        <select class="form-select" id="venue_id" name="venue_id" required>
                            <option value="">Select Venue</option>
                            <?php foreach ($venues as $v): ?>
                                <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Save
                        </button>
                        <a href="index.php?page=event&action=index" class="btn btn-secondary">
                            <i class="bi bi-x-lg"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
