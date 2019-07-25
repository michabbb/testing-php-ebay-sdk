<?php

use \DTS\eBaySDK\Inventory\Services;
use DTS\eBaySDK\Inventory\Types\BulkUpdatePriceAndQuantityRestResponse;
use DTS\eBaySDK\Inventory\Types\CreateOrReplaceInventoryItemRestResponse;
use \DTS\eBaySDK\Inventory\Types;
use \DTS\eBaySDK\Inventory\Enums;
use DTS\eBaySDK\Inventory\Types\PriceQuantity;
use DTS\eBaySDK\Inventory\Types\ShipToLocationAvailability;

class ebay_inventory_bulkupdatepricequantiy {

	/**
	 * @var Services\InventoryService
	 */
	private $service;

	public function __construct(array $params) {
		$this->service = new Services\InventoryService(['authorization'    => $params['oauthtoken'],
														'requestLanguage'  => 'en-US',
														'responseLanguage' => 'en-US',
														'sandbox'          => $params['sandbox']]);
	}

	public function getInventoryItem($sku) {
		$request      = new Types\GetInventoryItemRestRequest();
		$request->sku = $sku;

		return $this->service->getInventoryItem($request);
	}

	public function addInventoryItem($sku, $stock) {
		$request                                                     = new Types\CreateOrReplaceInventoryItemRestRequest();
		$request->sku                                                = $sku;
		$request->availability                                       = new Types\Availability();
		$request->availability->shipToLocationAvailability           = new Types\ShipToLocationAvailability();
		$request->availability->shipToLocationAvailability->quantity = $stock;
		$request->condition                                          = Enums\ConditionEnum::C_NEW_OTHER;
		$request->product                                            = new Types\Product();
		$request->product->title                                     = 'GoPro Hero4 Helmet Cam';
		$request->product->description                               = 'New GoPro Hero4 Helmet Cam. Unopened box.';
		$request->product->aspects                                   = [
			'Brand'                => ['GoPro'],
			'Type'                 => ['Helmet/Action'],
			'Storage Type'         => ['Removable'],
			'Recording Definition' => ['High Definition'],
			'Media Format'         => ['Flash Drive (SSD)'],
			'Optical Zoom'         => ['10x', '8x', '4x']
		];
		$request->product->imageUrls                                 = [
			'http://i.ebayimg.com/images/i/182196556219-0-1/s-l1000.jpg',
			'http://i.ebayimg.com/images/i/182196556219-0-1/s-l1001.jpg',
			'http://i.ebayimg.com/images/i/182196556219-0-1/s-l1002.jpg'
		];
		$response                                                    = $this->service->createOrReplaceInventoryItem($request);

		return $this->parse_reponse($response);
	}

	/**
	 * @param CreateOrReplaceInventoryItemRestResponse|BulkUpdatePriceAndQuantityRestResponse $response
	 *
	 * @return array
	 */
	private function parse_reponse($response) {

		$statusCode = $response->getStatusCode();
		$errors     = [];
		$state      = false;

		printf("\nStatus Code: %s\n\n", $statusCode);

		if ($statusCode >= 200 && $statusCode < 400) {
			$state = true;
		}

		if (isset($response->errors)) {
			foreach ($response->errors as $error) {
				$errors[$error->errorId] = $error;
			}
		}

		return [$state, $statusCode, $errors];
	}

	public function updateIventoryStock($sku, $stock) {
		$request                                             = new Types\BulkUpdatePriceAndQuantityRestRequest();
		$priceQuantity                                       = new PriceQuantity();
		$priceQuantity->sku                                  = $sku;
		$priceQuantity->shipToLocationAvailability           = new ShipToLocationAvailability();
		$priceQuantity->shipToLocationAvailability->quantity = $stock;
		$request->requests                                   = [$priceQuantity];
		$response                                            = $this->service->bulkUpdatePriceAndQuantity($request);

		return $this->parse_reponse($response);
	}

}
