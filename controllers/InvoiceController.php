<?php
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/InvoiceService.php';

class InvoiceController {
    public function listInvoices() {
        $invoices = Invoice::all();
        include __DIR__ . '/../views/invoices/list.php';
    }

    public function showInvoices($id) {
        $invoice = Invoice::find($id);
        if (!$invoice) {
            // Handle item not found (e.g., display an error message)
            echo "Invoice not found.";
            return;
        }
        include __DIR__ . '/../views/invoices/read.php';
    }

    public function showInvoice($inv_code) {
        $invoice = Invoice::findByInvCode($inv_code);
        if (!$invoice) {
            echo "Invoice not found.";
            return;
        }
        
        $invoiceDetails = $invoice;
        $clientDetails = Client::findByCode($invoice['customer_id']);
        $invoiceServices = InvoiceService::getInvoiceServicesWithDetails($invoice['invoice_id']);
        
        include __DIR__ . '/../views/invoices/show.php';
    }

    public function getRecentInvoices($limit = 5) {
        return Invoice::getRecent($limit);
    }

    public function showInvoiceClient($customer_id) {
        $client = Client::findByCode($customer_id);
        if (!$client) {
            // Handle client not found (e.g., display an error message)
            echo "Client not found.";
            return;
        }
        return $client['company_name'];
    }

    public function countInvoices() {
        return Invoice::count();
    }

    public function totalPaidAmount() {
        return Invoice::totalPaidAmount();
    }

    public function countPaidInvoices() {
        // Replace with actual counting logic for paid invoices
        return Invoice::countPaid();
    }

    public function createInvoiceForm() {
        $clients = Client::all();
        include __DIR__ . '/../views/invoices/create.php';
    }

    public function createInvoice($data) {
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(strip_tags($value));

            echo $key . ': ' . $data[$key] . '<br>'; // Debugging line
        }
        var_dump($data); // Debugging line
        $invoiceId = Invoice::create($data);

        header("Location: invoice-services.php?action=create&invoice_id=" . urlencode($invoiceId)); // Redirect to the invoice services creation page
        exit;
    }

    public function editInvoiceForm($id) {
        $invoice = Invoice::findByInvCode($id);
        if (!$invoice) {
            echo "Invoice not found.";
            return;
        }
        include __DIR__ . '/../views/invoices/update.php';
    }

    public function updateInvoice($id, $data) {
        Invoice::update($id, $data);
        header("Location: invoices.php?action=list"); // Redirect to the list
        exit;
    }

    public function deleteInvoice($id) {
        Invoice::delete($id);
        header("Location: index.php?action=list"); // Redirect to the list
        exit;
    }
}

?>
