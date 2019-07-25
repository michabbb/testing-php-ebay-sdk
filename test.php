<?php
require 'vendor/autoload.php';

Kint::$plugins[]='Kint_Parser_Binary';

require_once 'config.php';

$ebay = new ebay_inventory_bulkupdatepricequantiy($keys);

$response = $ebay->addInventoryItem('ABC',1);
d($response);

/**
 * $response GetInventoryItemRestResponse
 */
$response = $ebay->getInventoryItem('ABC');
d($response->availability->shipToLocationAvailability->quantity); /* you should see: 1 */

$response = $ebay->updateIventoryStock('ABC',5);
d($response);

/**
 * $response GetInventoryItemRestResponse
 */
$response = $ebay->getInventoryItem('ABC');
d($response->availability->shipToLocationAvailability->quantity); /* you should see: 5 */
