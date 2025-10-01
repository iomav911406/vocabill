<?php
require_once 'BaseModel.php';
require_once __DIR__ . '/../config/database.php';
class Invoice extends BaseModel {
    protected static $table = 'invoices'; // Define the table name for this model
    protected static $primaryKey = 'invoice_id';

    public static function calculateTotal($invoice_id) {
        self::initDb();
        $stmt = self::$db->prepare("
            SELECT SUM((s.price+(s.price*s.vat)) * isv.quantity) AS total
            FROM invoice_services isv
            JOIN services s ON isv.service_id = s.service_id
            WHERE isv.invoice_id = :invoice_id
        ");
        $stmt->bindParam(':invoice_id', $invoice_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

}

?>
