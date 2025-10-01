<div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 max-w-lg mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-center">Client Details</h2>
    <div class="mb-4">
        <strong>Name:</strong> <?php echo htmlspecialchars($client['company_name']); ?>
    </div>
    <div class="mb-4">
        <strong>Email:</strong> <?php echo htmlspecialchars($client['email']); ?>
    </div>
    <div class="flex items-center justify-between mt-4">
        <a href="clients.php?action=edit&id=<?php echo $client['customer_id']; ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <i class="fas fa-edit mr-2"></i> Edit
        </a>
        <a href="clients.php?action=list" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to List</a>
    </div>
</div>