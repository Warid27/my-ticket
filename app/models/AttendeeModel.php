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
}

?>