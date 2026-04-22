<?php

require_once 'app/core/BaseModel.php';

class AttendeeModel extends BaseModel
{
    protected string $table = 'attendees';
    protected array $fillable = ['detail_id', 'ticket_code', 'checkin_status', 'checkin_time'];

    public function findByCode(string $code): ?array
    {
        return $this->queryOne("SELECT * FROM attendees WHERE ticket_code = ?", [$code]);
    }

    public function paginate(string $search = '', int $page = 1, int $perPage = 10, string $searchColumn = 'name'): array
    {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = '';
        $params = [];
        
        if (!empty($search)) {
            $whereClause = " WHERE ticket_code LIKE ? OR checkin_status LIKE ?";
            $params = ["%$search%", "%$search%"];
        }
        
        $countSql = "SELECT COUNT(*) as total FROM attendees" . $whereClause;
        $total = $this->queryOne($countSql, $params)['total'] ?? 0;
        
        $sql = "SELECT * FROM attendees" . $whereClause . " ORDER BY id DESC LIMIT ? OFFSET ?";
        $data = $this->query($sql, [...$params, $perPage, $offset]);
        
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

?>