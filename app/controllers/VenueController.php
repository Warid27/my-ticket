<?php
require_once 'app/core/BaseController.php';
class VenueController extends BaseController
{
    private VenueModel $model;
    private string $indexPage = 'index.php?page=auth&action=login';

    public function __construct()
    {
        $this->guard($this->adminRoles);
        require_once 'app/models/VenueModel.php';
        $this->model = new VenueModel();
    }

    public function index(): void
    {
        $search = $_GET['search'] ?? '';
        $page = (int) ($_GET['p'] ?? 1);
        $pagination = $this->model->paginate($search, $page);
        require 'app/views/admin/venue/index.php';
    }

    public function create(): void
    {
        require 'app/views/admin/venue/create.php';
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
        require 'app/views/admin/venue/edit.php';
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
