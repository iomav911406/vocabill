<?php

require_once __DIR__ . '/../../controllers/ServiceController.php';
$serviceController = new ServiceController();

?>
<div class="mb-4">
    <a href="services.php?action=create" class="lg:mx-8 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded mb-4 inline-block shadow transition duration-200"><i class="fas fa-plus mr-2"></i> Νέα Υπηρεσία</a>
</div>
<div class="lg:mx-8 py-4 bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 px-4 gap-2">
        <div class="flex flex-col sm:flex-row gap-2">
            <input type="text" id="invoices-search" placeholder="Αναζήτηση τιμολογίου..." class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" oninput="searchInvoicesTable()">
            <select id="invoices-filter" class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" onchange="filterInvoicesTable()">
                <option value="">Όλα</option>
                <option value="available">Διαθέσιμο</option>
                <option value="unavailable">Μη Διαθέσιμο</option>
                
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
        <table id="services-table" class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Κωδικός</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Περιγραφή</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Τιμή Μονάδος</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ΦΠΑ</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Κατάσταση</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ενέργειες</th>
                </tr>
            </thead>
            <tbody id="services-table-body" class="bg-white divide-y divide-gray-200">
                <?php foreach ($services as $service): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($service['service_id']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($service['service_code']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($service['description']); ?></td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?php echo htmlspecialchars(number_format($service['price'], 2)); ?> €</td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($service['VAT']); ?>%</td>
                    <td class="px-6 py-4">
                        <?php if ($service['status'] === 'available'): ?>
                            <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">Διαθέσιμο</span>
                        <?php else: ?>
                            <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">Μη Διαθέσιμο</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="services.php?action=show&service_code=<?php echo htmlspecialchars($service['service_code']); ?>" class="text-blue-600 hover:text-blue-900 mr-2" title="Προβολή"><i class="fas fa-eye"></i></a>
                        <a href="services.php?action=edit&service_code=<?php echo htmlspecialchars($service['service_code']); ?>" class="text-yellow-600 hover:text-yellow-900 mr-2" title="Επεξεργασία"><i class="fas fa-edit"></i></a>
                        <a href="services.php?action=delete&service_code=<?php echo htmlspecialchars($service['service_code']); ?>" class="text-red-600 hover:text-red-900" title="Διαγραφή" onclick="return confirm('Είστε σίγουροι ότι θέλετε να διαγράψετε αυτή την υπηρεσία;');"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>  
