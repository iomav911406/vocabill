<?php

require_once __DIR__ . '/../../controllers/InvoiceController.php';
require_once __DIR__ . '/../../controllers/ClientController.php';
require_once __DIR__ . '/../../controllers/ServiceController.php';
$invoiceController = new InvoiceController();
$clientController = new ClientController();
$serviceController = new ServiceController();
$services= $serviceController->getAllServices();

?>
<div class="lg:mx-8 p-6 bg-white shadow-md rounded-lg border border-gray-200 mb-8">
    
    <form method="post" action="invoices.php?action=create" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-2 text-gray-500 text-sm font-semibold" for="inv_code">Κωδικός Τιμολογίου</label>
                <input type="text" name="inv_code" id="inv_code" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" value="<?php echo 'INV-' . date('Y') . '-' . str_pad($invoiceController->countInvoices() + 1, 3, '0', STR_PAD_LEFT); ?>" readonly>  
            </div>
            <div>
                <label class="block mb-2 text-gray-500 text-sm font-semibold" for="customer_id">Πελάτης</label>
                <select name="customer_id" id="customer_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" required>
                    <option value="">Επιλέξτε Πελάτη</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?php echo $client['customer_id']; ?>"><?php echo $client['customer_id'] . " " . htmlspecialchars($client['company_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block mb-2 text-gray-500 text-sm font-semibold" for="invoice_date">Ημερομηνία Δημιουργίας</label>
                <input type="date" name="invoice_date" id="invoice_date" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" required>
            </div>
            <div>
                <label class="block mb-2 text-gray-500 text-sm font-semibold" for="due_date">Ημερομηνία Λήξης</label>
                <input type="date" name="due_date" id="due_date" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" required>
            </div>
            <div>
                <label class="block mb-2 text-gray-500 text-sm font-semibold" for="payment_method">Μέθοδος Πληρωμής</label>
                <select name="payment_method" id="payment_method" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" required>
                    <option value="">Επιλέξτε Μέθοδο Πληρωμής</option>
                    <option value="Debit Card">Χρεωστική Κάρτα</option>
                    <option value="Bank Transfer">Τραπεζική Μεταφορά</option>
                    <option value="Cash">Μετρητά</option>
                </select>
            </div>
            <div>
                <label class="block mb-2 text-gray-500 text-sm font-semibold" for="status">Κατάσταση</label>
                <select name="status" id="status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" required>
                    <option value="paid">Πληρωμένα</option>
                    <option value="overdue">Ανεξόφλητα</option>
                    <option value="pending">Σε εκκρεμότητα</option>
                </select>
            </div>
        </div>
        <div class="flex gap-2 mt-6">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 text-sm flex items-center shadow">
                <i class="fas fa-save mr-1"></i>  Αποθήκευση
                    </button>
            <a href="invoices.php" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition duration-300 text-sm flex items-center shadow">
                <i class="fas fa-arrow-left mr-1"></i> Επιστροφή
            </a>
        </div>
    </form>
</div>
