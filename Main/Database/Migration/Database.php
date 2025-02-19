<?php

class Database
{
	private $theQuery;
	private $link;
	private $error;

	private $dbPrefix;
	
	function connector($host, $db, $user, $pass, $dbPrefix)
	{
		$this->dbPrefix = $dbPrefix;
		// Connect to the database
		$this->link = mysqli_connect($host, $user, $pass);
		$this->selectDb($db);
		register_shutdown_function(array(&$this, 'close'));
		
	}
	
	function query($sql)
	{
		$this->theQuery = $sql;
		$sql = str_replace('#_', $this->dbPrefix, $this->theQuery);
		$result = mysqli_query($this->link, $sql) or die($this->dbError($this->error));
		
		return $result;
	}
	
	function fetchAssoc($result)
	{
		return mysqli_fetch_assoc($result);
	}
	
	function fetchArray($result, $resultType = MYSQL_NUM)
	{
		return mysqli_fetch_array($result, $resultType);
	}
	
	function queryUniqueValue($sql)
	{
		$result = Database::query($sql);
		$line = Database::fetchAssoc($result);
		return $line;
	}
	
	function fetchRow($result)
	{
		return mysqli_fetch_row($result);
	}

	function freeResult($result)
	{
		return mysqli_free_result($result);
	}
	
	function affectedRows()
	{
		return mysqli_affected_rows();
	}

	function numRows($result)
	{
		return mysqli_num_rows($result);
	}
	function selectDb($dbName)
	{
		return mysqli_select_db($this->link,$dbName);
	}

	function insertId()
	{
		return mysqli_insert_id($this->link);
	}
	
	function fetchFields($result)
	{
		return mysqli_fetch_fields($result);
	}
	
	function numFields($result)
	{
		return mysqli_num_fields($result);
	}
	
	function close()
	{
		mysqli_close($this->link);
	}
	
	function mysql_escape_string($string)
	{
		return mysqli_real_escape_string($this->link,$string);
	}
	
	function dbError($error)
	{
		$this->error = "<table cellpadding='3' cellspacing='1' style='font-size:12px;text-align:left;background-color:#000000;' align='center'>
			<tr><th colspan='2' style='color:#FF0000;background-color:#F5F5F5;'>Error</th></tr>
			<tr><td style='text-align:right;background-color:#F5F5F5;'>Query</td>
				<td style='text-align:right;background-color:#F5F5F5;'>".$this->theQuery."</td></tr>
			<tr><td style='text-align:right;background-color:#F5F5F5;'>Error Message</td>
				<td style='text-align:left;background-color:#F5F5F5;'>".mysqli_error($this->link)."</td></tr>
		</table>";
		
		#$this->error = "<h1>Server is too busy.</h1><p>The Server is processing your first request. Please refresh the page if this page still showing to you contact the System Adminsitrator.</p>";
		
		return $this->error;
	}
}
