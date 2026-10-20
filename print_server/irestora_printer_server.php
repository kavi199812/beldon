<?php
// CORS and Private Network Access headers for cross-origin requests from live HTTPS POS server
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Private-Network: true');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(['status' => 'ok']);
    exit();
}

// Log incoming request for debugging
$log_entry = date('Y-m-d H:i:s') . " | Method: " . $_SERVER['REQUEST_METHOD'] . " | Print Type: " . (isset($_POST['print_type']) ? $_POST['print_type'] : 'None') . "\n";
file_put_contents(__DIR__ . '/print_log.txt', $log_entry, FILE_APPEND);

try {
    //load library
    require __DIR__ . '/escpos-php/autoload.php';
    //include helper function
    include_once 'include/printer_helper.php';
    //include printer load
    include_once 'include/printer_load.php';

    $content_raw = isset($_POST['content_data']) ? $_POST['content_data'] : '';
    $object = json_decode($content_raw);

    if ($object) {
        $print_type = isset($_POST['print_type']) && $_POST['print_type'] ? $_POST['print_type'] : '';

        if ($print_type == "Bill") {
            print_receipt_bill($object);
        } else if ($print_type == "KOT") {
            print_kitchen_printers($object);
        } else if ($print_type == "Invoice") {
            print_receipt($object);
        }

        echo json_encode(['status' => 'success', 'message' => 'Printed successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No content_data received']);
    }
} catch (Throwable $e) {
    file_put_contents(__DIR__ . '/print_log.txt', date('Y-m-d H:i:s') . " | ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
