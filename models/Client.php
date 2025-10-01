<?php
require_once 'BaseModel.php';

class Client extends BaseModel {
    protected static $table = 'customers'; // Define the table name for this model
    protected static $primaryKey = 'customer_id';
}

?>
