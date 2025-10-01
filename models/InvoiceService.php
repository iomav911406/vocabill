<?php
require_once 'BaseModel.php';

class InvoiceService extends BaseModel {
    protected static $table = 'invoice_services';
    protected static $primaryKey = 'invoice_service_id';

    public static function getInvoiceServicesWithDetails($invoice_id) {
        $db = self::initDB();
        $stmt = self::$db->prepare("
            SELECT isv.*, s.name AS service_name, s.price AS service_price, s.vat AS service_vat
            FROM invoice_services isv
            JOIN services s ON isv.service_id = s.service_id
            WHERE isv.invoice_id = :invoice_id
        ");
        $stmt->bindParam(':invoice_id', $invoice_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
