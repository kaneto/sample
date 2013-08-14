<?php

require_once('lib/receipt.class.php');

/**
 * iOS Receipt Check Class
 *
 * @package Receipt
 * @author  kaneto
 * @since   PHP 5.2.0
 * @version 1.0.0
 */
class ReceiptIos extends Receipt 
{
	/**
	 * Receipt Data
	 */
	private $receipt;

	/**
	 * Password
	 */
	private $password;

	/**
	 * App Stor POST Data
	 */
	private $post_data;

	/**
	 * App Stor Response
	 */
	private $encoded_response;

	/**
	 * App Stor HTTP Response Code
	 */
	private $response_code;

	/**
	 * JSON Encode Response
	 */
	private $response;

	/**
	 * App Stor Response Status
	 */
	private $status;

	/**
	 * POST DATA Encode Flag
	 */
	private $base64_encode = true;

	/**
	 * App Store URL
	 */
	const APP_STORE_URL = "https://buy.itunes.apple.com/verifyReceipt";

	/**
	 * App Store Sand Box URL
	 */
	const APP_STORE_URL_SANDBOX = "https://sandbox.itunes.apple.com/verifyReceipt";

	/**
	 * HTTP Response Code
	 */
	/**
	 * Success
	 */
	const RESPONSE_CODE_SUCCESS = 200;

	/**
	 * App Store Response Status
	 */
	/**
	 * Success
	 */
	const RESPONSE_STATUS_SUCCESS = 0;

	/**
	 * HTTP Response Code Error
	 */
	const RESPONSE_STATUS_RECEIPT_SERVER_ERROR = 500;

	/**
	 * Receipt Data Format Error
	 */
	const RESPONSE_STATUS_DATA_ERROR = 21002;

	/**
	 * App Store Outh Error
	 */
	const RESPONSE_STATUS_OUTH_ERROR = 21003;

	/**
	 * App Store Unavailable
	 */
	const RESPONSE_STATUS_UNAVAILABLE = 21005;

	/**
	 * Expired Subscription
	 */
	const RESPONSE_STATUS_EXPIRED_SUBSCRIPTION = 21006;

	/**
	 * Sandbox Receipt
	 */
	const RESPONSE_STATUS_SANDBOX_RECEIPT = 21007;

	/**
	 * Production Receipt
	 */
	const RESPONSE_STATUS_PRODUCTION_RECEIPT = 21007;

	/**
	 * Construct
	 *
	 * @return null
	 */
	public function __construct()
	{
	}

	/**
	 * Set Receipt Data Encode Flag
	 *
	 * @param  bool $flag Encode Flag
	 * @return null
	 */
	public function setBase64Encode($flag = true)
	{
		$this->base64_encode = $flag;
	}

	/**
	 * Encodeing Receipt Data to App Store
	 *
	 * @param  string $data レシートデータ
	 * @return null
	 */
	private function setEncodeData($data, $password)
	{
		if ($this->base64_encode) {
			$data = base64_encode($data);
		}

		$post_data['receipt-data'] = $data;
		if ($password) {
			$post_data['password'] = $password;
		}
		$this->post_data = json_encode($post_data);
	}

	/**
	 * Send POST Data
	 *
	 * @param  string $url Access URL
	 * @return null
	 */
	private function send($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->post_data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json; charset=UTF-8'));
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		$this->encoded_response = curl_exec($ch);
		$this->response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
	}

	/**
	 * Request App Store
	 *
	 * @return null
	 */
	private function sendRequest()
	{
		// Receipt Data Encode
		$this->setEncodeData($this->receipt, $this->password);
	
		// Send POST Request
		$this->send(self::APP_STORE_URL);

		// Check Response Code
		if ($this->response_code != self::RESPONSE_CODE_SUCCESS) {
			// Response Error
			$this->status = self::RESPONSE_STATUS_RECEIPT_SERVER_ERROR;
		} else {
			$this->response = json_decode($this->encoded_response, true);
			$this->status = $this->response['status'];
			// Sand Box Receipt Data
			if ($this->status == self::RESPONSE_STATUS_SANDBOX_RECEIPT) {
				// Send POST Request SandBox
				$this->send(self::APP_STORE_URL_SANDBOX);
				$this->response = json_decode($this->encoded_response, true);
				$this->status = $this->response['status'];
			}
		}
	}

	/**
	 * Get Status
	 *
	 * @return int Status
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Get App Store Response
	 *
	 * @return array Response
	 */
	public function getResponse()
	{
		return $this->response;
	}

	/**
	 * Get HTTP Response Code
	 *
	 * @return int Response Code
	 */
	public function getResponseCode()
	{
		return $this->response_code;
	}

	/**
	 * Set Receipt Data
	 *
	 * @param  string $receipt Receipt Data
	 */
	public function setReceipt($receipt)
	{
		$this->receipt = $receipt;
	}

	/**
	 * Set Password
	 *
	 * @param  string $password Password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}

	/**
	 * Execute Receipt Check
	 *
	 * @return array Response Data
	 */
	public function exec()
	{
		if (empty($this->receipt)) {
			return false;
		}

		// Send Request
		$this->sendRequest();

		return $this->response;
	}
}
