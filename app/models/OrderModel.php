<?php
require_once 'app/core/BaseModel.php';

class OrderModel extends BaseModel
{
    protected string $table = 'orders';
    protected array $fillable = ['user_id', 'voucher_id', 'date', 'total', 'status'];

    public function byUser(int $user_id): array
    {
        return $this->query("SELECT orders.*, vouchers.code AS voucher_code FROM orders LEFT JOIN vouchers ON orders.voucher_id = vouchers.id WHERE orders.user_id = ? ORDER BY orders.date DESC", [$user_id]);
    }
}

?>