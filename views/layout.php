<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'CRUD Boilerplate'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            padding-top: 20px;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Inter', sans-serif;
        }
        @media (max-width: 768px) {
    table.min-w-full {
        display: block;
    }
    thead {
        display: none;
    }
    tbody {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    tbody tr {
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        padding: 1rem;
    }
    tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border: none;
    }
    tbody td:before {
        content: attr(data-label);
        font-weight: bold;
        color: #ef4444;
        margin-right: 1rem;
        min-width: 120px;
    }
}
    </style>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <?php include __DIR__ . '/../includes/header.php'; ?>
        <main>
            <?php include $content; ?>
        </main>
        <?php include __DIR__ . '/../includes/footer.php'; ?>
    </div>
</body>
</html>
