<?php
require_once 'app/core/BaseModel.php';

class TicketModel extends BaseModel
{
    protected string $table = 'tickets';
    protected array $fillable = ['event_id', 'name', 'price', 'quota'];

    public function byEvent(int $eventId): array
    {
        return $this->query("SELECT * FROM tickets WHERE event_id = ?", [$eventId]);
    }
}