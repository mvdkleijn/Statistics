<?php
/*
 * API plugin for Wolf CMS
 * November 2009
 * @author Ian Dundas for Band-x.org
 */
class StatisticsController extends PluginController {

	public function __construct() {
		$this->statistics_manager = new StatisticsManager();

		if (defined('CMS_BACKEND')) {
			$this->setLayout('backend');
		} else {
			$this->setLayout('plaintext');
		}
	}

	/* -----------------------------------------------
	 * BACKEND FUNCTIONS
	 * CAN I MOVE THESE INTO A SEPARATE CONTROLLER?
	 * -----------------------------------------------
	 */

	public function documentation() {
		$this->display(STATISTICS_VIEWS_BASE.'/backend/documentation/index');
    }

	

	#@id consider revising this implementation
	public function error($format, $str='Unknown error',$code=NULL) {
		header  ($str, TRUE, $code);
		$out=array(
			'error'=>$str,
			'code'=>$code
		);

		$this->stats['api_result_message']=$str;
		$this->stats['api_result']=-1;
		$this->apiUsageManager->logApiUsage($this->stats);
		self::renderArray($out,$format);
	}

	public static function renderArray($out=array(),$format='json') {
		if (strcasecmp('json', $format)==0) {
		#http://snippets.dzone.com/posts/show/5882
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');

			echo self::renderAsJSON($out);
		}
		elseif (strcasecmp('xml', $format)==0) {
		#http://www.satya-weblog.com/2008/02/header-for-xml-content-in-php-file.html
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header ("Content-Type:text/xml");
			echo self::renderAsXML($out, 'xml', NULL, 'entity');
		}
	}

	private static function renderAsJSON($array=array()) {
		return json_encode($array);
	}

	private static function renderAsXML($data=array(), $rootNodeName = 'data', $xml=null, $nodeName='unknownNode') {

		/**
		 * edited from http://snipplr.com/view.php?codeview&id=3491
		 *
		 * The main function for converting to an XML document.
		 * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
		 *
		 * @param array $data
		 * @param string $rootNodeName - what you want the root node to be - defaultsto data.
		 * @param SimpleXMLElement $xml - should only be used recursively
		 * @return string XML
		 */

		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1) {
			ini_set ('zend.ze1_compatibility_mode', 0);
		}

		if ($xml == null) {
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
		}

		// loop through the data passed in.
		foreach($data as $key => $value) {
		// no numeric keys in our xml please!
			if (is_numeric($key)) {
			// make string key...
				$key = $nodeName. (string) $key;
			}

			// replace anything not alpha numeric
			$key = preg_replace('/[^a-z]/i', '', $key);

			// if there is another array found recrusively call this function
			if (is_array($value)) {
				$node = $xml->addChild($key);
				// recrusive call.
				self::renderAsXML($value, $rootNodeName, $node, $nodeName);
			}
			else {
			// add single node.
				$value = htmlentities($value);
				$xml->addChild($key,$value);
			}

		}
		// pass back as string. or simple xml object if you want!
		return $xml->asXML();
	}


}