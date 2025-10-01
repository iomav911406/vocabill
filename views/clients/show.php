<?php 

require_once __DIR__ . '/../../controllers/ClientController.php';
$clientController = new ClientController();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Details - VocaBill</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .client-header {
            background-color: #f3f4f6;
            padding-top: 2em;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .client-details {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-active {
            background-color: #d1fae5;
            color: #047857;
        }
        .status-inactive {
            background-color: #fee2e2;
            color: #dc2626;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 sm:p-6">
        <div class="client-header space-y-4">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">Client Details</h1>
                    <p class="text-sm sm:text-base text-gray-600">Client ID: <strong><?= htmlspecialchars($client['customer_id']); ?></strong></p>
                    <p class="text-sm sm:text-base text-gray-600">Client Code: <strong><?= htmlspecialchars($client['customer_code']); ?></strong></p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                    <button onclick="window.location.href='clients.php'" class="w-full sm:w-auto px-4 py-2 bg-gray-800 text-gray-100 rounded hover:bg-gray-900 transition duration-300 text-sm sm:text-base">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </button>
                    <button onclick="window.location.href='clients.php?action=edit&id=<?= $client['customer_id']; ?>'" class="w-full sm:w-auto px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 text-sm sm:text-base">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </button>
                </div>
            </div>
        </div>

        <div class="client-details space-y-4 sm:space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-4">Company Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Company Name:</label>
                            <p class="text-gray-800 font-medium"><?= htmlspecialchars($client['company_name']); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Contact Name:</label>
                            <p class="text-gray-800"><?= htmlspecialchars($client['contact_name'] ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Status:</label>
                            <span class="status-badge <?= $client['status'] == 'active' ? 'status-active' : 'status-inactive'; ?>">
                                <?= ucfirst(htmlspecialchars($client['status'])); ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-4">Contact Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email:</label>
                            <p class="text-gray-800">
                                <a href="mailto:<?= htmlspecialchars($client['email']); ?>" class="text-blue-600 hover:text-blue-800 underline">
                                    <?= htmlspecialchars($client['email']); ?>
                                </a>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Phone:</label>
                            <p class="text-gray-800">
                                <?php if($client['phone']): ?>
                                    <a href="tel:<?= htmlspecialchars($client['phone']); ?>" class="text-blue-600 hover:text-blue-800 underline">
                                        <?= htmlspecialchars($client['phone']); ?>
                                    </a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Member Since:</label>
                            <p class="text-gray-800"><?= htmlspecialchars($client['created_at']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Client Invoices Section -->
            <div class="border-t pt-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0 mb-4">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-700">Client Invoices (<?= count($clientInvoices); ?>)</h3>
                    <button onclick="window.location.href='invoices.php?action=create&customer_id=<?= $client['customer_id']; ?>'" class="w-full sm:w-auto px-3 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition duration-300">
                        <i class="fas fa-plus mr-1"></i>Create Invoice
                    </button>
                </div>
                
                <?php if (!empty($clientInvoices)): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50 hidden sm:table-header-group">
                                <tr>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice Code</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Date</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Due Date</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Payment Method</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($clientInvoices as $invoice): ?>
                                    <tr class="hover:bg-gray-50 sm:table-row flex flex-col sm:flex-row border-b sm:border-0 py-2 sm:py-0">
                                        <td class="px-3 sm:px-6 py-2 sm:py-4 text-sm font-medium text-gray-900 flex justify-between sm:table-cell">
                                            <span class="font-medium sm:hidden">Invoice:</span>
                                            <?= htmlspecialchars($invoice['inv_code']); ?>
                                        </td>
                                        <td class="px-3 sm:px-6 py-2 sm:py-4 text-sm text-gray-500 hidden md:table-cell">
                                            <?= htmlspecialchars($invoice['invoice_date']); ?>
                                        </td>
                                        <td class="px-3 sm:px-6 py-2 sm:py-4 text-sm text-gray-500 hidden lg:table-cell">
                                            <?= htmlspecialchars($invoice['due_date']); ?>
                                        </td>
                                        <td class="px-3 sm:px-6 py-2 sm:py-4 text-sm font-semibold text-gray-900 flex justify-between sm:table-cell">
                                            <span class="font-medium sm:hidden">Amount:</span>
                                            $<?= number_format($invoice['total_amount'], 2); ?>
                                        </td>
                                        <td class="px-3 sm:px-6 py-2 sm:py-4 text-sm text-gray-500 hidden md:table-cell">
                                            <?= htmlspecialchars($invoice['payment_method']); ?>
                                        </td>
                                        <td class="px-3 sm:px-6 py-2 sm:py-4 flex justify-between sm:table-cell">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                <?php 
                                                switch($invoice['status']) {
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
                                                <?= ucfirst(htmlspecialchars($invoice['status'])); ?>
                                            </span>
                                        </td>
                                        <td class="px-3 sm:px-6 py-2 sm:py-4 text-sm text-gray-500 flex justify-between sm:table-cell">
                                            <span class="font-medium sm:hidden">Actions:</span>
                                            <div class="flex space-x-2">
                                                <a href="invoices.php?action=show&inv_code=<?= $invoice['inv_code']; ?>" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="invoices.php?action=edit&id=<?= $invoice['inv_code']; ?>" class="text-gray-600 hover:text-gray-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-4xl mb-2">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <p class="text-gray-500 text-lg mb-2">No invoices found</p>
                        <p class="text-gray-400 text-sm mb-4">This client hasn't been invoiced yet.</p>
                        <button onclick="window.location.href='invoices.php?action=create&customer_id=<?= $client['customer_id']; ?>'" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                            Create First Invoice
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Client Activity Summary -->
            <div class="border-t pt-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Activity Summary</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-600"><?= count($clientInvoices); ?></div>
                            <div class="text-sm text-gray-600">Total Invoices</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">
                                $<?= number_format(array_sum(array_column($clientInvoices, 'total_amount')), 2); ?>
                            </div>
                            <div class="text-sm text-gray-600">Total Billed</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">
                                <?= count(array_filter($clientInvoices, function($inv) { return $inv['status'] === 'paid'; })); ?>
                            </div>
                            <div class="text-sm text-gray-600">Paid Invoices</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-red-600">
                                <?= count(array_filter($clientInvoices, function($inv) { return $inv['status'] === 'overdue'; })); ?>
                            </div>
                            <div class="text-sm text-gray-600">Overdue</div>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mt-4">Client since: <?= htmlspecialchars($client['created_at']); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
