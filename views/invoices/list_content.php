<?php

require_once __DIR__ . '/../../controllers/InvoiceController.php';
$invoiceController = new InvoiceController();

?>
<div class="mb-4">
    <a href="invoices.php?action=create" class="lg:mx-8 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded mb-4 inline-block shadow transition duration-200">
        <i class="fas fa-plus mr-2"></i> Δημιουργία Νέου Τιμολογίου
    </a>
</div>
<div class="lg:mx-8 py-4 bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 px-4 gap-2">
        <div class="flex flex-col sm:flex-row gap-2">
            <input type="text" id="invoices-search" placeholder="Αναζήτηση τιμολογίου..." class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" oninput="searchInvoicesTable()">
            <select id="invoices-filter" class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" onchange="filterInvoicesTable()">
                <option value="">Όλα</option>
                <option value="paid">Πληρωμένα</option>
                <option value="overdue">Ανεξόφλητα</option>
                <option value="pending">Σε Εκκρεμότητα</option>
            </select>
        </div>
        <div class="flex gap-2 mt-2 md:mt-0">
            <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 text-sm flex items-center shadow" onclick="exportCSVInvoicesTable()">
                <i class="fas fa-file-csv mr-1"></i> Εξαγωγή CSV
            </button>
            <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition duration-300 text-sm flex items-center shadow" onclick="exportExcelInvoicesTable()">
                <i class="fas fa-file-excel mr-1"></i> Εξαγωγή Excel
            </button>
        </div>
    </div>
    <div class="overflow-x-auto md:max-w-full">
        <table id="invoices-table" class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Κωδικός</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Πελάτης</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ημ/νία Δημιουργίας</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ημ/νία Λήξης</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Συνολικό Ποσό</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Μέθοδος Πληρωμής</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Κατάσταση</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ενέργειες</th>
                </tr>
            </thead>
            <tbody id="invoices-table-body" class="bg-white divide-y divide-gray-200">
                <?php foreach ($invoices as $invoice): ?>
               
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($invoice['invoice_id']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($invoice['inv_code']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($invoiceController->showInvoiceClient($invoice['customer_id'])); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($invoice['created_at']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($invoice['due_date']); ?></td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($invoice['total_amount']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($invoice['payment_method']); ?></td>
                    <td class="px-6 py-4">
                        <?php if ($invoice['status'] === 'paid'): ?>
                            <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">Πληρωμένα</span>
                        <?php elseif ($invoice['status'] === 'overdue'): ?>
                            <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">Ανεξόφλητα</span>
                        <?php else:?>
                            <span class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">Σε Εκκρεμότητα</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-3 py-2 text-sm text-gray-500">
                        <div class="flex space-x-2">
                            <a href="invoices.php?action=show&inv_code=<?php echo $invoice['inv_code']; ?>" class="text-blue-600 hover:text-blue-900" title="Προβολή">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="invoices.php?action=edit&inv_code=<?php echo $invoice['inv_code']; ?>" class="text-gray-600 hover:text-gray-900" title="Επεξεργασία">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="invoices.php?action=delete&inv_code=<?php echo $invoice['inv_code']; ?>" class="text-red-500 hover:text-red-700" title="Διαγραφή">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
