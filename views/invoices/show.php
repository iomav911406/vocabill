<?php 

require_once __DIR__ . '/../../controllers/InvoiceController.php';

$invoiceController = new InvoiceController();
$client = $invoiceController->showInvoiceClient($invoice['customer_id']);
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Λεπτομέρειες Τιμολογίου - vocabill ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://use.fontawesome.com/69c4c50d03.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .invoice-header { background-color: #f3f4f6; padding-top: 2em; border-radius: 8px; margin-bottom: 20px; }
        .invoice-details { background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); }
        .invoice-items { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; border-radius: 8px; overflow: hidden; }
        .invoice-items th, .invoice-items td { border: 1px solid #e5e7eb; padding: 12px; text-align: left; }
        .invoice-items th { background-color: #f9fafb; font-weight: 600; font-size: 0.875rem; color: #374151; }
        .invoice-items tr:nth-child(even) { background-color: #f9fafb; }
        .invoice-items tr:hover { background-color: #f3f4f6; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100">
    <div id="voice-btn" class="fixed top-4 right-4">
        <button class="bg-orange-500 text-white px-3 py-2 rounded-full shadow-lg hover:bg-orange-600 transition duration-300">
            <i class="fas fa-microphone"></i>
        </button>
    </div>
    <div class="container mx-auto p-4 sm:p-6">
        <div class="invoice-header space-y-4">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">Λεπτομέρειες Τιμολογίου</h1>
                    <p class="text-sm sm:text-base text-gray-600">Κωδικός Τιμολογίου: <strong><?= htmlspecialchars($invoiceDetails['inv_code']); ?></strong></p>
                    <p class="text-sm sm:text-base text-gray-600">Ημερομηνία: <?= htmlspecialchars($invoiceDetails['invoice_date']); ?></p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                    <button onclick="exportToPDF()" class="w-full sm:w-auto px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition duration-300 text-sm sm:text-base">
                        <i class="fas fa-file-pdf mr-2"></i>Εξαγωγή σε PDF
                    </button>
                    <button onclick="window.location.href='invoices.php'" class="w-full sm:w-auto px-4 py-2 bg-gray-800 text-gray-100 rounded hover:bg-gray-900 transition duration-300 text-sm sm:text-base">
                        <i class="fas fa-arrow-left mr-2"></i>Επιστροφή
                    </button>
                </div>
            </div>
        </div>

        <div class="invoice-details space-y-4 sm:space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Στοιχεία Πελάτη</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Επωνυμία:</label>
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
                            <label class="block text-sm font-medium text-gray-600">Τηλέφωνο:</label>
                            <p class="text-gray-800">
                                <a href="tel:<?= htmlspecialchars($clientDetails['phone']); ?>" class="text-blue-600 hover:text-blue-800 underline">
                                    <?= htmlspecialchars($clientDetails['phone']); ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Σύνοψη Τιμολογίου</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Ημερομηνία Τιμολογίου:</label>
                            <p class="text-gray-800"><?= htmlspecialchars($invoiceDetails['invoice_date']); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Ημερομηνία Λήξης:</label>
                            <p class="text-gray-800"><?= htmlspecialchars($invoiceDetails['due_date']); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Τρόπος Πληρωμής:</label>
                            <p class="text-gray-800"><?= htmlspecialchars($invoiceDetails['payment_method']); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Κατάσταση:</label>
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
                        <th class="font-medium text-gray-700 text-left px-3 py-2">Υπηρεσία</th>
                        <th class="font-medium text-gray-700 text-left px-3 py-2 hidden md:table-cell">Περιγραφή</th>
                        <th class="font-medium text-gray-700 text-center px-3 py-2">Ποσότητα</th> 
                        <th class="font-medium text-gray-700 text-right px-3 py-2">Τιμή Μονάδας</th>
                        <th class="font-medium text-gray-700 text-right px-3 py-2 hidden lg:table-cell">ΦΠΑ (%)</th>
                        <th class="font-medium text-gray-700 text-right px-3 py-2">Σύνολο</th>
                    </tr>
                </thead>
            <tbody>
                <?php if (!empty($invoiceServices)): ?>
                    <?php foreach ($invoiceServices as $service): ?>
                        <tr class="sm:table-row flex flex-col border-b pb-4 mb-4 sm:mb-0 sm:pb-0 sm:border-b-0">
                            <td class="px-3 py-2 font-medium flex justify-between sm:table-cell">
                                <span class="sm:hidden font-medium">Υπηρεσία:</span>
                                <?= htmlspecialchars($service['service_name'] ?? 'N/A'); ?>
                            </td>
                            <td class="px-3 py-2 hidden md:table-cell"><?= htmlspecialchars($service['service_name'] ?? 'N/A'); ?></td>
                            <td class="px-3 py-2 text-center flex justify-between sm:table-cell">
                                <span class="sm:hidden font-medium">Ποσότητα:</span>
                                <?= htmlspecialchars($service['quantity'] ?? 1); ?>
                            </td>
                            <td class="px-3 py-2 text-right flex justify-between sm:table-cell">
                                <span class="sm:hidden font-medium">Τιμή Μονάδας:</span>
                                €<?= htmlspecialchars(number_format($service['service_price'] ?? 0, 2)); ?>
                            </td>
                            <td class="px-3 py-2 text-right hidden lg:table-cell"><?= number_format($service['service_vat'] ?? 0, 2); ?>%</td>
                            <td class="px-3 py-2 text-right font-semibold flex justify-between sm:table-cell">
                                <span class="sm:hidden font-medium">Σύνολο:</span>
                                €<?= number_format($service['quantity']*($service['service_price'] + $service['service_price']*$service['service_vat']) ?? 0, 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Δεν βρέθηκαν υπηρεσίες για αυτό το τιμολόγιο.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            </table>
        </div>

        <div class="flex justify-end mt-6">
            <div class="bg-gray-50 p-6 rounded-lg">
                <div class="text-right">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Συνολικό Ποσό:</label>
                    <p class="text-3xl font-bold text-green-600">€<?= number_format($invoiceDetails['total_amount'], 2); ?></p>
                </div>
            </div>
        </div>

    </div>
    
    <script>
    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Use default font for English
        doc.setFont("helvetica");

        doc.text("Invoice Details", 14, 16);
        doc.text("Invoice Code: <?= htmlspecialchars($invoiceDetails['inv_code']); ?>", 14, 22);
        doc.text("Date: <?= htmlspecialchars($invoiceDetails['invoice_date']); ?>", 14, 28);
        doc.text("Client Information:", 14, 36);    
        doc.text("Company Name: <?= htmlspecialchars($clientDetails['company_name']); ?>", 14, 42);
        doc.text("Email: <?= htmlspecialchars($clientDetails['email']); ?>", 14, 48);
        doc.text("Phone: <?= htmlspecialchars($clientDetails['phone']); ?>", 14, 54);

        const services = <?= json_encode($invoiceServices); ?>;
        const serviceData = services.map(service => [
            service.service_name ?? '', // Service
            service.description ?? '',  // Description
            service.quantity ?? '',     // Quantity
            Number(service.service_price ?? 0).toFixed(2), // Unit Price
            Number(service.service_vat ?? 0).toFixed(2),   // VAT
            Number(service.total ?? 0).toFixed(2)          // Total
        ]);

        if (typeof doc.autoTable === "function") {
            doc.autoTable({
                head: [['Service', 'Description', 'Quantity', 'Unit Price', 'VAT', 'Total']],
                body: serviceData,
                startY: 64,
            });
            doc.text("Total Amount: €<?= number_format($invoiceDetails['total_amount'], 2); ?>", 14, doc.lastAutoTable.finalY + 10);
        }
        doc.save("invoice_details.pdf");
    }

    </script>

</body>
</html>