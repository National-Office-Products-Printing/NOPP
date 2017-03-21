<?php 

	function db_query($dbquery) {
        global $connection;
        $query = mysqli_query($connection,$dbquery);
        return $query;
    }
	
	function db_quote($dbvalue) {
        global $connection;
        return mysqli_real_escape_string($connection,$dbvalue);
    }
	
	function db_select_support($dbtablenamesupport,$dbselectsupportemail,$dbemail){
		$result = db_query("SELECT * FROM ".$dbtablenamesupport." WHERE ".$dbselectsupportemail." = '".db_quote($dbemail)."'");
		$select = mysqli_num_rows($result);
		return $select;
	}
	
	function db_select_service($dbtablenameservice,$dbselectserviceemail,$dbemail){
		$result = db_query("SELECT * FROM ".$dbtablenameservice." WHERE ".$dbselectserviceemail." = '".db_quote($dbemail)."'");
		$select = mysqli_num_rows($result);
		return $select;
	}
	
	function db_select_payment($dbtablenamepayment,$dbselectpaymentemail,$dbemail){
		$result = db_query("SELECT * FROM ".$dbtablenamepayment." WHERE ".$dbselectpaymentemail." = '".db_quote($dbemail)."'");
		$select = mysqli_num_rows($result);
		return $select;
	}
	
	function db_select_recurring($dbtablenamerecurring,$dbselectrecurringemail,$dbemail){
		$result = db_query("SELECT * FROM ".$dbtablenamerecurring." WHERE ".$dbselectrecurringemail." = '".db_quote($dbemail)."'");
		$select = mysqli_num_rows($result);
		return $select;
	}
	
	function db_insert_support($dbtablenamesupport,$dbsupportvalues){
		$dbinsertsupportfields = array_map('db_quote',array_keys($dbsupportvalues));
	    $dbinsertsupportvalues = array_map('db_quote',array_values($dbsupportvalues));
		$insert = db_query("INSERT INTO ".$dbtablenamesupport." (".implode(',',$dbinsertsupportfields).") VALUES ('".implode("','",$dbinsertsupportvalues)."')");      
		return $insert;
	}
	
	function db_insert_service($dbtablenameservice,$dbservicevalues){
		$dbinsertservicefields = array_map('db_quote',array_keys($dbservicevalues));
	    $dbinsertservicevalues = array_map('db_quote',array_values($dbservicevalues));
		$insert = db_query("INSERT INTO ".$dbtablenameservice." (".implode(',',$dbinsertservicefields).") VALUES ('".implode("','",$dbinsertservicevalues)."')");      
		return $insert;
	}
	
	function db_insert_payment($dbtablenamepayment,$dbpaymentvalues){
		$dbinsertpaymentfields = array_map('db_quote',array_keys($dbpaymentvalues));
	    $dbinsertpaymentvalues = array_map('db_quote',array_values($dbpaymentvalues));
		$insert = db_query("INSERT INTO ".$dbtablenamepayment." (".implode(',',$dbinsertpaymentfields).") VALUES ('".implode("','",$dbinsertpaymentvalues)."')");      
		return $insert;
	}
	
	function db_insert_recurring($dbtablenamerecurring,$dbrecurringvalues){
		$dbinsertrecurringfields = array_map('db_quote',array_keys($dbrecurringvalues));
	    $dbinsertrecurringvalues = array_map('db_quote',array_values($dbrecurringvalues));
		$insert = db_query("INSERT INTO ".$dbtablenamerecurring." (".implode(',',$dbinsertrecurringfields).") VALUES ('".implode("','",$dbinsertrecurringvalues)."')");      
		return $insert;
	}
	
?>