<?php
require 'vendor/autoload.php';

Kint::$plugins[]='Kint_Parser_Binary';

require_once 'config.php';

$ebay = new ebay_getMyeBaySelling($keys);

$response = $ebay->getmyebayselling();

+d($response);
