<!-- Voice Button in absolute position -->
    <div id="voice-btn" class="fixed top-4 right-4">
        <button class="bg-orange-500 text-white px-3 py-2 rounded-full shadow-lg hover:bg-orange-600 transition duration-300">
            <i class="fas fa-microphone"></i>
        </button>
    </div>
    <div class="container mx-auto p-4 sm:p-6">
        <div class="invoice-header space-y-4">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">Invoice Details</h1>
                    <p class="text-sm sm:text-base text-gray-600">Invoice Code: <strong><?= htmlspecialchars($invoiceDetails['inv_code']); ?></strong></p>
                    <p class="text-sm sm:text-base text-gray-600">Date: <?= htmlspecialchars($invoiceDetails['invoice_date']); ?></p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                    <button onclick="exportToPDF()" class="w-full sm:w-auto px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition duration-300 text-sm sm:text-base">
                        <i class="fas fa-file-pdf mr-2"></i>Export to PDF
                    </button>
                    
                    <button onclick="window.location.href='invoices.php'" class="w-full sm:w-auto px-4 py-2 bg-gray-800 text-gray-100 rounded hover:bg-gray-900 transition duration-300 text-sm sm:text-base">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </button>
                </div>
            </div>
        </div>

        <div class="invoice-details space-y-4 sm:space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Client Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Company Name:</label>
                            <p class="text-gray-800 font-medium"><?= htmlspecialchars($clientDetails['company_name']); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email:</label>
                            <p class="text-gray-800">
                                <a href="mailto:<?= htmlspecialchars($clientDetails['email']); ?>" class="text-blue-600 hover:text-blue-800 underline">
                                    <?= htmlspecialchars($clientDetails['email']); ?>
                                </a>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Phone:</label>
                            <p class="text-gray-800">
                                <a href="tel:<?= htmlspecialchars($clientDetails['phone']); ?>" class="text-blue-600 hover:text-blue-800 underline">
                                    <?= htmlspecialchars($clientDetails['phone']); ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Invoice Summary</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Invoice Date:</label>
                            <p class="text-gray-800"><?= htmlspecialchars($invoiceDetails['invoice_date']); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Due Date:</label>
                            <p class="text-gray-800"><?= htmlspecialchars($invoiceDetails['due_date']); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Payment Method:</label>
                            <p class="text-gray-800"><?= htmlspecialchars($invoiceDetails['payment_method']); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Status:</label>
                            <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full 
                                <?php 
                                switch($invoiceDetails['status']) {
                                    case 'paid':
                                        echo 'bg-green-100 text-green-800';
                                        break;
                                    case 'pending':
                                        echo 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'overdue':
                                        echo 'bg-red-100 text-red-800';
                                        break;
                                    default:
                                        echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= ucfirst(htmlspecialchars($invoiceDetails['status'])); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="invoice-items w-full">
                <thead class="hidden sm:table-header-group">
                    <tr>
                        <th class="font-medium text-gray-700 text-left px-3 py-2">Service</th>
                        <th class="font-medium text-gray-700 text-left px-3 py-2 hidden md:table-cell">Description</th>
                        <th class="font-medium text-gray-700 text-center px-3 py-2">Qty</th> 
                        <th class="font-medium text-gray-700 text-right px-3 py-2">Unit Price</th>
                        <th class="font-medium text-gray-700 text-right px-3 py-2 hidden lg:table-cell">VAT (%)</th>
                        <th class="font-medium text-gray-700 text-right px-3 py-2">Total</th>
                    </tr>
                </thead>
            <tbody>
                <?php if (!empty($invoiceServices)): ?>
                    <?php foreach ($invoiceServices as $service): ?>
                        <tr class="sm:table-row flex flex-col border-b pb-4 mb-4 sm:mb-0 sm:pb-0 sm:border-b-0">
                            <td class="px-3 py-2 font-medium flex justify-between sm:table-cell">
                                <span class="sm:hidden font-medium">Service:</span>
                                <?= htmlspecialchars($service['service_name'] ?? 'N/A'); ?>
                            </td>
                            <td class="px-3 py-2 hidden md:table-cell"><?= htmlspecialchars($service['service_name'] ?? 'N/A'); ?></td>
                            <td class="px-3 py-2 text-center flex justify-between sm:table-cell">
                                <span class="sm:hidden font-medium">Quantity:</span>
                                <?= htmlspecialchars($service['quantity'] ?? 1); ?>
                            </td>
                            <td class="px-3 py-2 text-right flex justify-between sm:table-cell">
                                <span class="sm:hidden font-medium">Unit Price:</span>
                                $<?= htmlspecialchars(number_format($service['service_price'] ?? 0, 2)); ?>
                            </td>
                            <td class="px-3 py-2 text-right hidden lg:table-cell"><?= number_format($service['service_vat'] ?? 0, 2); ?>%</td>
                            <td class="px-3 py-2 text-right font-semibold flex justify-between sm:table-cell">
                                <span class="sm:hidden font-medium">Total:</span>
                                $<?= number_format($service['quantity']*($service['service_price'] + $service['service_price']*$service['service_vat']) ?? 0, 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">No services found for this invoice.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            </table>
        </div>

        <div class="flex justify-end mt-6">
            <div class="bg-gray-50 p-6 rounded-lg">
                <div class="text-right">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Total Amount:</label>
                    <p class="text-3xl font-bold text-green-600">$<?= number_format($invoiceDetails['total_amount'], 2); ?></p>
                </div>
            </div>
        </div>

    </div>
    
    <script>
        
    function exportToPDF() {
        // Correct way to get jsPDF from the UMD bundle
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.text("Invoice Details", 14, 16);
        doc.text("Invoice Code: <?= htmlspecialchars($invoiceDetails['inv_code']); ?>", 14, 22);
        doc.text("Date: <?= htmlspecialchars($invoiceDetails['invoice_date']); ?>", 14, 28);
        doc.text("Client Information:", 14, 36);    
        doc.text("Name: <?= htmlspecialchars($clientDetails['company_name']); ?>", 14, 42);
        doc.text("Email: <?= htmlspecialchars($clientDetails['email']); ?>", 14, 48);
        doc.text("Phone: <?= htmlspecialchars($clientDetails['phone']); ?>", 14, 54);
        const services = <?= json_encode($invoiceServices); ?>;
        const serviceData = services.map(service => [
            service.description,
            service.quantity,
            Number(service.price).toFixed(2),
            Number(service.VAT).toFixed(2),
            Number(service.total).toFixed(2)
        ]);
        if (typeof doc.autoTable === "function") {
            doc.autoTable({
                head: [['Description', 'Quantity', 'Unit Price', 'VAT', 'Total']],
                body: serviceData,
                startY: 64,
            });
            doc.text("Total Amount: $<?= number_format($invoiceDetails['total_amount'], 2); ?>", 14, doc.lastAutoTable.finalY + 10);
        } else {
            alert("autoTable plugin not loaded. Please check your script includes.");
        }
        doc.save("invoice_details.pdf");
    }
    </script>