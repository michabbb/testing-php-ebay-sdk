<?php
require 'vendor/autoload.php';

Kint::$plugins[]='Kint_Parser_Binary';

require_once 'config.php';

$ebay = new ebay_notifications($keys);

$response = $ebay->set_notifications([
							 'DeliveryURLName' => 'FixedPriceTransactions',
							 'DeliveryURL'     => 'https://url.com/'
						 ]);

+d($response);