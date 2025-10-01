<form method="post" action="clients.php?action=edit&id=<?php echo $client['id']; ?>" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 max-w-lg mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-center">Edit Client</h2>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="name" name="name" type="text" value="<?php echo htmlspecialchars($client['name']); ?>" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="email" name="email" type="email" value="<?php echo htmlspecialchars($client['email']); ?>" required>
    </div>
    <div class="flex items-center justify-between">
        <button class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded w-full" type="submit">
            Update Client
        </button>
    </div>
</form>