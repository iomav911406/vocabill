<div class="lg:mx-8 p-6 bg-white shadow-md rounded-lg border border-gray-200 mb-8">
    
    <form method="post" action="clients.php?action=create" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-2 text-gray-500 text-sm font-semibold" for="company_name">Επωνυμία</label>
                <input type="text" name="company_name" id="company_name" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" required>
            </div>
            <div>
                <label class="block mb-2 text-gray-500 text-sm font-semibold" for="contact_name">Επαφή</label>
                <input type="text" name="contact_name" id="contact_name" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200">
            </div>
            <div>
                <label class="block mb-2 text-gray-500 text-sm font-semibold" for="phone">Τηλέφωνο</label>
                <input type="text" name="phone" id="phone" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200">
            </div>
            <div>
                <label class="block mb-2 text-gray-500 text-sm font-semibold" for="email">Email</label>
                <input type="email" name="email" id="email" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200">
            </div>
            <div>
                <label class="block mb-2 text-gray-500 text-sm font-semibold" for="status">Κατάσταση</label>
                <select name="status" id="status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200" required>
                    <option value="active">Ενεργός</option>
                    <option value="inactive">Μη Ενεργός</option>
                </select>
            </div>
        </div>
        <div class="flex gap-2 mt-6">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 text-sm flex items-center shadow">
                <i class="fas fa-save mr-1"></i> Αποθήκευση
            </button>
            <a href="clients.php" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition duration-300 text-sm flex items-center shadow">
                <i class="fas fa-arrow-left mr-1"></i> Επιστροφή
            </a>
        </div>
    </form>
</div>