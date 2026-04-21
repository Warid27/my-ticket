<?php
require_once 'app/core/BaseController.php';
class VenueController extends BaseController
{
    private VenueModel $model;
    private string $indexPage = 'index.php?page=auth&action=login';

    public function __construct()
    {
        parent::__construct();
        $this->guard($this->adminRoles);
        require_once 'app/models/VenueModel.php';
        $this->model = new VenueModel();
    }

    public function index(): void
    {
        $search = $_GET['search'] ?? '';
        $page = (int) ($_GET['p'] ?? 1);
        $pagination = $this->model->paginate($search, $page);

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('venues'));
        $this->layout->render('admin/venue/index', [
            'title' => 'Venues - MyTicket',
            'pagination' => $pagination,
            'activeMenu' => 'venues'
        ]);
    }

    public function create(): void
    {
        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('venues'));
        $this->layout->render('admin/venue/create', [
            'title' => 'Add Venue - MyTicket',
            'activeMenu' => 'venues'
        ]);
    }

    public function store(): void
    {
        $this->model->insert([
            'name' => $_POST['name'],
            'address' => $_POST['address'],
            'capacity' => (int) $_POST['capacity']
        ]);
        header("Location: $this->indexPage");
        exit;
    }

    public function edit(): void
    {
        $venue = $this->model->find((int) $_GET['id']);

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('venues'));
        $this->layout->render('admin/venue/edit', [
            'title' => 'Edit Venue - MyTicket',
            'venue' => $venue,
            'activeMenu' => 'venues'
        ]);
    }

    public function update(): void
    {
        $this->model->update((int) $_POST['id'], [
            'name' => $_POST['name'],
            'address' => $_POST['address'],
            'capacity' => (int) $_POST['capacity']
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
