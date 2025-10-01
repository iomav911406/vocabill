<?php
require_once __DIR__ . '/../models/InvoiceService.php';
require_once __DIR__ . '/../models/Invoice.php';

class InvoiceServicesController {
    public function listInvoiceServices() {
        $invoiceServices = InvoiceService::all();
        include __DIR__ . '/../views/invoice_services/list.php';
    }

    public function showInvoiceService($id) {
        $invoiceService = InvoiceService::find($id);
        if (!$invoiceService) {
            echo "Invoice Service not found.";
            return;
        }
        include __DIR__ . '/../views/invoice_services/show.php';
    }

    public function getInvoiceServicesByInvoice($invoice_id) {
        return InvoiceService::findByInvoiceId($invoice_id);
    }

    public function countInvoiceServices() {
        return InvoiceService::count();
    }

    public function createInvoiceServiceForm() {
        include __DIR__ . '/../views/invoice_services/create.php';
    }

    public function createInvoiceService($data) {
        
        foreach ($data['service_id'] as $index => $service_id) {
            $quantity = $data['quantity'][$index];
            $invoice_id = $data['invoice_id'];
            $invoice = Invoice::find($invoice_id);
            if ($invoice) {
                $serviceData = [
                    'invoice_id' => $invoice_id,
                    'service_id' => $service_id,
                    'quantity' => $quantity
                ];
                InvoiceService::create($serviceData);
            }

            // Update the invoice total after adding services
            Invoice::updateInvoiceTotal($invoice_id, Invoice::calculateTotal($invoice_id));

        }
        header("Location: invoices.php");
        exit;
    }

    public function editInvoiceServiceForm($id) {
        $invoiceService = InvoiceService::find($id);
        if (!$invoiceService) {
            echo "Invoice Service not found.";
            return;
        }
        include __DIR__ . '/../views/invoice_services/update.php';
    }

    public function updateInvoiceService($id, $data) {
        InvoiceService::update($id, $data);
        header("Location: index.php?action=listInvoiceServices");
        exit;
    }

    public function deleteInvoiceService($id) {
        InvoiceService::delete($id);
        header("Location: index.php?action=listInvoiceServices");
        exit;
    }
}

?>
