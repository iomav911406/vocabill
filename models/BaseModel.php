<?php
require_once __DIR__ . '/../config/database.php';

abstract class BaseModel {
    protected static $table;  // The database table name
    protected static $primaryKey = 'id';  // The primary key column name
    protected static $db; //The database connection

    protected static function initDb() {
        if (!isset(self::$db)) {
            global $db;
            self::$db = $db;
        }
    }

    //Initialize connection
    public function __construct() {
        self::initDb();
    }

    public static function all() {
        self::initDb();
        $stmt = self::$db->prepare("SELECT * FROM " . static::$table);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return [];
        }
        return $stmt->fetchAll();
    }

    public static function find($id) {
        self::initDb();
        $primaryKey = static::$primaryKey ?? 'id';
        $stmt = self::$db->prepare("SELECT * FROM " . static::$table . " WHERE {$primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function findByInvId($invoice_id) {
        self::initDb();
        $stmt = self::$db->prepare("SELECT * FROM " . static::$table . " WHERE invoice_id = :invoice_id");
        $stmt->execute(['invoice_id' => $invoice_id]);
        return $stmt->fetchAll();
    }

    public static function findByInvCode($inv_code) {
        self::initDb();
        $stmt = self::$db->prepare("SELECT * FROM " . static::$table . " WHERE inv_code = :inv_code");
        $stmt->execute(['inv_code' => $inv_code]);
        return $stmt->fetch();
    }

    public static function findByCode($code) {
        self::initDb();
        $stmt = self::$db->prepare("SELECT * FROM " . static::$table . " WHERE customer_id = :code");
        $stmt->execute(['code' => $code]);
        return $stmt->fetch();
    }

    public static function findByInvoiceId($invoice_id) {
        self::initDb();
        $stmt = self::$db->prepare("SELECT * FROM " . static::$table . " WHERE invoice_id = :invoice_id");
        $stmt->execute(['invoice_id' => $invoice_id]);
        return $stmt->fetchAll();
    }

    public static function findByCustomerId($customer_id) {
        self::initDb();
        $stmt = self::$db->prepare("SELECT * FROM " . static::$table . " WHERE customer_id = :customer_id ORDER BY created_at DESC");
        $stmt->execute(['customer_id' => $customer_id]);
        return $stmt->fetchAll();
    }

    public static function count() {
        self::initDb();
        $stmt = self::$db->query("SELECT COUNT(*) FROM " . static::$table);
        return $stmt->fetchColumn();
    }

    public static function countWithLimit($days) {
        self::initDb();
        $stmt = self::$db->prepare("SELECT COUNT(*) FROM " . static::$table . " WHERE created_at >= NOW() - INTERVAL :days DAY");
        $stmt->bindValue(':days', (int)$days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public static function countPaid() {
        self::initDb();
        $stmt = self::$db->query("SELECT COUNT(*) FROM " . static::$table . " WHERE status = 'paid'");
        return $stmt->fetchColumn();
    }

    public static function countActive() {
        self::initDb();
        $stmt = self::$db->query("SELECT COUNT(*) FROM " . static::$table . " WHERE status = 'active'");
        return $stmt->fetchColumn();
    }

    public static function totalPaidAmount() {
        self::initDb();
        $stmt = self::$db->query("SELECT SUM(total_amount) FROM " . static::$table . " WHERE status = 'paid'");
        return $stmt->fetchColumn();
    }

    public static function getRecent($limit) {
        self::initDb();
        $stmt = self::$db->prepare("SELECT * FROM " . static::$table . " ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function create($data) {
        self::initDb();
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO " . static::$table . " ($columns) VALUES ($placeholders)";
        $stmt = self::$db->prepare($sql);
        $stmt->execute($data);
        return self::$db->lastInsertId();
    }

    public static function update($id, $data) {
        self::initDb();
        $primaryKey = static::$primaryKey ?? 'id';
        $setClauses = [];
        foreach ($data as $column => $value) {
            $setClauses[] = "$column = :$column";
        }
        $setClause = implode(", ", $setClauses);
        $sql = "UPDATE " . static::$table . " SET $setClause WHERE {$primaryKey} = :pk_id";
        $data['pk_id'] = $id;
        $stmt = self::$db->prepare($sql);
        $stmt->execute($data);
        return $id;
    }

    public static function updateInvoiceTotal($invoice_id, $total) {
        self::initDb();
        $stmt = self::$db->prepare("UPDATE " . static::$table . " SET total_amount = :total WHERE invoice_id = :invoice_id");
        $stmt->execute(['total' => $total, 'invoice_id' => $invoice_id]);
        return true;
    }

    public static function delete($id) {
        self::initDb();
        $primaryKey = static::$primaryKey ?? 'id';
        $stmt = self::$db->prepare("DELETE FROM " . static::$table . " WHERE {$primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        return true;
    }
}

?>
