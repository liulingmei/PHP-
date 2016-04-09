<?php
/**
 *@author:zhaobin
 */
class Utils{
	public static function getHash($hashKey, $subTable){
		if(!is_numeric($hashKey) || !is_string($hashKey)){
			return false;
		}
		if(is_numeric($hashKey)){
			$hash = $hashKey;
		}else {
			$hash = self::getHashFromStr($hashKey); 
		}

		if(intval($hash) > 0 ){
			return $hash % $subTable;
		}else{
			return false;
		}

	}

	public static function getHashFromStr($str){
		if(empty($str)){
			return 0;
		}

		$h = 0;

		for($i = 0, $j = strlen ( $str ); $i < $j; $i = $i + 3) {
			$h = 5 * $h + ord ( $str [$i] );
		}
		return $h;
	}
}