<?php

/*	---------------------------------------------------------
	:: VALIDATES POPULAR DEBIT AND CREDIT CARDS SERVER SIDE
	--------------------------------------------------------- */

	class CreditCard {
		
		protected static $cards = array(

			'visaelectron' => array(
				'type' => 'visaelectron',
				'pattern' => '/^4(026|17500|405|508|844|91[37])/',
				'length' => array(16),
				'luhn' => true
			),
			'maestro' => array(
				'type' => 'maestro',
				'pattern' => '/^(5(018|0[23]|[68])|6(39|7))/',
				'length' => array(12, 13, 14, 15, 16, 17, 18, 19),
				'luhn' => true
			),
			'forbrugsforeningen' => array(
				'type' => 'forbrugsforeningen',
				'pattern' => '/^600/',
				'length' => array(16),
				'luhn' => true
			),
			'dankort' => array(
				'type' => 'dankort',
				'pattern' => '/^5019/',
				'length' => array(16),
				'luhn' => true
			),
			'visa' => array(
				'type' => 'visa',
				'pattern' => '/^4/',
				'length' => array(13, 16),
				'luhn' => true
			),
			'mastercard' => array(
				'type' => 'mastercard',
				'pattern' => '/^(5[0-5]|2[2-7])/',
				'length' => array(16),
				'luhn' => true
			),
			'amex' => array(
				'type' => 'amex',
				'pattern' => '/^3[47]/',
				'format' => '/(\d{1,4})(\d{1,6})?(\d{1,5})?/',
				'length' => array(15),
				'luhn' => true
			),
			'dinersclub' => array(
				'type' => 'dinersclub',
				'pattern' => '/^3[0689]/',
				'length' => array(14),
				'luhn' => true
			),
			'discover' => array(
				'type' => 'discover',
				'pattern' => '/^6([045]|22)/',
				'length' => array(16),
				'luhn' => true
			),
			'jcb' => array(
				'type' => 'jcb',
				'pattern' => '/^35/',
				'length' => array(16),
				'luhn' => true
			),
			'unionpay' => array(
				'type' => 'unionpay',
				'pattern' => '/^(62|88)/',
				'length' => array(16, 17, 18, 19),
				'luhn' => false
			)
		);

		public static function validateCard($number, $type = null){
			$number = preg_replace('/[^0-9]/', '', $number);
			$number = preg_replace('/\s+/', '', $number);
			if (empty($type)) {
				$type = self::creditCardType($number);
			}
            if (array_key_exists($type, self::$cards) && self::validCard($number, $type)) {			    
			    return true;
			}
			return false;
		}
		
		public static function validateDate($date){
			if (preg_match('/^(0[1-9]|1[012])[\/]((20|30)\d\d)$/',$date)) {
				$dateformat = DateTime::createFromFormat('m/Y', $date);
		        $finalformat = $dateformat->format('Y/m');
				if ($finalformat < date('Y/m')){
					return false;
				}
			    return true;
			}
			return false;
		}
		
		public static function validateMonth($month){
			if (preg_match('/^(0[1-9]|1[012])$/',$month)) {
			    return true;
			}
			return false;
		}
		
		public static function validateYear($year){
			if (preg_match('/^((20|30)\d\d)$/',$year)) {
				if ($year < date('Y')){
					return false;
				}
			    return true;
			}
			return false;
		}
		
		public static function validateMonthYear($year, $month){
			if (preg_match('/^((20|30)\d\d)$/',$year)){
			    if (preg_match('/^(0[1-9]|1[012])$/',$month)){
					if ($year == date('Y') && $month < date('m')){
						return false;
					}
					return true;
				}
				return false;
			}
			return false;
		}
		
		protected static function creditCardType($number){
			foreach (self::$cards as $type => $card) {
				if (preg_match($card['pattern'], $number)) {
					return $type;
				}
			}
			return '';
		}
		
		protected static function validCard($number, $type){
			return (self::validPattern($number, $type) && self::validLength($number, $type) && self::validLuhn($number, $type));
		}
			
		protected static function validPattern($number, $type){
			return preg_match(self::$cards[$type]['pattern'], $number);
		}

		protected static function validLength($number, $type){
			foreach (self::$cards[$type]['length'] as $length) {
				if (strlen($number) == $length) {
					return true;
				}
			}
			return false;
		}
		
		protected static function validLuhn($number, $type){
			if (!self::$cards[$type]['luhn']) {
				return true;
			} else {
				return self::luhnCheck($number);
			}
		}

		protected static function luhnCheck($number){
			$checksum = 0;
			for ($i=(2-(strlen($number) % 2)); $i<=strlen($number); $i+=2) {
				$checksum += (int) ($number{$i-1});
			}

			for ($i=(strlen($number)% 2) + 1; $i<strlen($number); $i+=2) {
				$digit = (int) ($number{$i-1}) * 2;
				if ($digit < 10) {
					$checksum += $digit;
				} else {
					$checksum += ($digit-9);
				}
			}

			if (($checksum % 10) == 0) {
				return true;
			} else {
				return false;
			}
		}
		
	}
	
?>