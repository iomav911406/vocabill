<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/controllers/ClientController.php';

$clientController = new ClientController();
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vocabill ERP - Πελάτες</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    
</head>
<body class="bg-gray-100 h-screen">
    <!-- Voice Button in absolute responsive position -->
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
                    $clientController->listClients();
                    break;
                case 'show':
                    $clientController->showClient($id);
                    break;
                case 'create':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $clientController->createClient($_POST);
                    } else {
                        $clientController->createClientForm();
                    }
                    break;
                case 'edit':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $clientController->updateClient($id, $_POST);
                    } else {
                        $clientController->editClientForm($id);
                    }
                    break;
                case 'delete':
                    $clientController->deleteClient($id);
                    break;
                default:
                    $clientController->listClients();
                    break;
           } 
           ?>
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
                    } else if (voiceCmd.toLowerCase().includes("αριθμός ")) {
                        const searchTerm = voiceCmd.replace(/αριθμός/i, "").trim();
                        document.getElementById('clients-search').value = searchTerm;
                        searchClientsTableByIdByVoice(searchTerm);
                    } else if (voiceCmd.toLowerCase().includes("πελάτης")) {
                        const searchTerm = voiceCmd.replace(/πελάτης/i, "").trim();
                        document.getElementById('clients-search').value = searchTerm; // reset search
                        searchClientsTableByVoice(searchTerm);
                    } else if (voiceCmd.toLowerCase().includes("βρες τον πελάτη με αριθμό ")) {
                        const searchTerm = voiceCmd.replace(/βρες τον πελάτη με αριθμό/i, "").trim();
                        document.getElementById('clients-search').value = ""; // reset search
                        searchClientsTableByIdByVoice(searchTerm);
                    } else if (voiceCmd.toLowerCase().includes("βρες τους πελάτες που είναι")) {
                        const filterTerm = voiceCmd.replace(/βρες τους πελάτες που είναι/i, "").trim();
                        const filterValue = document.getElementById('clients-filter').value = filterTerm;
                        filterClientsTableByVoice(filterValue);
                    } else if (voiceCmd.toLowerCase().includes("csv")) {
                        exportCSVInvoicesTable();
                    } else if (voiceCmd.toLowerCase().includes("excel")) {
                        exportExcelInvoicesTable();
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
                    voiceMessage.textContent = 'Συνέβη λάθος. Παρακαλώ προσπαθήστε ξανά.';
                };
                recognition.onend = function() {
                    voiceMessage.textContent = 'Voice recognition stopped.';
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
        function searchClientsTable() {
            const input = document.getElementById('clients-search');
            const search = input.value.toUpperCase();
            const table = document.getElementById('clients-table');
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

        function searchClientsTableByVoice(searchTerm) {
            const search = searchTerm.toUpperCase();
            const table = document.getElementById('clients-table');
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

        function searchClientsTableByIdByVoice(id) {
            const tableBody = document.getElementById('clients-table-body');
            const rows = tableBody.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const idCell = rows[i].getElementsByTagName('td')[0]; // Υποθέτουμε ότι το ID είναι στην πρώτη στήλη
                if (idCell) {
                    rows[i].style.display = (idCell.innerText.trim() === id) ? '' : 'none';
                }
            }
        }

        function filterClientsTable() {
            const filterValue = document.getElementById('clients-filter').value.toLowerCase();
            console.log("filterValue is: ", filterValue);
            const tableBody = document.getElementById('clients-table-body');
            const rows = tableBody.getElementsByTagName('tr');
            
            // Loop through all table rows (skip header row)
            for (let i = 0; i < rows.length; i++) {
                const statusCell = rows[i].getElementsByTagName('td')[7];
                console.log("Status Cell is ", statusCell.innerText) // Assuming status is the 8th column
                if (statusCell) {
                    const statusText = statusCell.innerText;
                    console.log("Status text is ", statusText);
                    rows[i].style.display = statusText.includes(filterValue) ? '' : 'none';
                }
            }
        }

        function exportCSVClientsTable() {
            const clientsTable = document.getElementById('clients-table'); // Select the table
            const rows = Array.from(clientsTable.rows); // Get all rows in the table
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
            link.setAttribute('download', 'clients.csv'); // Set the file name

            // Append link to the body
            document.body.appendChild(link);
            link.click(); // Trigger the download
            document.body.removeChild(link); // Remove the link after download
        }

        function exportExcelClientsTable(filename = 'clients.xlsx') {
            // Get the table element
            const invoicesTable = document.getElementById('clients-table');

            // Get the tbody and its rows
            const tbody = invoicesTable.querySelector('tbody');
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