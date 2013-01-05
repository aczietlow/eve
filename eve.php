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
	public $baseURI = 'https://api.eveonline.com/';
	public $requestURI;	
	
	//Returned response
	public $data;

	/**
	 * 
	 * Construct a base eve object using the users api key authentication 
	 * @param String $keyID Api key ID
	 * @param String $vCode Api Key verification code
	 */
	public function __construct($keyID, $vCode){ 
		$this->keyID = $keyID;
		$this->vCode = $vCode;
	}
	
	/**
	 * 
	 * Make call to eve's API
	 * @param array $fields fields to be passed via POST during http request
	 */
	public function get($fields = array()) {
		$fields += array(
      'keyID' => $this->keyID,
      'vCode' => $this->vCode,
    );	
		$curl_handler = curl_init();
		curl_setopt($curl_handler, CURLOPT_URL, $this->baseURI. $this->requestURI);
		curl_setopt($curl_handler, CURLOPT_POST, 1);
		curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, 1);
		$this->data = new SimpleXMLElement(curl_exec($curl_handler));
		print $this->data->cachedUntil;
  }
  	
	public function result($xpath = NULL) {
    if ($xpath) {
      return $this->data->xpath($xpath);
    }
    else {
      return $this->data;
    }
  }
}

Class eveAccount extends eveRequest {
	public $characters;
	public function __construct($keyID, $vCode) {
		parent::__construct($keyID, $vCode);
		$this->requestURI = 'account/Characters.xml.aspx';
	}
	
  // Parses XML and creates well structured character object
  private function parseCharacter(SimpleXMLElement $char) {
    $character = new stdClass;
    foreach ($char->attributes() as $index => $value) {
      $character->$index = (string)$value;
    }
    return $character;
  }
	
	private function loadCharacters() {
		$this->get();
		foreach ($this->result('result/rowset/row') as $charData) {
	      $char = $this->parseCharacter($charData);
	      $this->characters[$char->characterID] = $char;
	    }
	}
	
	public function getCharacters() {
		if (!$this->characters) {
			$this->loadCharacters();
		}
	}
}




//constructed URL
//https://api.eveonline.com/account/Characters.xml.aspx?keyID=1527953&vCode=ZRKK83eqEkCyvkZBcHrtn4KK87e76dt7z4vA8mRjJenE74HeGpS4RdhLc8cZU33K
//https://api.eve-online.com/account/Characters.xml.aspx

//test input
$character1 = new eveAccount('1532444', 'zqYXo6MDfyomfjxBZPoAN3BU36jS2pefofDUpJRgFg6k9K3nwe8SSp2ON2WDoUMR');



$character1->getCharacters();
$character1->name = 'christov'; 
print $character1->baseURI. $character1->requestURI;
//print_r ($character1->data);
