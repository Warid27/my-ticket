<!DOCTYPE html>
<html>
<head>
    <title>Add Voucher - MyTicket</title>
</head>
<body>
    <h1>Add Voucher</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | <a href="index.php?page=voucher&action=index">Back to Vouchers</a></p>
    
    <form method="POST" action="index.php?page=voucher&action=store">
        <label>Code<br><input type="text" name="code" required></label><br><br>
        <label>Discount (Rp)<br><input type="number" name="discount" required></label><br><br>
        <label>Quota<br><input type="number" name="quota" required></label><br><br>
        <label>Status<br>
            <select name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </label><br><br>
        <button type="submit">Save</button>
        <a href="index.php?page=voucher&action=index">Cancel</a>
    </form>
</body>
</html>
