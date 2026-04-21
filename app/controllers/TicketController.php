<?php
require_once 'app/core/BaseController.php';
class TicketController extends BaseController
{

    private TicketModel $model;
    private string $indexPage = 'index.php?page=ticket&action=index';

    public function __construct()
    {
        require_once 'app/models/TicketModel.php';
        $this->model = new TicketModel();
        $this->guard($this->adminRoles);
    }
    public function index(): void
    {
        require_once 'app/models/EventModel.php';
        $eventModel = new EventModel();
        $search = $_GET['search'] ?? '';
        $page = (int) ($_GET['p'] ?? 1);
        $pagination = $this->model->paginate($search, $page);
        $tickets = $pagination['data'];

        $ticketsWithEvent = [];
        foreach ($tickets as $ticket) {
            $event = $eventModel->find($ticket['event_id']);
            $ticket['event_name'] = $event['name'] ?? 'Unknown';
            $ticketsWithEvent[] = $ticket;
        }
        $pagination['data'] = $ticketsWithEvent;

        require 'app/views/admin/ticket/index.php';
    }

    public function create(): void
    {
        $this->guard(['admin']);
        require_once 'app/models/EventModel.php';
        $eventModel = new EventModel();
        $events = $eventModel->allWithVenue();
        require 'app/views/admin/ticket/create.php';
    }

    public function store(): void
    {
        $this->guard(['admin']);
        $this->model->insert([
            'event_id' => (int) $_POST['event_id'],
            'name' => $_POST['name'],
            'price' => (int) $_POST['price'],
            'quota' => (int) $_POST['quota']
        ]);
        header("Location: $this->indexPage");
        exit;
    }

    public function edit(): void
    {
        $this->guard(['admin']);
        $ticket = $this->model->find((int) $_GET['id']);
        require_once 'app/models/EventModel.php';
        $eventModel = new EventModel();
        $events = $eventModel->allWithVenue();
        require 'app/views/admin/ticket/edit.php';
    }

    public function update(): void
    {
        $this->guard(['admin']);
        $this->model->update((int) $_POST['id'], [
            'event_id' => (int) $_POST['event_id'],
            'name' => $_POST['name'],
            'price' => (int) $_POST['price'],
            'quota' => (int) $_POST['quota']
        ]);
        header("Location: $this->indexPage");
        exit;
    }

    public function destroy(): void
    {
        $this->guard(['admin']);
        $this->model->delete((int) $_GET['id']);
        header("Location: $this->indexPage");
        exit;
    }
}
