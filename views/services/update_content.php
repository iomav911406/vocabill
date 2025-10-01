<?php
require_once __DIR__ . '/../../controllers/ServiceController.php';
// $service πρέπει να έχει τα δεδομένα της υπηρεσίας προς επεξεργασία
?>

<div class="lg:mx-8 p-6 bg-white shadow-md rounded-lg border border-gray-200 mb-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
        <i class="fas fa-edit text-gray-800 mr-2"></i> Επεξεργασία Υπηρεσίας
    </h2>
    <form method="post" action="services.php?action=edit&id=<?php echo htmlspecialchars($service['service_id']); ?>" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-2 text-gray-500 text-sm font-semibold" for="service_code">Κωδικός</label>
                <input type="text" name="service_code" id="service_code" value="<?php echo htmlspecialchars($service['service_code']); ?>" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-green-200" required>
            </div>
            <div>
                <label class="block mb-2 text-gray-500 text-sm font-semibold" for="name">Όνομα</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($service['name']); ?>" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-green-200" required>
            </div>
        </div>
        <div>
            <label class="block mb-2 text-gray-500 text-sm font-semibold" for="description">Περιγραφή</label>
            <textarea name="description" id="description" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-green-200"><?php echo htmlspecialchars($service['description']); ?></textarea>
        </div>
        <div>
            <label class="block mb-2 text-gray-500 text-sm font-semibold" for="price">Τιμή (€)</label>
            <input type="number" name="price" id="price" step="0.01" min="0" value="<?php echo htmlspecialchars($service['price']); ?>" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-green-200" required>
        </div>
        <div class="flex gap-2 mt-6">
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900 transition duration-300 text-sm flex items-center shadow">
                <i class="fas fa-save mr-1"></i> Αποθήκευση
            </button>
            <a href="services.php" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition duration-300 text-sm flex items-center shadow">
                <i class="fas fa-arrow-left mr-1"></i> Επιστροφή
            </a>
        </div>
    </form>
</div>