<div class="mb-4">
    <a href="clients.php?action=create" class="lg:mx-8 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded mb-4 inline-block shadow transition duration-200">
        <i class="fas fa-plus mr-2"></i> Δημιουργία Νέου Πελάτη
    </a>
</div>
<div class="lg:mx-8 py-4 bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 px-4 gap-2">
        <div class="flex flex-col sm:flex-row gap-2">
            <input type="text" id="clients-search" placeholder="Αναζήτηση πελάτη..." class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" oninput="searchClientsTable()">
            <select id="clients-filter" class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" onchange="filterClientsTable()">
                <option value="">Όλα</option>
                <option value="active">Ενεργοί</option>
                <option value="inactive">Ανενεργοί</option>
            </select>
        </div>
        <div class="flex gap-2 mt-2 md:mt-0">
            <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 text-sm flex items-center shadow" onclick="exportCSVClientsTable()">
                <i class="fas fa-file-csv mr-1"></i> Εξαγωγή CSV
            </button>
            <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition duration-300 text-sm flex items-center shadow" onclick="exportExcelClientsTable()">
                <i class="fas fa-file-excel mr-1"></i> Εξαγωγή Excel
            </button>
        </div>
    </div>
    <div class="overflow-x-auto md:max-w-full">
        <table id="clients-table" class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Κωδικός Πελάτη</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Πελάτης</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Επαφή</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Τηλέφωνο</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Κατάσταση</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ενέργειες</th>
                </tr>
            </thead>
            <tbody id="clients-table-body" class="bg-white divide-y divide-gray-200">
                <?php foreach ($clients as $client): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($client['customer_id']); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($client['customer_code']); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($client['company_name']); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($client['contact_name']); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($client['phone']); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($client['email']); ?></td>
                        <td class="px-6 py-4">
                            <?php if ($client['status'] === 'active'): ?>
                                <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">Ενεργός</span>
                            <?php else: ?>
                                <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">Μη Ενεργός</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="flex space-x-2">
                                <a href="clients.php?action=show&id=<?php echo $client['customer_id']; ?>" class="text-blue-600 hover:text-blue-900" title="Προβολή">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="clients.php?action=edit&id=<?php echo $client['customer_id']; ?>" class="text-gray-600 hover:text-gray-900" title="Επεξεργασία">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="clients.php?action=delete&id=<?php echo $client['customer_id']; ?>" class="text-red-500 hover:text-red-700" title="Διαγραφή">
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
