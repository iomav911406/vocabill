<?php
$title = 'Προσθήκη Υπηρεσιών Τιμολογίου';
$heading = 'Προσθήκη Υπηρεσιών Τιμολογίου';
$content = __DIR__ . '/create_content.php';
$invoice_id = isset($invoice_id) ? $invoice_id : (isset($_GET['invoice_id']) ? $_GET['invoice_id'] : null);
include __DIR__ . '/../layout.php';
?>
