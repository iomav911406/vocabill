<?php 

require_once __DIR__ . '/../../controllers/ServiceController.php';
$serviceController = new ServiceController();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Details - VocaBill</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .service-header {
            background-color: #f3f4f6;
            padding-top: 2em;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .service-details {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
        }
    </style>
    <lcfirstt/script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100">

<div class="lg:mx-8 p-6 bg-white shadow-md rounded-lg border border-gray-200 mb-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
        <i class="fas fa-eye text-green-500 mr-2"></i> Προβολή Υπηρεσίας
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <span class="block text-gray-500 text-sm font-semibold mb-1">Κωδικός:</span>
            <span class="block text-gray-800 font-bold mb-4"><?php echo htmlspecialchars($service['service_code']); ?></span>
        </div>
        <div>
            <span class="block text-gray-500 text-sm font-semibold mb-1">Όνομα:</span>
            <span class="block text-gray-800 font-bold mb-4"><?php echo htmlspecialchars($service['name']); ?></span>
        </div>
        <div>
            <span class="block text-gray-500 text-sm font-semibold mb-1">Περιγραφή:</span>
            <span class="block text-gray-800 mb-4"><?php echo htmlspecialchars($service['description']); ?></span>
        </div>
        <div>
            <span class="block text-gray-500 text-sm font-semibold mb-1">Τιμή (€):</span>
            <span class="block text-gray-800 font-bold mb-4"><?php echo htmlspecialchars(number_format($service['price'], 2)); ?></span>
        </div>
        <div>
            <span class="block text-gray-500 text-sm font-semibold mb-1">Ημερομηνία Δημιουργίας:</span>
            <span class="block text-gray-800 mb-4"><?php echo htmlspecialchars($service['created_at']); ?></span>
        </div>
    </div>
    <div class="flex gap-2 mt-6">
        <a href="services.php?action=edit&id=<?php echo htmlspecialchars($service['service_id']); ?>" class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900 transition duration-300 text-sm flex items-center shadow">
            <i class="fas fa-edit mr-1"></i> Επεξεργασία
        </a>
        <a href="services.php" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition duration-300 text-sm flex items-center shadow">
            <i class="fas fa-arrow-left mr-1"></i> Επιστροφή
        </a>
    </div>
</div>
</body>
</html>
