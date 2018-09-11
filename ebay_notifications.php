<?php

use \DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Enums\EnableCodeType;
use DTS\eBaySDK\Trading\Enums\NotificationEventTypeCodeType;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Services;
use PrettyXml\Formatter;


class ebay_notifications {
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
														 'siteId'      => Constants\SiteIds::US
													 ]);

	}

	public function set_notifications($params) {
		$defaults = [
			'DeliveryURLName' => '',
			'DeliveryURL'     => '',
			'Status'          => EnableCodeType::C_ENABLE
		];

		$this->config = array_merge($defaults, $params);


		$request = new Types\SetNotificationPreferencesRequestType();

		$NotificationEventPropertyType            = new Types\NotificationEventPropertyType();
		$NotificationEventPropertyType->EventType = NotificationEventTypeCodeType::C_FIXED_PRICE_TRANSACTION;
		$NotificationEventPropertyType->Value     = $this->config['DeliveryURLName'];

		$NotificationEnableType              = new Types\NotificationEnableType();
		$NotificationEnableType->EventType   = NotificationEventTypeCodeType::C_FIXED_PRICE_TRANSACTION;
		$NotificationEnableType->EventEnable = $this->config['Status'];

		$NotificationEnableArrayType                     = new Types\NotificationEnableArrayType();
		$NotificationEnableArrayType->NotificationEnable = [$NotificationEnableType];

		$ApplicationDeliveryPreferences                 = new Types\ApplicationDeliveryPreferencesType();
		$ApplicationDeliveryPreferences->ApplicationURL = $this->config['DeliveryURL'];
		$DeliveryURLDetailType                          = new Types\DeliveryURLDetailType();


		$DeliveryURLDetailType->DeliveryURL                 = $this->config['DeliveryURL'];
		$DeliveryURLDetailType->DeliveryURLName             = $this->config['DeliveryURLName'];
		$DeliveryURLDetailType->Status                      = $this->config['Status'];
		$ApplicationDeliveryPreferences->DeliveryURLDetails = [$DeliveryURLDetailType];
		$ApplicationDeliveryPreferences->AlertEnable        = $this->config['Status'];
		$ApplicationDeliveryPreferences->ApplicationEnable  = $this->config['Status'];

		$request->DeliveryURLName                = $this->config['DeliveryURLName'];
		$request->UserDeliveryPreferenceArray    = $NotificationEnableArrayType;
		$request->ApplicationDeliveryPreferences = $ApplicationDeliveryPreferences;

		$formatter = new Formatter();
		d($formatter->format($request->toRequestXml()));

		$response = $this->service->setNotificationPreferences($request);

		$formatter = new Formatter();
		d($formatter->format($response->toRequestXml()));

		return $response;
	}


}