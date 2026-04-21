<?php

class BaseModel
{
    protected PDO $db;
    protected string $table;
    protected array $fillable = [];

    public function __construct()
    {
        $this->db = getDB();
    }

    public function all(): array
    {
        return $this->db->query("SELECT * FROM {$this->table}")->fetchAll();
    }

    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function insert(array $data): int
    {
        $data = array_intersect_key($data, array_flip($this->fillable));
        $cols = implode(', ', array_keys($data));
        $places = implode(', ', array_fill(0, count($data), '?'));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($cols) VALUE ($places)");
        $stmt->execute(array_values($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $data = array_intersect_key($data, array_flip($this->fillable));
        $set = implode(', ', array_map(fn($c) => "$c = ?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE {$this->table} SET $set WHERE id = ?");
        $stmt->execute([...array_values($data), $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function queryOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function hasRole(array $allowedRoles): bool
    {
        return in_array($_SESSION['role'] ?? '', $allowedRoles);
    }
    public function paginate(string $search = '', int $page = 1, int $perPage = 10, string $searchColumn = 'name'): array
    {
        $offset = ($page - 1) * $perPage;

        $where = '';
        $params = [];
        if ($search) {
            $where = " WHERE $searchColumn LIKE ?";
            $params[] = "%$search%";
        }

        $countSql = "SELECT COUNT(*) as total FROM {$this->table}$where";
        $total = $this->queryOne($countSql, $params)['total'];

        $sql = "SELECT * FROM {$this->table}$where ORDER BY id DESC LIMIT ? OFFSET ?";
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
}