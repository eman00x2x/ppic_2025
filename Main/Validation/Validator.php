<?php

namespace EO\Validation;

use Pecee\Exceptions\InvalidArgumentException;

class Validator {

	private $constraints = [];
	private $validatedData;
	private $errors = []; // A variable to store a list of error messages
	private $input = [];
	private $filters = [
			'string' => FILTER_SANITIZE_SPECIAL_CHARS,
			'string[]' => [
				'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
				'flags' => FILTER_REQUIRE_ARRAY
			],
			'email' => FILTER_SANITIZE_EMAIL,
			'int' => [
				'filter' => FILTER_SANITIZE_NUMBER_INT,
				'flags' => FILTER_REQUIRE_SCALAR
			],
			'int[]' => [
				'filter' => FILTER_SANITIZE_NUMBER_INT,
				'flags' => FILTER_REQUIRE_ARRAY
			],
			'float' => [
				'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
				'flags' => FILTER_FLAG_ALLOW_FRACTION
			],
			'float[]' => [
				'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
				'flags' => FILTER_REQUIRE_ARRAY
			],
			'url' => FILTER_SANITIZE_URL,
		];

	function validate(array $data) {

		// Capture the input data.
		$validated = $this->capture($data);

		// Check if any errors were found.
		if($this->foundErrors()) {
			return false;
		}

		foreach($data as $key => $val) {
			$data[$key] = $validated[$key] ?? $data[$key];
		}

		$this->setValidatedData($data);
		return true;
	}

	/**
	 * Captures the input data and applies the constraints.
	 *
	 * @param array $inputs The input data to be validated.
	 * @param array $constraints The constraints to be applied to the input data.
	 * @return array The validated data.
	 * @throws \Exception If the input data or constraints are invalid.
	 */
	function capture(array $inputs) {

		// validate the input data
		if (!is_array($inputs)) {
			throw new InvalidArgumentException("`inputs` param expecting an array, string given. From method capture of Validator Class");
		}

		// convert the input data to dot notation
		$inputs = $this->arrayToDotNotation($inputs);

		// loop through the constraints and validate the input data
		foreach($this->constraints as $name => $validator) {
			// If the input is required, it applies the validation methods specified in the constraints
			if(isset($this->constraints[$name]['required']) && $this->constraints[$name]['required'] === true) {
				foreach($validator as $method => $params) {
					if(method_exists($this, $method)) {

						if(!isset($inputs[$name])) {
							$inputs[$name] = false;
						}

						if(stripos($name, ".") !== false) {
							[$key, $fieldName] = explode(".", $name);
						}else {
							$fieldName = $name;
						}

						$this->input[$name] = $this->$method(name: $fieldName, input: $inputs[$name], params: $params);
						
					}else {
						throw new \Exception("Method `".$method."` not exists in Validator Class.");
					}
				}
			}else {
				if(isset($inputs[$name])) {
					// input is not required, sanitized it
					/* $sanitized = $this->filter([$name => $inputs[$name]], [$name => 'string']);
					$this->input[$name] = $sanitized[$name]; */
					$this->input[$name] = $inputs[$name];
				}
			}
		}

		// return the validated data in array format
		return $this->dotNotationToArray($this->input);

	}

	function getErrors($delim = "<br/>") {
		return "<br/>" . implode($delim, $this->errors);
	}

	function getValidatedData() { 
		return $this->validatedData; 
	}

	function setValidatedData($data) { 
		$this->validatedData = $data; 
	}

	function setConstraints(array $rules) {
		foreach($rules as $field_name => $constraints) {
			foreach($constraints as $constraint => $value) {
				$this->constraints[$field_name][$constraint] = $value;
			}
		}
		return $this;
	}

	function getConstraints() {
		return $this->constraints;
	}

	function resetConstraints() {
		$this->constraints = [];
		return $this;
	}

	function format($name) {
		return ucwords(str_replace("_", " ", $name));
	}

	function length($name, $input, $params) {

		if(!isset($params['min'])) { $params['min'] = 1; }
		if(!isset($params['max'])) { $params['max'] = 255; }

		if(strlen($input) < $params['min'] || strlen($input) > $params['max']) {
			$this->errors[] = $this->format($name)." should be minimum length of " . $params['min'];
			return false;
		}
		$sanitized = $this->filter([$name => $input], [$name => 'string']);
		return $sanitized[$name];
	}

	function required($name, $input, $params) {

		if($params && (is_null($input) || empty($input))) {
			$this->errors[] = $this->format($name)." is required.";
			return false;
		}
		$sanitized = $this->filter([$name => $input], [$name => 'string']);
		return $sanitized[$name];
	}

	// Validate text only
	function textOnly($name, $input, $params) {
		if($params) {
			$result = preg_match("/^[A-Za-z\ .]+$/", $input );
			if (!$result){
				$this->errors[] = $this->format($name)." should only contain text.";
				return false;
			}
		}
		$sanitized = $this->filter([$name => $input], [$name => 'string']);
		return $sanitized[$name];
	}
	
	// Validate text only, no spaces allowed
	function textOnlyNoSpaces($name, $input, $params){
		if($params) {
			$result = preg_match("/^[A-Za-z_]+$/", $input );
			if (!$result){
				$this->errors[] = $this->format($name)." should only contain text, underscore and no spaces.";
				return false;
			}
		}
		$sanitized = $this->filter([$name => $input], [$name => 'string']);
		return $sanitized[$name];
	}
	
	 // Validate alphanumeric only, spaces allowed
	function alphaNumeric($name, $input, $params){
		if($params) {
			$result = preg_match("/^[A-Za-z0-9- ]+$/", $input );
			if (!$result){
				$this->errors[] = $this->format($name)." should only contain alpha numeric characters only no special characters.";
				return false;
			}
		}
		$sanitized = $this->filter([$name => $input], [$name => 'string']);
		return $sanitized[$name];
	}
	
	// Validate Confirm Password
	function confirmPassword($name, $input, $params) {
		if($input !== $params) {
			$this->errors[] = "Password does not matched.";
			return false;
		}
		$sanitized = $this->filter([$name => $input], [$name => 'string']);
		return $sanitized[$name];
	}

	// validate username
	function username($name, $input, $params) {
		if($params) {
			$result = preg_match('/^[a-z0-9.-_]+$/', $input);
			if (!$result && in_array($input, $this->restrictedWordsList())){
				$this->errors[] = "Username is invalid.";
				return false;
			}
		}
		$sanitized = $this->filter([$name => $input], [$name => 'string']);
		return $sanitized[$name];
	}

	// validate url
	function url($name, $input, $params) {
		$sanitized = $this->filter([$name => $input], [$name => 'url']);
		if($params) {
			if (!filter_var($sanitized[$name], FILTER_VALIDATE_URL)){
				$this->errors[] = "url is invalid.";
				return false;
			}
		}
		return $sanitized[$name];
	}
		
	// Validate email address
	function email($name, $input, $params){
		$sanitized = $this->filter([$name => $input], [$name => 'email']);
		if($params) {
			if (!filter_var($sanitized[$name], FILTER_VALIDATE_EMAIL)){
				$this->errors[] = "Email address is not valid.";
				return false;
			}
		}
		return $sanitized[$name];
	}
	
	// Validate numbers only
	function number($name, $input, $params){
		if($params) {
			if (!is_numeric($input)){
				$this->errors[] = $this->format($name)." is not a number.";
				return false;
			}
		}
		$sanitized = $this->filter([$name => $input], [$name => 'int']);
		return $sanitized[$name];
	}
	
	// Validate date
	function date($name, $input, $params){
		if($params) {
			if (strtotime($input) === -1 || $input == '') {
				$this->errors[] = $this->format($name)." is not a valid date.";
				return false;
			}
		}
		$sanitized = $this->filter([$name => $input], [$name => 'string']);
		return $sanitized[$name];
	}
	
	// Validate JSON
	function json($name, $input, $params){
		if($params) {
			json_decode($input);
			if(json_last_error() !== JSON_ERROR_NONE) {
				$this->errors[] = $this->format($name)." is not a valid JSON format.";
				return false;
			}
		}
		$sanitized = $this->filter([$name => $input], [$name => 'string']);
		return $sanitized[$name];
	}

	function urlSafeName($name, $input, $params) {

		$force_lowercase = true;
		$anal = false;

		if($params) {
			$sanitized = $this->filter([$name => $input], [$name => 'string']);
			
			$strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "—", "–", ",", "<", ".", ">", "/", "?");

			$clean = trim(str_replace($strip, "", strip_tags($sanitized[$name])));
			$clean = preg_replace('/\s+/', "-", $clean);
			$clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
			return ($force_lowercase) ?
				(function_exists('mb_strtolower')) ?
					mb_strtolower($clean, 'UTF-8') :
					strtolower($clean) :
				$clean;
		}
		
	}

	function mobileNumber($name, $input, $params) {
		if($params) {
			if(!preg_match('/^[0-9]{10}+$/', $input)) {
				$this->errors[] = $this->format($name)." is not a valid mobile number format {9xxxxxxxxx}.";
				return false;
			}
		}
		$sanitized = $this->filter([$name => $input], [$name => 'string']);
		return $sanitized[$name];
	}

	// validate Words
	function restrictedWords($name, $input, $params) {
		if($params) {
			if ($input != "" && in_array($input, $this->restrictedWordsList())){
				$this->errors[] = $this->format($name)." has a restricted words on it.";
				return false;
			}
		}
		$sanitized = $this->filter([$name => $input], [$name => 'string']);
		return $sanitized[$name];
	}

	function sanitize($name, $input, $params = null) {
		$sanitized[$name] = htmlspecialchars($input, ENT_QUOTES);
		return $sanitized[$name];
	}

	/**
	 * Validates that the input value is unique.
	 *
	 * @param string $name The name of the input field.
	 * @param mixed $input The input value to be validated.
	 * @param mixed $params Additional parameters for validation (not used in this function).
	 * 				Check database if already exists and return true or false value only
	 *
	 * @return mixed The sanitized input value if it is unique, or false if it already exists.
	 */
	function unique($name, $input, $params = null) {
		if($params) {
			$this->errors[] = $this->format($name)." already exists!";
		}

		$sanitized = $this->filter([$name => $input], [$name => 'string']);
		return $sanitized[$name];
	}

	// Check whether any errors have been found (i.e. validation has returned false)
	// since the object was created
	function foundErrors() {
		if (!empty($this->errors)){
			return true;
		}else{
			return false;
		}
	}

	// Manually add something to the list of errors
	function addError($description){
		$this->errors[] = $description;
	}

	function restrictedWordsList() {
		$restricted_words = "about access account accounts add address adm admin administration administrator adult ";
		$restricted_words .= "advertising affiliate affiliates ajax analytics android anon anonymous api ";
		$restricted_words .= "apple app apps archive atom auth authentication avatar ";
		$restricted_words .= "backup banner banners bin billing blog blogs board bot bots business ";
		$restricted_words .= "chat cache cadastro calendar campaign careers cgi client cliente code comercial ";
		$restricted_words .= "compare config connect contact contest create code compras css ";
		$restricted_words .= "dashboard data db design delete demo design designer dev devel dir directory";
		$restricted_words .= "doc docs domain download downloads edit editor email ecommerce ";
		$restricted_words .= "forum forums faq favorite feed feedback flog follow file files free ftp";
		$restricted_words .= "gadget gadgets games guest group groups ";
		$restricted_words .= "help home homepage host hosting hostname html http httpd https hpg ";
		$restricted_words .= "info information image img images imap index invite intranet indice ipad iphone irc ";
		$restricted_words .= "java javascript job jobs js ";
		$restricted_words .= "knowledgebase ";
		$restricted_words .= "log login logs logout list lists ";
		$restricted_words .= "mail mail1 mail2 mail3 mail4 mail5 mailer mailing mx manager marketing master me media message ";
		$restricted_words .= "microblog microblogs mine mp3 msg msn mysql messenger mob mobile movie movies music musicas my ";
		$restricted_words .= "name named net network new news newsletter nick nickname notes noticias ns ns1 ns2 ns3 ns4 ";
		$restricted_words .= "old online operator order orders ";
		$restricted_words .= "page pager pages panel password perl pic pics photo photos photoalbum php plugin plugins pop pop3 post ";
		$restricted_words .= "postmaster postfix posts profile project projects promo pub public python ";
		$restricted_words .= "random register registration root ruby rss ";
		$restricted_words .= "sale sales sample samples script scripts secure send service shop sql signup signin search security ";
		$restricted_words .= "settings setting setup site sites sitemap smtp soporte ssh stage staging start subscribe subdomain ";
		$restricted_words .= "suporte support stat static stats status store stores system ";
		$restricted_words .= "tablet tablets tech telnet test test1 test2 test3 teste tests theme themes tmp todo task tasks tools tv talk ";
		$restricted_words .= "update upload url user username usuario usage ";
		$restricted_words .= "vendas video videos visitor ";
		$restricted_words .= "win ww www www1 www2 www3 www4 www5 www6 www7 wwww wws wwws web webmail website websites webmaster workshop ";
		$restricted_words .= "xxx xpg you yourname yourusername yoursite yourdomain ";
		$restricted_words .= "anal anus arse ass ballsack balls bastard bitch biatch bloody blowjob bollock bollok boner ";
		$restricted_words .= "boob bugger bum butt buttplug clitoris cock coon crap cunt damn dick dildo dyke fag feck ";
		$restricted_words .= "fellate fellatio felching fuck fudgepacker fudge packer flange Goddamn God damn hell homo ";
		$restricted_words .= "jerk jizz knobend knob end labia lmao lmfao muff nigger nigga omg penis piss poop prick pube ";
		$restricted_words .= "pussy queer scrotum sex shit sh1t slut smegma spunk tit tosser turd twat vagina wank whore wtf ";
	
		return explode(" ",$restricted_words);
	
	}

	/**
	* Sanitize the inputs based on the rules an optionally trim the string
	* @param array $inputs
	* @param array $fields
	* @param int $default_filter FILTER_SANITIZE_STRING
	* @param bool $trim
	* @return array
	*/
	function filter(array $inputs, array $fields = [], int $default_filter = FILTER_UNSAFE_RAW, bool $trim = true): array {
		if ($fields) {
			$options = array_map(fn($field) => $this->filters[$field], $fields);
			$data = filter_var_array($inputs, $options);
		} else {
			$data = htmlspecialchars($inputs, ENT_QUOTES);
		}

		return $trim ? $this->arrayTrim($data) : $data;
	}

	/**
	* Recursively trim strings in an array
	* @param array $items
	* @return array
	*/
	function arrayTrim(array $items): array {
		return array_map(function ($item) {
			if (is_string($item)) {
				return trim($item);
			} elseif (is_array($item)) {
				return $this->arrayTrim($item);
			} else
				return $item;
		}, $items);
	}

	function arrayToDotNotation($arr) {
		return arrayToDotNotation($arr);
	}

	function dotNotationToArray($arr) {
		return dotNotationToArray($arr);
	}
		
}


/* 
$_POST = [
        "name" => "Eman 'Olivas",
        "email" => "eman00x2x@gmail.com",
        "username" => "eman00x2x",
        "address" => [
            "province" => "Metro Manila",
            "municipality" => ""
        ]
    ];

    try {
        $response = $validator->capture($_POST, [
            "name" => [
                "length" => [ "max" => 20 ],
                "required" => true,
                "restrictedWords" => true
            ],
            "email" => [
                "length" => [ "min" => 15 ],
                "required" => true,
                "email" => true,
                "restrictedWords" => true
            ],
            "username" => [
                "length" => [ "max" => 20 ],
                "required" => true,
                "username" => true,
                "restrictedWords" => true
            ],
            "address.province" => [
                "required" => true,
                "restrictedWords" => true
            ],
            "address.municipality" => [
                "required" => true,
                "restrictedWords" => true
            ]
        ]);
        debug($response);
    } catch (\Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
} */