<?php

session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");  // Redirect to login page
    exit;
}

// If the user is logged in, display the dashboard content
$userId = $_SESSION["user_id"];

require_once __DIR__ . '/controllers/InvoiceController.php';
require_once __DIR__ . '/controllers/InvoiceServicesController.php';


$invoiceServicesController = new InvoiceServicesController();

$action = $_GET['action'] ?? 'list';
$invoiceId = $_GET['invoice_id'] ?? null;
$invoiceCode = $_GET['inv_code'] ?? null;

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoiceServicesController->createInvoiceService($_POST);
    exit;
}
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoiceServicesController->updateInvoiceService($_POST['id'], $_POST);
    exit;
}
if ($action === 'delete') {
    $invoiceServicesController->deleteInvoiceService($_GET['id']);
    exit;
}


?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vocabill ERP - Τιμολόγια</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
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
    <!-- Voice Button in absolute position -->
    <div id="voice-btn" style="position: fixed; top: 8em; right: 2em; z-index: 1000; display: flex; justify-content: center; align-items: center;">
        <button class="bg-green-400 text-white px-3 py-2 rounded-full shadow-lg hover:bg-orange-600 transition duration-300" onclick="startVoiceRecognition()">
            <i class="fas fa-microphone"></i>
        </button>
    </div>
    <!-- Voice Command Modal -->
    <div id="voice-modal" style="display: none; position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background: rgba(255, 255, 255, 1); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 20px; border-radius: 8px; text-align: center; width: 500px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <p id="voice-modal-message" style="margin-bottom: 20px; font-size: 16px; color: #333;"></p>
        </div>
    </div>
    <!-- Header Div -->
    <div x-data="{ open: false }" class="fixed top-0 z-40 w-full bg-white shadow-md">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center px-8 py-4">
        <header>
            <h1 class="text-3xl font-bold text-center md:text-left">vocabi<span class="text-blue-500">ll.</span></h1>
        </header>
        <!-- Desktop Nav -->
        <nav class="hidden md:flex space-x-4 items-center mt-4 md:mt-0">
            <a href="dashboard.php" class="text-gray-800 hover:text-gray-900 font-bold text-base">Πίνακας Ελέγχου</a>
            <a href="invoices.php" class="text-gray-800 hover:text-gray-900 font-bold text-base">Τιμολόγια</a>
            <a href="clients.php" class="text-gray-800 hover:text-gray-900 font-bold text-base">Πελάτες</a>
            <a href="services.php" class="block py-2 text-gray-800 hover:text-gray-900 font-bold text-base">Υπηρεσίες</a>
            <button onclick="window.location.href='logout.php'" class="bg-blue-500 hover:bg-blue-600 text-gray-100 font-bold py-2 px-4 rounded text-base">Αποσύνδεση</button>
        </nav>
        <!-- Mobile Hamburger -->
        <button @click="open = !open" class="md:hidden text-gray-800 absolute top-6 right-8 focus:outline-none">
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
            </svg>
            <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <!-- Mobile Nav -->
    <div x-show="open" x-transition class="md:hidden px-8 pb-4">
        <a href="dashboard.php" class="block py-2 text-gray-800 hover:text-gray-900 font-bold text-base">Πίνακας Ελέγχου</a>
        <a href="invoices.php" class="block py-2 text-gray-800 hover:text-gray-900 font-bold text-base">Τιμολόγια</a>
        <a href="clients.php" class="block py-2 text-gray-800 hover:text-gray-900 font-bold text-base">Πελάτες</a>
        <a href="services.php" class="block py-2 text-gray-800 hover:text-gray-900 font-bold text-base">Υπηρεσίες</a>
        <button onclick="window.location.href='logout.php'" class="w-full bg-blue-500 hover:bg-blue-600 text-gray-100 font-bold py-2 px-4 rounded text-base mt-2">Αποσύνδεση</button>
    </div>
    </div>
    <div class="container mx-auto py-8">
        <main class="mt-8">
            <?php
            switch ($action) {
                
                case 'list':
                    $invoiceServicesController->listInvoiceServices();
                    break;
                case 'show':
                    $invoiceServicesController->showInvoiceServices($invoiceId);
                    break;
                case 'create':
                    $invoiceServicesController->createInvoiceServiceForm();
                    break;
                case 'edit':
                    $invoiceServicesController->editInvoiceServicesForm($invoiceId);
                    break;
                case 'delete':
                    $invoiceServicesController->deleteInvoiceServices($invoiceId);
                    break;
                default:
                    $invoiceServicesController->listInvoiceServices();
                    break;
            }
            ?>
        </main>
    </div>

    <script>
        function startVoiceRecognition() {
            const voiceBtn = document.getElementById('voice-btn');
            voiceBtn.disabled = true;
            const voiceModal = document.getElementById('voice-modal');
            const voiceMessage = document.getElementById('voice-modal-message');
            voiceModal.style.display = 'flex';
            voiceMessage.textContent = 'Ηχογράφηση ξεκίνησε...';

            if ('webkitSpeechRecognition' in window) {
                const recognition = new webkitSpeechRecognition();
                recognition.continuous = true;
                recognition.interimResults = true;
                recognition.lang = 'el-GR';
                recognition.maxAlternatives = 1;

                recognition.onresult = function(event) {
                    const voiceBtn = event.results[event.resultIndex][0].transcript.trim();
                    voiceMessage.textContent = `You said: "${voiceBtn}"`;
                    if (voiceBtn.toLowerCase().includes("πήγαινε πίνακα ελέγχου")) {
                        window.location.href = 'dashboard.php';
                    } else if (voiceBtn.toLowerCase().includes("πήγαινε τιμολόγια")) {
                        window.location.href = 'invoices.php';
                    } else if (voiceBtn.toLowerCase().includes("εισαγωγή")) {
                        fillInvoiceServicesFormByVoice(voiceBtn);
                    } else if (voiceBtn.toLowerCase().includes("πήγαινε υπηρεσίες")) {
                        window.location.href = 'services.php';
                    } else if (voiceBtn.toLowerCase().includes("αποσύνδεση")) {
                        window.location.href = 'logout.php';
                    } else if (voiceBtn.toLowerCase().includes("αναζήτηση τιμολογίων")) {
                        const searchInput = document.getElementById('invoices-search');
                        if (searchInput) {
                            const searchTerm = voiceBtn.toLowerCase().replace("αναζήτηση τιμολογίων", "").trim();
                            searchInput.value = searchTerm;
                            searchInvoicesTableByVoice(searchTerm);
                        }
                    } else if (voiceBtn.toLowerCase().includes("εισαγωγή ")) {
                        fillInvoiceServicesFormByVoice(voiceBtn);
                    } else if (voiceBtn.toLowerCase().includes("αποθήκευση")) {
                        const addBtn = document.querySelector('button[type="submit"]');
                        if (addBtn) {
                            addBtn.click();
                        }

                    } else if (voiceCmd.toLowerCase().includes("πίσω")) {
                        window.history.back();
                    } else if (voiceBtn.toLowerCase().includes("φίλτρο")) {
                        const filterTerm = voiceBtn.toLowerCase().replace("φίλτρο", "").trim();
                        let filterValue = "";
                        if (filterTerm.includes("1")) filterValue = "paid";
                        if (filterTerm.includes("2")) filterValue = "overdue";
                        if (filterTerm.includes("3")) filterValue = "pending";
                        document.getElementById('invoices-filter').value = filterValue;
                        filterActivityTableByVoice(filterValue);
                    } else if (voiceBtn.toLowerCase().includes("excel")) {
                        exportExcelInvoicesTable();
                    } else if (voiceBtn.toLowerCase().includes("csv")) {
                        exportCSVInvoicesTable();

                    } else if (voiceBtn.toLowerCase().includes("κρύψε μενού")) {
                        hideMenu();
                    } else if (voiceBtn.toLowerCase().includes("εμφάνισε μενού")) {
                        showMenu();
                    } else if (voiceBtn.toLowerCase().includes("σταμάτα την ηχογράφηση")) {
                        recognition.stop();
                        voiceMessage.textContent = 'Ηχογράφηση σταμάτησε.';
                        voiceBtn.disabled = false;
                        voiceModal.style.display = 'none';
                    } else {
                        voiceMessage.textContent = `Command not recognized: "${voiceBtn}"`;
                    }
                };
                recognition.onerror = function(event) {
                    voiceMessage.textContent = 'Συνέβη λάθος. Παρακαλώ προσπαθήστε ξανά.';
                };
                recognition.onend = function() {
                    voiceMessage.textContent = 'Voice recognition stopped.';
                    setTimeout(() => {
                        voiceModal.style.display = 'none';
                    }, 2000);
                };
                recognition.start();
                setTimeout(function() {
                    recognition.stop();
                    voiceBtn.disabled = false;
                    voiceMessage.textContent = 'Voice recognition stopped after timeout.';
                }, 10 * 60 * 1000);
            } else {
                voiceMessage.textContent = 'Voice recognition is not supported in this browser.';
            }
        }

        function selectServiceById(serviceId) {
            const serviceSelect = document.querySelector('select[name="service_id[]"]');
            if (serviceSelect) {
                for (let i = 0; i < serviceSelect.options.length; i++) {
                    if (serviceSelect.options[i].value === serviceId) {
                        serviceSelect.selectedIndex = i;
                        break;
                    }
                }
            }
        }

        function selectQuantityByValue(quantity) {
            const quantityInput = document.querySelector('input[name="quantity[]"]');
            if (quantityInput) quantityInput.value = quantity;
        }

        function fillInvoiceServicesFormByVoice(voiceCmd) {
            // Παράδειγμα: "εισαγωγή υπηρεσία 2 ποσότητα 5 και υπηρεσία 3 ποσότητα 2"
            const cmd = voiceCmd.toLowerCase();

            // Βρες όλα τα ζεύγη "υπηρεσία X ποσότητα Y"
            const serviceRegex = /υπηρεσία\s*(\d+)\s*ποσότητα\s*(\d+)/g;
            let match;
            let rowIndex = 0;

            // Πόσα rows υπάρχουν ήδη;
            let serviceSelects = document.querySelectorAll('select[name="service_id[]"]');
            let quantityInputs = document.querySelectorAll('input[name="quantity[]"]');

            // Για κάθε ζεύγος υπηρεσίας/ποσότητας
            while ((match = serviceRegex.exec(cmd)) !== null) {
                const serviceId = match[1];
                const quantity = match[2];

                // Αν δεν υπάρχει αρκετό row, πρόσθεσέ το
                if (rowIndex >= serviceSelects.length) {
                    if (typeof addService === "function") addService();
                    // Ξαναβρες τα στοιχεία μετά την προσθήκη
                    serviceSelects = document.querySelectorAll('select[name="service_id[]"]');
                    quantityInputs = document.querySelectorAll('input[name="quantity[]"]');
                }

                // Συμπλήρωσε τα πεδία στην τελευταία σειρά που προστέθηκε
                const serviceSelect = serviceSelects[rowIndex];
                const quantityInput = quantityInputs[rowIndex];

                if (serviceSelect) {
                    for (let i = 0; i < serviceSelect.options.length; i++) {
                        if (serviceSelect.options[i].value === serviceId) {
                            serviceSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
                if (quantityInput) {
                    quantityInput.value = quantity;
                }

                rowIndex++;
            }
        }


        function searchInvoicesTable() {
            const input = document.getElementById('invoices-search');
            const search = input.value.toUpperCase();
            const table = document.getElementById('invoices-table');
            const rows = table.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
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


        function searchInvoicesTable() {
            const input = document.getElementById('invoices-search');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('invoices-table');
            const rows = table.getElementsByTagName('tr');
            for (let i = 1; i < rows.length; i++) {
                let found = false;
                const cells = rows[i].getElementsByTagName('td');
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell) {
                        const textValue = cell.textContent || cell.innerText;
                        if (textValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                rows[i].style.display = found ? '' : 'none';
            }
        }

        function searchInvoicesTableByVoice(filterValue) {
            const filter = filterValue.toUpperCase();
            const table = document.getElementById('invoices-table');
            const rows = table.getElementsByTagName('tr');
            for (let i = 1; i < rows.length; i++) {
                let found = false;
                const cells = rows[i].getElementsByTagName('td');
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell) {
                        const textValue = cell.textContent || cell.innerText;
                        if (textValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                rows[i].style.display = found ? '' : 'none';
            }
        }

        function filterActivityTableByVoice(filterValue) {
            console.log("Filter value from voice is: ", filterValue);

            let filterValueGreek = "";
            if (filterValue === "paid") filterValueGreek = "πληρωμένα";
            if (filterValue === "overdue") filterValueGreek = "ανεξόφλητα";
            if (filterValue === "pending") filterValueGreek = "σε εκκρεμότητα";

            const tableBody = document.getElementById('invoices-table-body');
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
            const filterValue = document.getElementById('invoices-filter').value;
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

        function exportCSVInvoicesTable() {
            const invoicesTable = document.getElementById('invoices-table');
            const rows = Array.from(invoicesTable.rows);
            let csvContent = '';
            rows.forEach(row => {
                const cols = Array.from(row.cells).map(cell => cell.innerText);
                csvContent += cols.join(',') + '\n';
            });
            const link = document.createElement('a');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'data.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function exportExcelInvoicesTable(filename = 'data.xlsx') {
            const invoicesTable = document.getElementById('invoices-table');
            const tbody = invoicesTable.querySelector('tbody');
            const rows = tbody ? Array.from(tbody.rows) : [];
            const data = rows.map(row => 
                Array.from(row.cells).map(cell => cell.innerText.trim())
            );
            const worksheet = XLSX.utils.aoa_to_sheet(data);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet 1");
            XLSX.writeFile(workbook, filename);
        }
    </script>

    
</body>
</html>