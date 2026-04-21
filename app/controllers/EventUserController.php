<?php
require_once 'app/core/BaseController.php';
class EventUserController extends BaseController
{
    private EventModel $model;

    public function __construct()
    {
        require_once 'app/models/EventModel.php';
        $this->model = new EventModel();
        $this->isLoggedIn();
    }

    public function index(): void
    {
        $events = $this->model->allWithVenue();
        require 'app/views/users/event/index.php';
    }

    public function show(): void
    {
        $event = $this->model->find((int) $_GET['id']);

        // Venue Name
        require_once 'app/models/VenueModel.php';
        $venueModel = new VenueModel();
        $venue = $venueModel->find($event['venue_id']);
        $event['venue_name'] = $venue['name'];

        require_once 'app/models/TicketModel.php';
        $ticketModel = new TicketModel();
        $tickets = $ticketModel->byEvent((int) $_GET['id']);

        foreach ($tickets as $ticket) {
            $ticket['event_name'] = $event['name'];
        }

        require 'app/views/user/event/show.php';
    }
}
