<?php

require_once('lib/receipt.class.php');

/**
 * Android Signature Data Verify Class
 *
 * @package Receipt
 * @author  kaneto
 * @since   PHP 5.2.0
 * @version 1.0.0
 */
class ReceiptAndroid extends Receipt
{
	/**
	 * RSA Key
	 */
	private $rsa_key;
	/**
	 * Signature
	 */
	private $signature;
	/**
	 * signed data
	 */
	private $dncode_signature;
	/**
	 * Signed Data
	 */
	private $signed_data;
	/**
	 * Verify Result
	 */
	private $result;

	/**
	 * Construct
	 *
	 * @param  string $rsa_key RSA Puublic Key
	 * @return null
	 */
	public function __construct($rsa_key)
	{
		$this->rsa_key = $rsa_key;
	}

	/**
	 * Get RSA Puublic Key
	 *
	 * @return string OpenSSL Puublic Key
	 */
	private function getPublicKey()
	{
		$cert = "-----BEGIN PUBLIC KEY-----" 
				. PHP_EOL . chunk_split($this->rsa_key, 64, PHP_EOL) 
				. "-----END PUBLIC KEY-----";
		$publickey = openssl_get_publickey($cert);

		return $publickey;
	}

	/**
	 * Decode Signature
	 *
	 * @param  string $signature Signature
	 * @return string Decode Signature
	 */
	private function getDecodeSignature($signature)
	{
		return base64_decode($signature);
	}

	/**
	 * Verify
	 *
	 * @return null
	 */
	private function verify()
	{
		$this->result = openssl_verify($this->signed_data, $this->dncode_signature, $this->publickey, OPENSSL_ALGO_SHA1);
		openssl_free_key($this->publickey);
	}

	/**
	 * Verify Data
	 *
	 * @return null
	 */
	private function signatureVerify()
	{
		// Make Public Key
		$this->publickey = $this->getPublicKey();

		// Dncode Signature
		$this->dncode_signature = $this->getDecodeSignature($this->signature);

		// Verify
		$this->verify();
	}

	/**
	 * Set Signed Data
	 *
	 * @param  string $signed_data Signed Data
	 */
	public function setSignedData($signed_data)
	{
		$this->signed_data = $signed_data;
	}

	/**
	 * Set Signature
	 *
	 * @param  string $signature Signature
	 */
	public function setSignature($signature)
	{
		$this->signature = $signature;
	}

	/**
	 * Execute Verify Data
	 *
	 * @return array Signed Data
	 */
	public function exec()
	{
		if (empty($this->rsa_key)) {
			return false;
		}

		if (empty($this->signed_data)) {
			return false;
		}

		if (empty($this->signature)) {
			return false;
		}

		// Verify Data
		$this->signatureVerify();

		if ($this->result === 1) {
			return $this->signed_data;
		}
		return false;
	}
}
