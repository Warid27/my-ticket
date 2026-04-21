<?php

require_once 'app/core/BaseModel.php';

class OrderDetailModel extends BaseModel
{
    protected string $table = 'order_details';
    protected array $fillable = ['order_id', 'ticket_id', 'qty', 'subtotal'];

    public function byOrder(int $orderId): array
    {
        return $this->query("SELECT order_details.*, tickets.name AS ticket_name, tickets.price, events.name AS event_name FROM order_details JOIN tickets ON order_details.ticket_id = tickets.id JOIN events ON tickets.event_id = events.id WHERE order_details.order_id = ?", [$orderId]);
    }
}
?>