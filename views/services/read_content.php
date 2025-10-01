<div class="max-w-lg mx-auto">
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Service Name:</label>
        <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($service['name']); ?></p>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
        <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($service['description']); ?></p>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
        <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">$<?php echo htmlspecialchars(number_format($service['price'], 2)); ?></p>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Service Code:</label>
        <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($service['service_code']); ?></p>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">VAT:</label>
        <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars(number_format($service['VAT'], 2)); ?></p>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
        <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($service['status']); ?></p>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Created At:</label>
        <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($service['created_at']); ?></p>
    </div>
    <a href="services.php?action=list" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Back to List</a>
    <a href="services.php?action=edit&id=<?php echo $service['service_id']; ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Edit</a>
</div>
