<?php
require_once __DIR__ . '/../models/Service.php';

class ServiceController {
    public function listServices() {
        try {
            $services = Service::all();
        } catch (Exception $e) {
            $services = [];
        }
        include __DIR__ . '/../views/services/list.php';
    }

    public function getAllServices() {
        try {
            return Service::all();
        } catch (Exception $e) {
            return [];
        }
    }

    public function showService($id) {
        $service = Service::find($id);
        if (!$service) {
            echo "Service not found.";
            return;
        }
        include __DIR__ . '/../views/services/read.php';
    }

    public function showServiceDetails($id) {
        $service = Service::find($id);
        if (!$service) {
            echo "Service not found.";
            return;
        }
        include __DIR__ . '/../views/services/show.php';
    }

    public function countServices() {
        return Service::count();
    }

    public function getRecentServices($limit = 5) {
        return Service::getRecent($limit);
    }

    public function createServiceForm() {
        include __DIR__ . '/../views/services/create.php';
    }

    public function createService($data) {
        $serviceId = Service::create($data);
        header("Location: services.php?action=list");
        exit;
    }

    public function editServiceForm($id) {
        $service = Service::find($id);
        if (!$service) {
            echo "Service not found.";
            return;
        }
        include __DIR__ . '/../views/services/update.php';
    }

    public function updateService($id, $data) {
        Service::update($id, $data);
        header("Location: services.php?action=list");
        exit;
    }

    public function deleteService($id) {
        Service::delete($id);
        header("Location: services.php?action=list");
        exit;
    }
}

?>
