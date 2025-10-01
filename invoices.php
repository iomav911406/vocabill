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
require_once __DIR__ . '/controllers/ClientController.php';
require_once __DIR__ . '/controllers/ServiceController.php';

$invoiceController = new InvoiceController();

$action = $_GET['action'] ?? 'list';
$invoiceId = $_GET['invoice_id'] ?? null;
$invoiceCode = $_GET['inv_code'] ?? null;
$errorMessage = "";  // Initialize error message

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    //var_dump($_POST);
    $invoiceController->createInvoice($_POST);
    exit;
}
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoiceController->updateInvoice($invoiceCode, $_POST);
    exit;
}
if ($action === 'delete') {
    $invoiceController->deleteInvoice($invoiceCode);
    exit;
}
if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoiceController->addServices($invoiceId, $_POST);
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
                    $invoiceController->listInvoices();
                    break;
                case 'show':
                    $invoiceController->showInvoice($invoiceCode);
                    break;
                case 'create':
                    $invoiceController->createInvoiceForm();
                    break;
                case 'edit':
                    $invoiceController->editInvoiceForm($invoiceCode);
                    break;
                case 'delete':
                    $invoiceController->deleteInvoice($invoiceCode);
                    break;
                default:
                    $invoiceController->listInvoices();
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
                recognition.interimResults = false;
                recognition.lang = 'el-GR';
                recognition.maxAlternatives = 1;

                recognition.onresult = function(event) {
                    const voiceCmd = event.results[event.resultIndex][0].transcript.trim();
                    console.log("Recognized text:", voiceCmd);
                    voiceMessage.textContent = `You said: "${voiceCmd}"`;
                    if (voiceCmd.toLowerCase().includes("πίνακα ελέγχου") || voiceCmd.toLowerCase().includes("πίνακας ελέγχου") 
                       || voiceCmd.toLowerCase().includes("πήγαινε πίνακα ελέγχου")) {
                        window.location.href = 'dashboard.php';
                    } else if (voiceCmd.toLowerCase().includes("παραστατικά") 
                        || voiceCmd.toLowerCase().includes("πήγαινε τιμολόγια") || voiceCmd.toLowerCase().includes("πήγαινε παραστατικά")
                        || voiceCmd.toLowerCase().includes("δείξε τιμολόγια") || voiceCmd.toLowerCase().includes("δείξε παραστατικά")
                        || voiceCmd.toLowerCase().includes("άνοιξε τιμολόγια") || voiceCmd.toLowerCase().includes("άνοιξε παραστατικά")) {
                        window.location.href = 'invoices.php';
                    } else if (voiceCmd.toLowerCase().includes("πελάτες") || voiceCmd.toLowerCase().includes("πήγαινε πελάτες") 
                        || voiceCmd.toLowerCase().includes("δείξε πελάτες") || voiceCmd.toLowerCase().includes("άνοιξε πελάτες")) {
                        window.location.href = 'clients.php';
                    } else if (voiceCmd.toLowerCase().includes("υπηρεσίες") || voiceCmd.toLowerCase().includes("πήγαινε υπηρεσίες")
                        || voiceCmd.toLowerCase().includes("δείξε υπηρεσίες") || voiceCmd.toLowerCase().includes("άνοιξε υπηρεσίες")) {
                        window.location.href = 'services.php';  
                    } else if (voiceCmd.toLowerCase().includes("τιμολόγιο") || voiceCmd.toLowerCase().includes("βρες τιμολόγιο ")
                        || voiceCmd.toLowerCase().includes("αναζήτηση τιμολογίου")) {
                        const searchTerm = voiceCmd.replace(/τιμολόγιο/i, "").trim();
                        document.getElementById('invoices-search').value = searchTerm;
                        searchInvoicesTableByVoice(searchTerm);
                    } else if (voiceCmd.toLowerCase() === "νέο παραστατικό" || voiceCmd.toLowerCase() === "δημιουργία νέου τιμολογίου") {
                        window.location.href = 'invoices.php?action=create';
                    } else if (voiceCmd.toLowerCase() === "αποθήκευση" || voiceCmd.toLowerCase() === "αποθήκευσε") {
                        const saveBtn = document.querySelector('button[type="submit"]');
                        if (saveBtn) {
                            saveBtn.click();
                        } else {
                            voiceMessage.textContent = 'Το κουμπί αποθήκευσης δεν βρέθηκε στη φόρμα!';
                        }

                    } else if (voiceCmd.toLowerCase().includes("πίσω")) {
                        window.history.back();
                    } else if (voiceCmd.toLowerCase().includes("δημιουργία τιμολογίου") 
                        || voiceCmd.toLowerCase().includes("δημιούργησε τιμολόγιο")) {
                        fillInvoiceFormByVoice(voiceCmd);
                    } else if (voiceCmd.toLowerCase().includes("εισαγωγή") || voiceCmd.toLowerCase().includes("πρόσθεσε")
                        || voiceCmd.toLowerCase().includes("πρόσθεσε υπηρεσίες") || voiceCmd.toLowerCase().includes("εισαγωγή υπηρεσιών")) {
                        fillInvoiceServicesFormByVoice(voiceCmd);
                    } else if (voiceCmd.toLowerCase().includes("τιμολόγια")) {
                        const filterTerm = voiceCmd.replace(/τιμολόγια/i, "").trim();
                        let filterValue = "";
                        if (filterTerm.includes("1")) filterValue = "paid";
                        else if (filterTerm.includes("2")) filterValue = "overdue";
                        else if (filterTerm.includes("3")) filterValue = "pending";
                        else if (filterTerm.includes("0")) filterValue = "";
                        document.getElementById('invoices-filter').value = filterValue;
                        filterInvoicesTableByVoice(filterValue);
                    } else if (voiceCmd.toLowerCase().includes("csv") || voiceCmd.toLowerCase().includes("εξαγωγή σε csv")) {
                        exportCSVInvoicesTable();
                    } else if (voiceCmd.toLowerCase().includes("excel") || voiceCmd.toLowerCase().includes("εξαγωγή σε excel")) {
                        exportExcelInvoicesTable();
                    } else if (voiceCmd.toLowerCase().includes("κρύψε μενού")) {
                        hideMenu();
                    } else if (voiceCmd.toLowerCase().includes("εμφάνισε μενού")) {
                        showMenu();
                    } else if (voiceCmd.toLowerCase().includes("σταμάτα την ηχογράφηση")) {
                        recognition.stop();
                        voiceMessage.textContent = 'Ηχογράφηση σταμάτησε.';
                        voiceBtn.disabled = false;
                        voiceModal.style.display = 'none';
                    } else if (voiceCmd.toLowerCase() === "ξανά") {
                        voiceMessage.textContent = 'Command not recognized: "${voiceCmd}"';
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

        function fillInvoiceFormByVoice(voiceCmd) {
            // Παράδειγμα: "δημιουργία τιμολογίου πελάτης 1 σήμερα ένας μήνας μετρητά πληρωμένο"
            cmd = voiceCmd.toLowerCase();

            if (cmd === "αλλαγή") {
                return; // Exit if the command does not include "δημιουργία τιμολογίου"
            }

            // Πελάτης
            const clientMatch = cmd.match(/πελάτης\s+(\d+)/);
            if (clientMatch) {
                const clientId = clientMatch[1];
                const clientSelect = document.getElementById('customer_id');
                if (clientSelect) {
                    for (let i = 0; i < clientSelect.options.length; i++) {
                        if (clientSelect.options[i].value === clientId) {
                            clientSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
            }

            // Ημερομηνία δημιουργίας
            if (cmd.includes("σήμερα")) {
                const today = new Date();
                const day = String(today.getDate()).padStart(2, '0');
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const year = today.getFullYear();
                const createdAt = document.getElementById('invoice_date');
                if (createdAt) createdAt.value = `${year}-${month}-${day}`;
            }

            // Ημερομηνία λήξης
            if (cmd.includes("ένας μήνας")) {
                const oneMonthLater = new Date();
                oneMonthLater.setMonth(oneMonthLater.getMonth() + 1);
                const day = String(oneMonthLater.getDate()).padStart(2, '0');
                const month = String(oneMonthLater.getMonth() + 1).padStart(2, '0');
                const year = oneMonthLater.getFullYear();
                const dueDate = document.getElementById('due_date');
                if (dueDate) dueDate.value = `${year}-${month}-${day}`;
            } else if (cmd.includes("μισός μήνας")) {
                const fifteenDaysLater = new Date();
                fifteenDaysLater.setDate(fifteenDaysLater.getDate() + 15);
                const day = String(fifteenDaysLater.getDate()).padStart(2, '0');
                const month = String(fifteenDaysLater.getMonth() + 1).padStart(2, '0');
                const year = fifteenDaysLater.getFullYear();
                const dueDate = document.getElementById('due_date');
                if (dueDate) dueDate.value = `${year}-${month}-${day}`;
            } else if (cmd.includes("2 μήνες")) {
                const twoMonthsLater = new Date();
                twoMonthsLater.setMonth(twoMonthsLater.getMonth() + 2);
                const day = String(twoMonthsLater.getDate()).padStart(2, '0');
                const month = String(twoMonthsLater.getMonth() + 1).padStart(2, '0');
                const year = twoMonthsLater.getFullYear();
                const dueDate = document.getElementById('due_date');
                if (dueDate) dueDate.value = `${year}-${month}-${day}`;
            }

            // Mέθοδος πληρωμής
            if (cmd.includes("μετρητά")) {
                const paymentSelect = document.getElementById('payment_method');
                if (paymentSelect) {
                    for (let i = 0; i < paymentSelect.options.length; i++) {
                        if (paymentSelect.options[i].text.toLowerCase().includes("μετρητά")) {
                            paymentSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
            } else if (cmd.includes("κάρτα")) {
                const paymentSelect = document.getElementById('payment_method');
                if (paymentSelect) {
                    for (let i = 0; i < paymentSelect.options.length; i++) {
                        if (paymentSelect.options[i].text.toLowerCase().includes("χρεωστική κάρτα")) {
                            paymentSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
            } else if (cmd.includes("τράπεζα")) {
                const paymentSelect = document.getElementById('payment_method');
                if (paymentSelect) {
                    for (let i = 0; i < paymentSelect.options.length; i++) {
                        if (paymentSelect.options[i].text.toLowerCase().includes("τραπεζική μεταφορά")) {
                            paymentSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
            }

            // Κατάσταση
            if (cmd.includes("πληρωμένο")) {
                const statusSelect = document.getElementById('status');
                if (statusSelect) {
                    for (let i = 0; i < statusSelect.options.length; i++) {
                        if (statusSelect.options[i].text.toLowerCase().includes("πληρωμένο")) {
                            statusSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
            } else if (cmd.includes("ανεξόφλητο")) {
                const statusSelect = document.getElementById('status');
                if (statusSelect) {
                    for (let i = 0; i < statusSelect.options.length; i++) {
                        if (statusSelect.options[i].text.toLowerCase().includes("ανεξόφλητο")) {
                            statusSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
            } else if (cmd.includes("εκκρεμή")) {
                const statusSelect = document.getElementById('status');
                if (statusSelect) {
                    for (let i = 0; i < statusSelect.options.length; i++) {
                        if (statusSelect.options[i].text.toLowerCase().includes("σε εκκρεμότητα")) {
                            statusSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
            }

            if (cmd.includes("αποθήκευση")) {
                const saveBtn = document.querySelector('button[type="submit"]');
                if (saveBtn) {
                    saveBtn.click();
                }
            }

        }

        function searchInvoicesTable() {
            const search = document.getElementById('invoices-search').value.toUpperCase();
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

        function searchInvoicesTableByVoice(searchTerm) {
            const search = searchTerm.toUpperCase();
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

        function filterInvoicesTableByVoice(filterValue) {
            console.log("Filter value from voice is: ", filterValue);

            let filterValueGreek = "";
            if (filterValue === "") filterValueGreek = "όλα";
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

        function filterInvoicesTable() {
            const filterValue = document.getElementById('invoices-filter').value;
            console.log("filterValue is: ", filterValue);
            
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

        function selectClientByVoice(clientSelect, client) {
            const options = clientSelect.options;
            
            let found = false;
            for (let i = 0; i < options.length; i++) {
                console.log(`Checking option "${options[i].text}" with value "${options[i].value}" against client "${client}"`);
                if (client === options[i].value) {
                    console.log(`Selecting client "${options[i].text}" by index ${i}`);
                    clientSelect.selectedIndex = i;
                    found = true;
                    break;
                }
            }
            if (!found) {
                console.log(`Client "${client}" not found.`);
            }
        }

        function selectPaymentTermByVoice(paymentSelect, paymentTerm) {
            const options = paymentSelect.options;
            //console.log("Payment term from voice is: ", paymentTerm);
            let found = false;
            let paymentTermEnglish = "";
            if (paymentTerm === "μετρητά") paymentTermEnglish = "cash";
            if (paymentTerm === "πιστωτική κάρτα") paymentTermEnglish = "credit_card";
            if (paymentTerm === "μεταφορά") paymentTermEnglish = "bank_transfer";
            for (let i = 0; i < options.length; i++) {
                //console.log("Current option is: ", options[i].value);
                //console.log(`Checking option "${options[i].text}" with value "${options[i].value}" against payment term "${paymentTerm}"`);
                if (paymentTermEnglish === options[i].value.toLowerCase()) {
                    //console.log(`Selecting payment term "${options[i].text}" by index ${i}`);
                    paymentSelect.selectedIndex = i;
                    found = true;
                    break;
                }
            }
            if (!found) {
                console.log(`Payment term "${paymentTerm}" not found.`);
            }
        }

        function selectStatusByVoice(statusSelect, statusTerm) {
            const options = statusSelect.options;
            
            let found = false;
            let statusTermEnglish = "";
            if (statusTerm === "πληρωμένα") statusTermEnglish = "paid";
            if (statusTerm === "ανεξόφλητα") statusTermEnglish = "overdue";
            if (statusTerm === "σε εκκρεμότητα") statusTermEnglish = "pending";
            for (let i = 0; i < options.length; i++) {
                console.log(`Checking option "${options[i].text}" with value "${options[i].value}" against status term "${statusTerm}"`);
                if (statusTermEnglish === options[i].value.toLowerCase()) {
                    //console.log(`Selecting status term "${options[i].text}" by index ${i}`);
                    statusSelect.selectedIndex = i;
                    found = true;
                    break;
                }
            }
            if (!found) {
                console.log(`Status term "${statusTerm}" not found.`);
            }
        }

        let csvExported = false;

        function exportCSVInvoicesTable() {

            if (csvExported) return; // Prevent multiple exports
            csvExported = true;

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

            // Reset το flag μετά από λίγο για να μπορείς να ξανακάνεις εξαγωγή αν θέλεις
            setTimeout(() => { csvExported = false; }, 2000);

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