<?php
/**
 * EVE API fetcher for character info
 * @TODO: this is task
 */

Class eveRequest {
	//User date
	public $keyID;
	public $vCode;
	
	//API Method
	public $baseURI = 'https://api.eveonline.com/';
	public $requestURI;	
	
	//Returned response
	public $data;
	
	private $cachedUntil;

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
		if(time() >= $this->cachedUntil) {	
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
			$this->parseCommon();
		}
  	return $this;
	}
  
	/**
	 * 
	 * EVE caches data. This is will set the cachedUtil flag to ensure that we 
	 * do not query uneeded (cached) data
	 */
	private function parseCommon() {
		if (!isset($this->data->error)) {
			$this->cachedUntil = strtotime($this->data->cachedUntil);
		}
	}
  	
	public function result($xpath = NULL) {
    if ($xpath) {
      return $this->data->xpath($xpath);
    }
    else {
      return $this->data;
    }
  }
  
  public function debug() {
  	print ($this->data->asXML());
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
	
	/**
	 * Returns character matching $name or NULL
	 */
	  public function getCharacterByName($name = NULL) {
	    foreach ($this->characters as $char) {
	      if ($name == $char['name']) {
	        return $char;
	      }
	    }
	    return NULL;
	  }
	  
	/**
	 * Returns characters matching $ID or NULL
	 */
	  public function getCharacterByID($ID = NULL) {
	    return isset($this->characters[$ID]) ? $this->characters[$ID] : NULL;
	  }
}

Class eveCharacter extends eveRequest {
	
}



//constructed URL
//https://api.eveonline.com/account/Characters.xml.aspx?keyID=1527953&vCode=ZRKK83eqEkCyvkZBcHrtn4KK87e76dt7z4vA8mRjJenE74HeGpS4RdhLc8cZU33K
//https://api.eveonline.com/char/SkillInTraining.xml.aspx?keyID=1527953&vCode=ZRKK83eqEkCyvkZBcHrtn4KK87e76dt7z4vA8mRjJenE74HeGpS4RdhLc8cZU33K&characterID=92650498


//test input
$eve = new eveAccount('1527953', 'ZRKK83eqEkCyvkZBcHrtn4KK87e76dt7z4vA8mRjJenE74HeGpS4RdhLc8cZU33K');


$eve->getCharacters(); 
$character = $eve->getCharacterByID(92650498);
print_r ($christov);
//print_r ($character1->data);
