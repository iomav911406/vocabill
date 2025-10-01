<?php

session_start();

require_once __DIR__ . '/controllers/InvoiceController.php';
require_once __DIR__ . '/controllers/ClientController.php';

// Check if the user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");  // Redirect to login page
    exit;
}

// If the user is logged in, display the dashboard content
$userId = $_SESSION["user_id"];
$invoiceController = new InvoiceController();
$clientController = new ClientController();

?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vocabill ERP - Πίνακας Ελέγχου</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            padding-top: 80px;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Inter', sans-serif;
        }
        /* Responsive πίνακας */
        @media (max-width: 768px) {
            table thead {
                display: none;
            }
            table, table tbody, table tr, table td {
                display: block;
                width: 100%;
            }
            table tr {
                margin-bottom: 15px;
            }
            table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }
            table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
            }
        }
    </style>
</head>
<body class="bg-gray-100 h-screen">
    <!-- Κουμπί Φωνητικών Εντολών -->
    <div id="voice-btn" style="position: fixed; top: 6em; right: 2em; z-index: 1000; display: flex; justify-content: center; align-items: center;">
        <button class="bg-green-400 text-white px-3 py-2 rounded-full shadow-lg hover:bg-orange-600 transition duration-300" onclick="startVoiceRecognition()">
            <i class="fas fa-microphone"></i>
        </button>
    </div>
    <!-- Modal Φωνητικών Εντολών -->
    <div id="voice-modal" style="display: none; position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background: rgba(255, 255, 255, 1); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 20px; border-radius: 8px; text-align: center; width: 500px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <p id="voice-modal-message" style="margin-bottom: 20px; font-size: 16px; color: #333;"></p>
        </div>
    </div>

    <!-- Κεφαλίδα -->
    <div x-data="{ open: false }" class="fixed top-0 z-40 w-full bg-white shadow-md">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center px-8 py-4">
        <header>
            <h1 class="text-3xl font-bold text-center md:text-left">vocabi<span class="text-blue-500">ll.</span></h1>
        </header>
        <!-- Desktop Μενού -->
        <nav class="hidden md:flex space-x-4 items-center mt-4 md:mt-0">
            <a href="dashboard.php" class="text-gray-800 hover:text-gray-900 font-bold text-base">Πίνακας Ελέγχου</a>
            <a href="invoices.php" class="text-gray-800 hover:text-gray-900 font-bold text-base">Τιμολόγια</a>
            <a href="clients.php" class="text-gray-800 hover:text-gray-900 font-bold text-base">Πελάτες</a>
            <a href="services.php" class="block py-2 text-gray-800 hover:text-gray-900 font-bold text-base">Υπηρεσίες</a>
            <button onclick="window.location.href='logout.php'" class="bg-blue-500 hover:bg-blue-600 text-gray-100 font-bold py-2 px-4 rounded text-base">Αποσύνδεση</button>
        </nav>
        <!-- Mobile Μενού -->
        <button @click="open = !open" class="md:hidden text-gray-800 absolute top-6 right-8 focus:outline-none">
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
            </svg>
            <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div x-show="open" x-transition class="md:hidden px-8 pb-4">
        <a href="dashboard.php" class="block py-2 text-gray-800 hover:text-gray-900 font-bold text-base">Πίνακας Ελέγχου</a>
        <a href="invoices.php" class="block py-2 text-gray-800 hover:text-gray-900 font-bold text-base">Τιμολόγια</a>
        <a href="clients.php" class="block py-2 text-gray-800 hover:text-gray-900 font-bold text-base">Πελάτες</a>
        <a href="services.php" class="block py-2 text-gray-800 hover:text-gray-900 font-bold text-base">Υπηρεσίες</a>
        <button onclick="window.location.href='logout.php'" class="w-full bg-blue-500 hover:bg-blue-600 text-gray-100 font-bold py-2 px-4 rounded text-base mt-2">Αποσύνδεση</button>
    </div>
    </div>

    <div class="container mx-auto py-8">
        <main>
            <div class="lg:mx-8">
                <h2 class="text-gray-800 text-2xl font-bold mb-4">Πίνακας Ελέγχου</h2>
            </div>
            <div class="flex flex-col gap-4 lg:mx-8">
                <h3 class="text-blue-500 text-xl font-bold mb-4">Βασικά Στατιστικά</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex justify-between bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Σύνολο Τιμολογίων</h3>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-gray-800 mb-2"><?php echo $invoiceController->countInvoices(); ?></p>
                            <span class="text-green-600 text-sm">▲ 5% σε σχέση με τον προηγούμενο μήνα</span>
                        </div>
                    </div>
                    <div class="flex justify-between bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Πληρωμένα Τιμολόγια</h3>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-gray-800 mb-2"><?php echo $invoiceController->countPaidInvoices(); ?></p>
                            <span class="text-green-600 text-sm">&#10003;</span>
                        </div>
                    </div>
                    <div class="flex justify-between bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Σύνολο Εισπραχθέντων</h3>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-gray-800 mb-2"><?php echo $invoiceController->totalPaidAmount(); ?></p>
                            <span class="text-green-600 text-sm">▲ 10% σε σχέση με τον προηγούμενο μήνα</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex justify-between bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Σύνολο Πελατών</h3>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-gray-800 mb-2"><?php echo $clientController->countClients(); ?></p>
                            <span class="text-green-600 text-sm">▲ 3% σε σχέση με τον προηγούμενο μήνα</span>
                        </div>
                    </div>
                    <div class="flex justify-between bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Νέοι Πελάτες</h3>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-gray-800 mb-2"><?php echo $clientController->countRecentClients(); ?></p>
                            <span class="text-green-600 text-sm">▲ 8% σε σχέση με τον προηγούμενο μήνα</span>
                        </div>
                    </div>
                    <div class="flex justify-between bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Ενεργοί Πελάτες</h3>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-gray-800 mb-2"><?php echo $clientController->countActiveClients(); ?></p>
                            <span class="text-green-600 text-sm">&#10003;</span>
                        </div>
                    </div>
                </div>
            </div>
            <h3 class="text-blue-500 text-xl font-bold mt-4 mb-8 lg:mx-8">Πρόσφατη Δραστηριότητα</h3>
            <div class="client-details bg-white rounded-lg shadow p-6 border border-gray-200 lg:mx-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-2">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <input type="text" id="activity-search" placeholder="Αναζήτηση δραστηριότητας..." class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" oninput="searchActivityTable()">
                        <select id="activity-filter" class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" onchange="filterActivityTable()">
                            <option value="">Όλες</option>
                            <option value="paid">Πληρωμένα</option>
                            <option value="overdue">Ανεξόφλητα</option>
                            <option value="pending">Σε Εκκρεμότητα</option>
                        </select>
                    </div>
                    <div class="flex gap-2 mt-2 sm:mt-0">
                        <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 text-sm" onclick="exportCSVActivityTable()">
                            <i class="fas fa-file-csv mr-1"></i> Εξαγωγή CSV
                        </button>
                        <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition duration-300 text-sm" onclick="exportExcelActivityTable()">
                            <i class="fas fa-file-excel mr-1"></i> Εξαγωγή Excel
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table id="activity-table" class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Κωδικός Τιμολογίου</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Πελάτης</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ημερομηνία Δημιουργίας</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ημερομηνία Λήξης</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Συνολικό Ποσό</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Μέθοδος Πληρωμής</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Κατάσταση</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ενέργειες</th>
                            </tr>
                        </thead>
                        <tbody id="activity-table-body" class="bg-white divide-y divide-gray-200">
                            <?php
                                $recentInvoices = $invoiceController->getRecentInvoices(5); 
                                foreach ($recentInvoices as $invoice): 
                                    $clientName = $invoiceController->showInvoiceClient($invoice['customer_id']);
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 text-sm text-gray-900"><?php echo htmlspecialchars($invoice['invoice_id']); ?></td>
                                <td class="px-3 py-2 text-sm text-gray-900"><?php echo htmlspecialchars($invoice['inv_code']); ?></td>
                                <td class="px-3 py-2 text-sm text-gray-700"><?php echo htmlspecialchars($clientName); ?></td>
                                <td class="px-3 py-2 text-sm text-gray-500"><?php echo htmlspecialchars($invoice['created_at']); ?></td>
                                <td class="px-3 py-2 text-sm text-gray-500"><?php echo htmlspecialchars($invoice['due_date']); ?></td>
                                <td class="px-3 py-2 text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($invoice['total_amount']); ?></td>
                                <td class="px-3 py-2 text-sm text-gray-500"><?php echo htmlspecialchars($invoice['payment_method']); ?></td>
                                <td class="px-3 py-2">
                                    <?php if ($invoice['status'] === 'paid'): ?>
                                        <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">Πληρωμένα</span>
                                    <?php elseif ($invoice['status'] === 'overdue'): ?>
                                        <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">Ανεξόφλητα</span>
                                    <?php else:?>
                                        <span class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">Σε εκκρεμότητα</span>
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
        </main>
    </div>

    <script>

        function startVoiceRecognition() {

            const voiceBtn = document.getElementById('voice-btn');
            // Make voice button disable while listening
            voiceBtn.disabled = true;
            const voiceModal = document.getElementById('voice-modal');
            const voiceMessage = document.getElementById('voice-modal-message');
            voiceModal.style.display = 'flex';
            voiceMessage.textContent = 'Ηχογράφηση ξεκίνησε...';

            // Start the voice recognition
            if ('webkitSpeechRecognition' in window) {
                const recognition = new webkitSpeechRecognition();
                recognition.continuous = true;
                recognition.interimResults = true;

                recognition.lang = 'el-GR'; // Set the language for recognition
                recognition.maxAlternatives = 1; // Set the maximum number of alternatives to return

                recognition.onresult = function(event) {
                    const voiceCmd = event.results[event.resultIndex][0].transcript.trim();
                    console.log("Recognized text:", voiceCmd);
                    voiceMessage.textContent = `You said: "${voiceCmd}"`;
                    if (voiceCmd.toLowerCase().includes("πίνακα ελέγχου")) {
                        window.location.href = 'dashboard.php';
                    } else if (voiceCmd.toLowerCase().includes("τιμολόγια")) {
                        window.location.href = 'invoices.php';
                    } else if (voiceCmd.toLowerCase().includes("πελάτες")) {
                        window.location.href = 'clients.php';
                    } else if (voiceCmd.toLowerCase().includes("υπηρεσίες")) {
                        window.location.href = 'services.php';  
                    } else if (voiceCmd.toLowerCase().includes("τιμολόγιο")) {
                        const searchTerm = voiceCmd.replace(/τιμολόγιο/i, "").trim();
                        document.getElementById('activity-search').value = searchTerm;
                        searchActivityTable();
                        
                    } else if (voiceCmd.toLowerCase().includes("τιμολόγια ")) {
                        const filterTerm = voiceCmd.replace(/τιμολόγια/i, "").trim();
                        let filterValue = "";
                        if (filterTerm.includes("πληρωμένα")) filterValue = "paid";
                        else if (filterTerm.includes("ανεξόφλητα")) filterValue = "overdue";
                        else if (filterTerm.includes("σε εκκρεμότητα")) filterValue = "pending";

                        document.getElementById('activity-filter').value = filterValue;
                        filterActivityTableByVoice(filterValue);
                        
                    } else if (voiceCmd.toLowerCase().includes("csv")) {
                        exportCSVActivityTable();
                    } else if (voiceCmd.toLowerCase().includes("excel")) {
                        exportExcelActivityTable();
                    } else if (voiceCmd.toLowerCase().includes("κρύψε μενού")) {
                        hideMenu();
                    } else if (voiceCmd.toLowerCase().includes("εμφάνισε μενού")) {
                        showMenu();
                    } else if (voiceCmd.toLowerCase().includes("σταμάτα την ηχογράφηση")) {
                        recognition.stop(); // Stop the recognition
                        voiceMessage.textContent = 'Ηχογράφηση σταμάτησε.';
                        voiceBtn.disabled = false; // Re-enable the voice button
                        voiceModal.style.display = 'none';
                    } else {
                        voiceMessage.textContent = `Command not recognized: "${voiceCmd}"`;
                    }
                };
                recognition.onerror = function(event) {
                    console.error("Error occurred in recognition:", event.error);
                    voiceMessage.textContent = 'Συνέβη λάθος. Παρακαλώ προσπαθήστε ξανά';
                };
                recognition.onend = function() {
                    voiceMessage.textContent = 'Ηχογράφηση σταμάτησε.';
                    setTimeout(() => {
                        voiceModal.style.display = 'none';
                    }, 2000);
                };
                recognition.start();
                // Set timeout to stop recognition after 10 minutes
                setTimeout(function() {
                    recognition.stop();
                    voiceBtn.disabled = false; // Re-enable the voice button
                    voiceMessage.textContent = 'Voice recognition stopped after timeout.';
                    console.log("Voice recognition stopped after timeout.");
                }, 10 * 60 * 1000); // 10 minutes
            } else {
                console.warn("Web Speech API is not supported in this browser.");
                voiceMessage.textContent = 'Voice recognition is not supported in this browser.';
            }
        }

        // Function to search the recent activity table
        function searchActivityTable() {
            const input = document.getElementById('activity-search');
            const search = input.value.toUpperCase();
            const table = document.getElementById('activity-table');
            const rows = table.getElementsByTagName('tr');
            for (let i = 1; i < rows.length; i++) {
                let found = false;
                const cells = rows[i].getElementsByTagName('td');
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell) {
                        const textValue = cell.textContent || cell.innerText;
                        if (textValue.toUpperCase().indexOf(search) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                rows[i].style.display = found ? '' : 'none';
            }
        }

        function searchActivityTableByVoice(searchTerm) {
            const search = searchTerm.toUpperCase();
            const table = document.getElementById('invoices-table');
            const rows = table.getElementsByTagName('tr');
            for (let i = 1; i < rows.length; i++) {
                let found = false;
                const cells = rows[i].getElementsByTagName('td');
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell) {
                        const textValue = cell.textContent || cell.innerText;
                        if (textValue.toUpperCase().indexOf(search) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                rows[i].style.display = found ? '' : 'none';
                break; // Only search the first match
            }
        }

        function filterActivityTableByVoice(filterValue) {
            console.log("Filter value from voice is: ", filterValue);

            let filterValueGreek = "";
            if (filterValue === "paid") filterValueGreek = "πληρωμένα";
            if (filterValue === "overdue") filterValueGreek = "ανεξόφλητα";
            if (filterValue === "pending") filterValueGreek = "σε εκκρεμότητα";

            const tableBody = document.getElementById('activity-table-body');
            const rows = tableBody.getElementsByTagName('tr');
            
            // Loop through all table rows (skip header row)
            for (let i = 0; i < rows.length; i++) {
                const statusCell = rows[i].getElementsByTagName('td')[7];
                console.log("Status Cell is ", statusCell.innerText) // Assuming status is the 8th column
                if (statusCell) {
                    const statusText = statusCell.innerText.toLowerCase();
                    console.log("Status text is ", statusText);
                    if (filterValue === "") {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = statusText.includes(filterValueGreek) ? '' : 'none';
                    }
                }
            }
        }

        function filterActivityTable() {
            const filterValue = document.getElementById('activity-filter').value;
            console.log("filterValue is: ", filterValue);
            
            let filterValueGreek = "";
            if (filterValue === "paid") filterValueGreek = "πληρωμένα";
            if (filterValue === "overdue") filterValueGreek = "ανεξόφλητα";
            if (filterValue === "pending") filterValueGreek = "σε εκκρεμότητα";
            
            const tableBody = document.getElementById('activity-table-body');
            const rows = tableBody.getElementsByTagName('tr');
            
            // Loop through all table rows (skip header row)
            for (let i = 0; i < rows.length; i++) {
                const statusCell = rows[i].getElementsByTagName('td')[7];
                console.log("Status Cell is ", statusCell.innerText) // Assuming status is the 8th column
                if (statusCell) {
                    const statusText = statusCell.innerText.toLowerCase();
                    console.log("Status text is ", statusText);

                    if (filterValue === "") {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = statusText.includes(filterValueGreek) ? '' : 'none';
                    }
                }
            }
        }

        function exportCSVActivityTable() {
            const activityTable = document.getElementById('activity-table'); // Select the table
            const rows = Array.from(activityTable.rows); // Get all rows in the table
            let csvContent = '';

            rows.forEach(row => {
                const cols = Array.from(row.cells).map(cell => cell.innerText); // Get all cell values in the row
                csvContent += cols.join(',') + '\n'; // Join cell values with commas and add a new line
            });

            // Create a link element
            const link = document.createElement('a');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'data.csv'); // Set the file name

            // Append link to the body
            document.body.appendChild(link);
            link.click(); // Trigger the download
            document.body.removeChild(link); // Remove the link after download
        }

        function exportExcelActivityTable(filename = 'data.xlsx') {
            // Get the table element
            const activityTable = document.getElementById('activity-table');

            // Get the tbody and its rows
            const tbody = activityTable.querySelector('tbody');
            const rows = tbody ? Array.from(tbody.rows) : [];

            // Extract data from rows
            const data = rows.map(row => 
                Array.from(row.cells).map(cell => cell.innerText.trim())
            );

            console.log("Body data going to Excel:", data);

            // Create a worksheet from the body data
            const worksheet = XLSX.utils.aoa_to_sheet(data);

            // Create a new workbook and append the sheet
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet 1");

            // Export to file
            XLSX.writeFile(workbook, filename);

        }
    </script>
</body>
</html>