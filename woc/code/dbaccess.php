<?php


/*********************************************************************
*
* This file is a part of Web Interface to Octave project.

*********************************************************************/

/*
	Web Interface to Octave
	DBAccess class
	v 0.1

*/

// DBAccess
class DBAccess
{
	//MySQL  host address
	private $host;
	// MySQL user name
	private $user;
	// MySQL user password
	private $password;
	// MySQL DataBase name
	private $dataBase;
	
	// DB conection
	private $DBconection;
	
	//constructor
	public function __construct($_host = '', $_user = '', $_password = '', $_dataBase = '')
	{
		// because PHP5 doesn't allow to define several constructors we need to add some code
		if (!($_host == '') and !($_user == '') and !($_password == '') and !($_dataBase == ''))
		{
			$this->host = $_host;
			$this->user = $_user;
			$this->password = $_password;
			$this->dataBase = $_dataBase;
		}
		else
		{
                        $this->host = DB_HOST;
                        $this->user = DB_USER;
                        $this->password = DB_PASSWORD;
                        $this->dataBase = DB_DATABASE;
                                                                                                		}		
	}
	
	// destruct
	public function __destruct()
	{
	
	}
	
	// conect to DB
	public function Connect()
	{
		// establish connection to MySQL
		$this->DBconnection = mysql_connect($this->host, $this->user, $this->password)
			or die('Can not connect to data base: ' . mysql_error());
			
		// select data base
		mysql_select_db($this->dataBase)
			or die ('Can not select data base: ' . mysql_error());
	}	
	
	// close connection to DB
	public function Close()
	{
		// release resources
//		mysql_free_result($this->result);
	
		// close connection
		mysql_close($this->DBconnection);
	}
	
	// execute query
	public function &ExecuteQuery($_query)
	{
		$this->result = mysql_query($_query)
			or die ('There were some problems with the query: ' . mysql_error());		
		
	 // return reference
	 return $this->result;
	}
}

?>
