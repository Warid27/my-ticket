<?php 
require_once 'app/core/BaseController.php';
class AttendeeController extends BaseController
{
    private AttendeeModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->guard($this->staffRoles);
        require_once 'app/models/AttendeeModel.php';
        $this->model = new AttendeeModel();
    }

    public function index(): void
    {
        unset($_SESSION['error']);
        unset($_SESSION['success']);
        
        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('checkin'));
        $this->layout->render('admin/checkin/index', [
            'title' => 'Check-in - MyTicket',
            'activeMenu' => 'checkin'
        ]);
    }

    public function checkin(): void
    {
        $ticketCode = $_POST['ticket_code'] ?? '';
        $attendee = $this->model->findByCode($ticketCode);

        if (!$attendee) {
            $_SESSION['error'] = 'Ticket code not found';
            header('Location: index.php?page=attendee&action=index');
            exit;
        }

        if ($attendee['checkin_status'] === 'checked') {
            $_SESSION['error'] = 'Ticket already checked in';
            header('Location: index.php?page=attendee&action=index');
            exit;
        }

        $this->model->update($attendee['id'], ['checkin_status' => 'checked']);
        $_SESSION['success'] = 'Check-in successful';
        header('Location: index.php?page=attendee&action=index');
        exit;
    }
}
