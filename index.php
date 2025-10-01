<?php
session_start(); // Start the session

// Check if the user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php"); // Redirect to dashboard
    exit;
}

require_once __DIR__ . '/config/database.php';
$errorMessage = "";  // Initialize error message

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    try {
        // Prepare and execute the SQL query to fetch user data
        $stmt = $db->prepare("SELECT admin_id, password_hash FROM admins WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password_hash"])) {
            // Authentication successful
            $_SESSION["user_id"] = $user["admin_id"]; // Store user ID in session
            header("Location: dashboard.php");   // Redirect to dashboard
            exit;
        } else {
            // Authentication failed
            $errorMessage = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        $errorMessage = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vocabill ERP - Είσοδος</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="bg-gray-100 h-screen">
    <div class="flex flex-col items-center justify-center min-h-screen">
        <div class="bg-white shadow-md rounded-lg border border-gray-200 px-8 py-10 w-full max-w-md">
            <div class="flex items-center justify-center mb-6">
                <h1 class="text-3xl font-bold text-center">vocabi<span class="text-blue-500">ll.</span></h1>
            </div>
            <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Είσοδος στο σύστημα</h2>
            
            <?php if ($errorMessage): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-6">
                <div>
                    <label for="email" class="block mb-2 text-gray-500 text-sm font-semibold">Ηλ. Ταχ.</label>
                    <input type="email" id="email" name="email" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label for="password" class="block mb-2 text-gray-500 text-sm font-semibold">Κωδικός πρόσβασης</label>
                    <input type="password" id="password" name="password" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200">
                </div>
                <div class="flex items-center justify-between">
                    <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 text-sm font-semibold shadow">
                        <i class="fas fa-sign-in-alt mr-1"></i> Είσοδος
                    </button>
                </div>
            </form>
            <div class="mt-6 text-center">
                <span class="text-gray-500 text-sm">Δεν έχετε λογαριασμό; </span>
                <a href="register.php" class="text-blue-500 hover:underline text-sm font-semibold">Εγγραφή</a>
            </div>
        </div>
    </div>
</body>
</html>