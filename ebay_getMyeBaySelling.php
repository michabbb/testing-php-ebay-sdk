<?php

use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Services;
use PrettyXml\Formatter;


class ebay_getMyeBaySelling {
	private $config;
	private $service;

	public function __construct($params) {

		$defaults = [
			'credentials' => $params['credentials'],
			'authToken'   => $params['authToken'],
			'sandbox'     => $params['sandbox'],
			'version'     => 991
		];

		$this->config = array_merge($defaults, $params);

		$this->service = new Services\TradingService([
														 'credentials' => $this->config['credentials'],
														 'authToken'   => $this->config['authToken'],
														 'sandbox'     => $this->config['sandbox'],
														 'siteId'      => Constants\SiteIds::DE
													 ]);

	}

	public function getmyebayselling() {

		$request = new Types\GetMyeBaySellingRequestType();

		$ActiveList              = new Types\ItemListCustomizationType();
		$ActiveList->Include     = true;
		$Pagination              = new Types\PaginationType();
		$request->OutputSelector = ['ItemID'];

		$itemIDSs = [];

		for ($page = 1; $page <= 20; $page++) {

			echo "running page: ".$page."\n";

//			$formatter = new Formatter();
//			d($formatter->format($request->toRequestXml()));

			$Pagination->PageNumber     = $page;
			$Pagination->EntriesPerPage = 200;
			$ActiveList->Pagination     = $Pagination;
			$request->ActiveList        = $ActiveList;
			$response                   = $this->service->getMyeBaySelling($request);

//			$formatter = new Formatter();
//			d($formatter->format($response->toRequestXml()));
			foreach ($response->ActiveList->ItemArray->Item as $item) {
				$itemIDSs[] = $item->ItemID;
			}
		}

		return $itemIDSs;

	}


}
