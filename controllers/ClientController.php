<?php

require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Invoice.php';

class ClientController {
    public function listClients() {
        $clients = Client::all();
        include __DIR__ . '/../views/clients/list.php';
    }

    public function getAllClients() {
        try {
            return Client::all();
        } catch (Exception $e) {
            return [];
        }
    }

    public function showClient($id) {
        $client = Client::find($id);
        if (!$client) {
            echo "Client not found.";
            return;
        }
        
        // Fetch client's invoices
        $clientInvoices = Invoice::findByCustomerId($id);
        
        include __DIR__ . '/../views/clients/show.php';
    }

    public function countClients() {
        return Client::count();
    }

    public function countActiveClients() {
        return Client::countActive();
    }

    public function countRecentClients($days = 30) {
        return Client::countWithLimit($days);
    }

    public function createClientForm() {
        include __DIR__ . '/../views/clients/create.php';
    }

    public function createClient($data) {
        $clientId = Client::create($data);
        header("Location: clients.php?action=list"); // Redirect to the list
        exit;
    }

    public function editClientForm($id) {
        $client = Client::find($id);
        if (!$client) {
            echo "Client not found.";
            return;
        }
        include __DIR__ . '/../views/clients/update.php';
    }

    public function updateClient($id, $data) {
        Client::update($id, $data);
        header("Location: clients.php?action=list"); // Redirect to the list
        exit;
    }

    public function deleteClient($id) {
        Client::delete($id);
        header("Location: clients.php?action=list"); // Redirect to the list
        exit;
    }
}

?>
