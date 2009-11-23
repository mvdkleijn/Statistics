<?php
/*
 * API plugin for Wolf CMS
 * November 2009
 * @author Ian Dundas for Band-x.org
 */
class StatisticsManager {

	const TABLE_NAME = 'statistics_usage';

	function __construct() {
		global $__CMS_CONN__;
		$this->db = $__CMS_CONN__;
	}

	static function getKeys()
	{
		return array_flip(array(	"HTTP_HOST","HTTP_USER_AGENT","HTTP_ACCEPT",
						"HTTP_ACCEPT_LANGUAGE","HTTP_ACCEPT_ENCODING",
						"HTTP_ACCEPT_CHARSET","REMOTE_ADDR", "REQUEST_METHOD",
						"QUERY_STRING","REQUEST_URI","REQUEST_TIME"));
	}

	#takes any number of arrays as input
	function storeRequestData()
	{
		$data = array();
		$allowed_keys=self::getKeys();
		if ($stats_id = $this->addRecord())
		{
			foreach(func_get_args() as $inputArray){
				if (!is_array($inputArray))continue;
				else{
					foreach($inputArray as $key=>$value)
					{
						if (array_key_exists($key, $allowed_keys)){
							$this->addMetaData($stats_id,$key,$value);
						}
					}
				}
			}
		}
		else {//error
		}
	}

	function executeSql($sql) {
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function addMetaData($stats_id,$key,$value)
	{
		$key = addslashes($key);
		$value = addslashes($value);
		$query ="	INSERT INTO ".TABLE_PREFIX."statistics_metadata SET
				".TABLE_PREFIX."statistics_metadata.key = '{$key}',
				".TABLE_PREFIX."statistics_metadata.value = '{$value}',
				".TABLE_PREFIX."statistics_metadata.statistics_id= '{$stats_id}'";
		self::executeSql($query);
	}

	function addRecord()
	{
		$query = '	INSERT INTO '.TABLE_PREFIX."statistics
					SET request_date = '".time()."'";
		self::executeSql($query);
		return $this->db->lastInsertId();
	}
	function add($_POST) {
		$sql = "INSERT INTO ".self::TABLE_NAME."
				(id, name, type)
				VALUES ('', '".filter_var($_POST['name'], FILTER_SANITIZE_STRING)."', '".filter_var($_POST['type'], FILTER_SANITIZE_STRING)."')";
		return self::executeSql($sql);
	}
	
	function quickAdd($name, $type, $association, $document) {
		$sql = "INSERT INTO ".self::TABLE_NAME."
				(id, name, type, associationid, documentid)
				VALUES ('', '".filter_var($name, FILTER_SANITIZE_STRING)."', '".filter_var($type, FILTER_SANITIZE_STRING)."', '".filter_var($association, FILTER_SANITIZE_STRING)."', '".filter_var($document, FILTER_SANITIZE_STRING)."')";
//				echo $sql;exit;
		return self::executeSql($sql);
	}

	function delete($id) {
		$sql = "DELETE FROM ".self::TABLE_NAME."
				WHERE id='".$id."'";
		return self::executeSql($sql);
	}






}