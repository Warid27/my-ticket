<!-- Search and Pagination - Include in admin list pages -->
<form method="GET" style="margin-bottom: 20px;">
    <input type="hidden" name="page" value="<?= $_GET['page'] ?>">
    <input type="hidden" name="action" value="<?= $_GET['action'] ?>">
    <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Search..."
        style="width: 200px;">
    <button type="submit">Search</button>
    <?php if (!empty($_GET['search'])): ?>
        <a href="?page=<?= $_GET['page'] ?>&action=<?= $_GET['action'] ?>">Clear</a>
    <?php endif; ?>
</form>

<?php if ($pagination['total'] > $pagination['perPage']): ?>
    <div style="margin-bottom: 20px;">
        Showing
        <?= $pagination['perPage'] * ($pagination['currentPage'] - 1) + 1 ?>
        to
        <?= min($pagination['perPage'] * $pagination['currentPage'], $pagination['total']) ?>
        of
        <?= $pagination['total'] ?> results

        <?php if ($pagination['hasPrev']): ?>
            <a
                href="?page=<?= $_GET['page'] ?>&action=<?= $_GET['action'] ?>&search=<?= urlencode($_GET['search'] ?? '') ?>&p=<?= $pagination['currentPage'] - 1 ?>">«
                Prev</a>
        <?php endif; ?>

        Page
        <?= $pagination['currentPage'] ?> of
        <?= $pagination['lastPage'] ?>

        <?php if ($pagination['hasMore']): ?>
            <a
                href="?page=<?= $_GET['page'] ?>&action=<?= $_GET['action'] ?>&search=<?= urlencode($_GET['search'] ?? '') ?>&p=<?= $pagination['currentPage'] + 1 ?>">Next
                »</a>
        <?php endif; ?>
    </div>
<?php endif; ?>