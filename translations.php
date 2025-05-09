<?php 

	if (!isset($_SESSION['lang'])) {
		$_SESSION['lang'] = 'en'; 
  }

	function loadTranslation($lang = null) {
		$lang = $_SESSION['lang'] ?? 'en';

		$langDir = __DIR__ . '/langs/';
		$filePath = $langDir . $lang . ".ini";

		if(!file_exists($filePath)) {
			return [];
		}

		return parse_ini_file($filePath);
	}

	function _lang($key, $lang = "en") {
		static $translations = null;

		if(is_null($translations)) {
			$translations = loadTranslation($lang);
		}

		return isset($translations[$key]) ? $translations[$key] : $key; 
	}

	function setLanguage($lang) {
    $_SESSION['lang'] = $lang;
  }
?>