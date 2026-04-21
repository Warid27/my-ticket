<!DOCTYPE html>
<html>
<head>
    <title>Edit Voucher - MyTicket</title>
</head>
<body>
    <h1>Edit Voucher</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | <a href="index.php?page=voucher&action=index">Back to Vouchers</a></p>
    
    <form method="POST" action="index.php?page=voucher&action=update">
        <input type="hidden" name="id" value="<?= $voucher['id'] ?>">
        <label>Code<br><input type="text" name="code" value="<?= htmlspecialchars($voucher['code']) ?>" required></label><br><br>
        <label>Discount (Rp)<br><input type="number" name="discount" value="<?= $voucher['discount'] ?>" required></label><br><br>
        <label>Quota<br><input type="number" name="quota" value="<?= $voucher['quota'] ?>" required></label><br><br>
        <label>Status<br>
            <select name="status">
                <option value="active" <?= $voucher['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $voucher['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </label><br><br>
        <button type="submit">Update</button>
        <a href="index.php?page=voucher&action=index">Cancel</a>
    </form>
</body>
</html>
