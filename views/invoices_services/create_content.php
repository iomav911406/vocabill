<?php

require_once __DIR__ . '/../../controllers/ServiceController.php';
$serviceController = new ServiceController();
$services = $serviceController->getAllServices();
$invoice_id = $invoice_id ?? ($_GET['invoice_id'] ?? null);

?>
<div class="lg:mx-8 p-6 bg-white shadow-md rounded-lg border border-gray-200 mb-8">
    
    <form method="POST" action="invoice-services.php?action=create" class="space-y-6">
        <div>
            <label class="block mb-2 text-gray-500 text-sm font-semibold" for="invoice_id">Κωδικός Τιμολογίου</label>
            <input type="text" id="invoice_id" name="invoice_id" value="<?php echo htmlspecialchars($invoice_id); ?>">
        </div>
        <div id="services-container" class="space-y-4">
            <div class="service-item flex gap-2">
                <select name="service_id[]" class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" required>
                    <option value="">Επιλέξτε Υπηρεσία/Προϊόν</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?php echo htmlspecialchars($service['service_id']); ?>">
                            <?php echo htmlspecialchars($service['service_id']) . " " . htmlspecialchars($service['description']) . " (" . htmlspecialchars($service['price']) . "€)"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="number" step="1" name="quantity[]" placeholder="Ποσότητα" class="w-32 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" required>
                <button id="remove-service-btn" type="button" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition duration-300" onclick="removeService(this)">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <button id="add-service-btn" type="button" class="mt-2 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition duration-300 text-sm flex items-center shadow" onclick="addService()">
            <i class="fas fa-plus mr-1"></i> Προσθήκη Υπηρεσίας/Προϊόντος
        </button>
        <div class="flex gap-2 mt-6">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 text-sm flex items-center shadow">
                <i class="fas fa-save mr-1"></i> Αποθήκευση Υπηρεσιών
            </button>
            <a href="invoices.php" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition duration-300 text-sm flex items-center shadow">
                <i class="fas fa-arrow-left mr-1"></i> Επιστροφή
            </a>
        </div>
    </form>
</div>

<script>
    // Add service row above the add button

    function addService() {
        const container = document.getElementById('services-container');
        const serviceItem = document.createElement('div');
        serviceItem.className = 'service-item flex gap-2 mt-4';
        serviceItem.innerHTML = `
            <select name="service_id[]" class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" required>
                <option value="">Επιλέξτε Υπηρεσία/Προϊόν</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?php echo htmlspecialchars($service['service_id']); ?>">
                        <?php echo htmlspecialchars($service['service_id']) . " " . htmlspecialchars($service['description']) . " (" . htmlspecialchars($service['price']) . "€)"; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="number" step="1" name="quantity[]" placeholder="Ποσότητα" class="w-32 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" required>
            <button type="button" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition duration-300" onclick="removeService(this)">
                <i class="fas fa-minus"></i>
            </button>
        `;
        container.appendChild(serviceItem);
    }

    function removeService(button) {
        const serviceItem = button.parentElement;
        serviceItem.remove();
    }
</script>