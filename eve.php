<?php
/**
 * EVE API fetcher for character info
 */

Class eveRequest {
	//User date
	public $name;
	public $keyID;
	public $vCode;
	//API Method
	public $baseURI = 'http://api.eve-online.com/';
	public $requestURI;	
	
	public $data;

	public function __construct($keyID, $vCode){ 
		$this->keyID = $keyID;
		$this->vCode = $vCode;
	}
	
	public function getInfo () {
	$curl_hander = curl_init();
	curl_setopt($curl_handler, CURLOPT_URL, $this->baseURI. $this->requestURI);
	curl_setopt($curl_handler, CURLOPT_POST, 1);
	curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, 1);
	$this->data = new SimpleXMLElement(curl_exec($curl_handler));
        }	
}

Class eveAccount extends eve Request {
	public $characters;
	public function __construct($keyID, $vCode, $requestURI) {
		parent::__construct($ketID, $vCode);
		$this->requestURI = '/account/Characters.xml.aspx';
	}
	
}

$character1 = new eveRequest('1527953', 'ZRKK83eqEkCyvkZBcHrtn4KK87e76dt7z4vA8mRjJenE74HeGpS4RdhLc8cZU33K');

$character->name = 'christov'; 

print $character1->name."\n";
