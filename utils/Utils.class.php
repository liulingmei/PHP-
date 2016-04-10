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


	public static function check_int($value,$min=0,$max=-1,$compare = true){
		if(!is_numeric($value)){
			return false;
		}
		if(is_null($value)){
			return false;
		}
		if(intval($value) != $value){
			return false;
		}
		if($compare === true && $min < 0){
			return false;
		}
		if($compare === true && 0<= $max && $max < $value){
			return false;
		}
		return true;
	}

	public static function check_string(value, $max_len = NULL, $min_len = 1){
		if(is_null($value)){
			return false;
		}

		if(mb_substr($value, "utf-8") < $min_len){
			return false;
		}

		if(!is_null($max_len) && mb_substr($value, "utf-8") > $max_len){
			return false;
		}
		return true;
	}

	public static function check_array($value){
		return is_array($value) && !empty($value);
	}

	public static function checkPwd($str){
		$pwd = trim($str);
		if(is_string($pwd)){
			$pattern = '/^[_0-9a-z]{6,16}$/i';
			preg_match($pattern, $pwd,$match);
		}
		return empty($match) ? false : true;
	}

	public static function checkUserName($username){
		$username = trim($username);
		if(empty($username) || !is_string($username)){
			return false;
		}
		$pattern = '/^[a-zA-z][A-Za-z0-9_]{3,13}$/';
		if(!preg_match($pattern, $username)){
			return false;
		}
		return true;
	}
}