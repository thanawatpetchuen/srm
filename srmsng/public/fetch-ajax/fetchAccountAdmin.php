<?php

// DB table to use
$statement_after_from = 'account, customers WHERE account.account_no = customers.customer_no ORDER BY customer_name ASC';
 
// Table's primary key
$primaryKey = 'username_tag';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

// Selected columns
$columns = array(
    array( 'db' => "account_no", 'dt' => 0),
    array( 'db' => "account_type", 'dt' => 1),
    array( 'db' => 'customer_name', 'dt' => 2 ),
    array( 'db' => "username_tag", 'dt' => 3),
    array( 'db' => 'account_status', 'dt' => 4 ),
    array( 'db' => 'is_lock',  'dt' => 5 ),
    array( 'db' => 'last_login',   'dt' => 6 ),
    array( 'db' => 'ip',   'dt' => 7 ),
  
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'admin',
    'pass' => '1234',
    'db'   => 'sngbase',
    'host' => 'localhost'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp2.class.php' );
 
echo json_encode(
    SSP::complex( $_GET, $sql_details, $statement_after_from, $primaryKey, $columns )
);
?>