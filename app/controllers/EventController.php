<?php
require_once 'app/core/BaseController.php';
class EventController extends BaseController
{
    private EventModel $model;
    private string $indexPage = 'index.php?page=event&action=index';

    public function __construct()
    {
        $this->guard($this->adminRoles);
        require_once 'app/models/EventModel.php';
        $this->model = new EventModel();
    }

    public function index(): void
    {
        require_once 'app/models/EventModel.php';
        $eventModel = new EventModel();
        $search = $_GET['search'] ?? '';
        $page = (int) ($_GET['p'] ?? 1);
        $pagination = $eventModel->paginate($search, $page);
        $events = $pagination['data'];

        $eventsWithVenue = [];
        foreach ($events as $event) {
            $venue = $eventModel->find($event['venue_id']);
            $event['venue_name'] = $venue['name'] ?? 'Tidak Diketahui';
            $eventsWithVenue[] = $event;
        }
        $pagination['data'] = $eventsWithVenue;

        require 'app/views/admin/event/index.php';
    }

    public function create(): void
    {
        $this->guard(['admin']);
        require_once 'app/models/VenueModel.php';
        $venueModel = new VenueModel();
        $venues = $venueModel->all();
        require 'app/views/admin/event/create.php';
    }

    public function store(): void
    {
        $this->guard(['admin']);
        $this->model->insert([
            'name' => $_POST['name'],
            'date' => $_POST['date'],
            'venue_id' => (int) $_POST['venue_id']
        ]);
        header("Location: $this->indexPage");
        exit;
    }

    public function edit(): void
    {
        $this->guard(['admin']);
        $event = $this->model->find((int) $_GET['id']);
        require_once 'app/models/VenueModel.php';
        $venueModel = new VenueModel();
        $venues = $venueModel->all();
        require 'app/views/admin/event/edit.php';
    }

    public function update(): void
    {
        $this->guard(['admin']);
        $this->model->update((int) $_POST['id'], [
            'name' => $_POST['name'],
            'date' => $_POST['date'],
            'venue_id' => (int) $_POST['venue_id']
        ]);
        header("Location: $this->indexPage");
        exit;
    }

    public function destroy(): void
    {
        $this->model->delete((int) $_GET['id']);
        header("Location: $this->indexPage");
        exit;
    }
}
