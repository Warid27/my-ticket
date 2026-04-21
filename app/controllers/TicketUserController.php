<?php
require_once 'app/core/BaseController.php';
class TicketUserController extends BaseController
{
    public function __construct()
    {
        $this->guard($this->userRoles);
    }

    public function show(): void
    {
        require_once 'app/models/AttendeeModel.php';
        require_once 'app/models/OrderDetailModel.php';

        $orderDetailModel = new OrderDetailModel();
        $attendeeModel = new AttendeeModel();

        $details = $orderDetailModel->byOrder((int) $_GET['order_id']);
        $attendees = [];

        foreach ($details as $detail) {
            $detailAttendees = $attendeeModel->query("SELECT * FROM attendees WHERE order_detail_id = ?", [$detail['id']]);

            $attendees = [...$attendees, $detailAttendees];
        }

        require 'app/views/user/ticket/show.php';
    }
}
