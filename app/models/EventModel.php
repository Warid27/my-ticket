<?php

require_once 'app/core/BaseModel.php';

class EventModel extends BaseModel
{
    protected string $table = 'events';
    protected array $fillable = ['name', 'date', 'venue_id'];

    public function allWithVenue(): array
    {
        return $this->query("SELECT events.*, venues.name AS venue_name FROM events JOIN venues ON events.venue_id = venues.id");
    }
}

?>