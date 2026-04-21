<?php
require_once 'app/core/BaseModel.php';

class OrderModel extends BaseModel
{
    protected string $table = 'orders';
    protected array $fillable = ['user_id', 'voucher_id', 'date', 'total', 'status'];

    public function byUser(int $user_id): array
    {
        return $this->query("SELECT orders.*, vouchers.code AS voucher_code, users.name AS customer_name FROM orders LEFT JOIN vouchers ON orders.voucher_id = vouchers.id LEFT JOIN users ON orders.user_id = users.id WHERE orders.user_id = ? ORDER BY orders.date DESC", [$user_id]);
    }

    public function paginate(string $search = '', int $page = 1, int $perPage = 10, string $searchColumn = 'name'): array
    {
        $offset = ($page - 1) * $perPage;
        
        $where = '';
        $params = [];
        if ($search) {
            $where = "WHERE u.name LIKE ? OR o.id LIKE ?";
            $params = ["%$search%", "%$search%"];
        }

        $countSql = "SELECT COUNT(*) as total 
                     FROM orders o 
                     LEFT JOIN users u ON o.user_id = u.id 
                     $where";
        $total = $this->queryOne($countSql, $params)['total'];

        $sql = "SELECT o.*, u.name AS customer_name, v.code AS voucher_code 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                LEFT JOIN vouchers v ON o.voucher_id = v.id 
                $where 
                ORDER BY o.date DESC 
                LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        $data = $this->query($sql, $params);

        $lastPage = ceil($total / $perPage);

        return [
            'data' => $data,
            'currentPage' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'lastPage' => $lastPage,
            'hasMore' => $page < $lastPage,
            'hasPrev' => $page > 1
        ];
    }

    public function find(int $id): array
    {
        $sql = "SELECT o.*, u.name AS customer_name, v.code AS voucher_code 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                LEFT JOIN vouchers v ON o.voucher_id = v.id 
                WHERE o.id = ?";
        return $this->queryOne($sql, [$id]);
    }
}

?>