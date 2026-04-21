<?php
require_once 'app/core/BaseController.php';
class EventUserController extends BaseController
{
    private EventModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->guard($this->userRoles);
        require_once 'app/models/EventModel.php';
        $this->model = new EventModel();
    }

    public function index(): void
    {
        $events = $this->model->allWithVenue();
        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('events'));
        $this->layout->render('user/event/index', [
            'title' => 'Browse Events - MyTicket',
            'events' => $events,
            'activeMenu' => 'events'
        ]);
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

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('events'));
        $this->layout->render('user/event/show', [
            'title' => 'Event Details - MyTicket',
            'event' => $event,
            'venue' => $venue,
            'tickets' => $tickets,
            'activeMenu' => 'events'
        ]);
    }
}
