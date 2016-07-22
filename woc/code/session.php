<?php
/*********************************************************************
*
* This file is a part of Web Interface to Octave project.
* Copyright (C) 2008 Kolo Naukowe Numerykow Uniwersytetu Warszawskiego
* (Students' Numerical Scientific Group of University of Warsaw)
*
* e-mail:knn@students.mimuw.edu.pl
*
* Distributed under terms of GPL License
*
*
*********************************************************************/

/*
	Web Interface to Octave
	Session class
	v 0.1

*/

// include class to access data base
include_once('code/dbaccess.php');

//Session
class Session
{
	// cookie proporties
	private $cookieName = 'web_interface_to_octave';
	private $cookieExpire = 2592000; // 30 days

	// session proporties
	private $id;
	private $time;
	private $userID;	
	
	// store information if suer was here before
	private $wasBefore;
	
	
	// constructor is responsible for setting cookie and session information
	public function __construct()
	{			//echo 'konstruktor';
			// delete old sessions
			$this->DeleteOldSessions();
			//echo isset($_COOKIE[$this->cookieName]);
			// does the cookie exist?
			if(!isset($_COOKIE[$this->cookieName]))
			{
				$_COOKIE[$this->cookieName] = '';		
				
				//echo 'nie ma zdefinowanego ciasteczka';
			}
			
			// if the cookie does not exist or is not apropriate create new cookie
			if(strlen($_COOKIE[$this->cookieName]) != 32)
			{			
				$this->Create();
				//echo 'tworzymy ciasteczko';
				// set that this session is new
				$this->wasBefore = false;
			}
			else // the session id is alright
			{ //echo 'jest ciasteczko';
				// checking if the session exists in data base
				$db = new DBAccess();
				$db->Connect();
                $query = 'SELECT id, userID, time  FROM session WHERE id=\''.$_COOKIE[$this->cookieName].'\'';
				
				$result = &$db->ExecuteQuery($query);
				$row = mysql_fetch_array($result);				
				$db->Close();
				
				// if there is no session in data base with the session id from cookie, create new session
				if (!is_array($row))
				{
					$this->Create();
					
					// set that this session is new
					$this->wasBefore = false;
				}
				else // the session is in the data base
				{
					$this->id = $row['id'];
					$this->userID = $row['userID'];
					
					// update session time
					$this->UpdateSessionTime();
					
					// set that this session is old
					$this->wasBefore = true;
				}
				
			}
	}
		
	// create new cookie
	private function Create()
	{			
		// set cookie
		$this->id = md5(uniqid(time().$_SERVER['REMOTE_ADDR']));
		$this->userID = '0';
		$this->time = time();
		setcookie($this->cookieName, $this->id, time() + $this->cookieExpire);
		
		// add information about seesion to data base
		$db = new DBAccess();
		$db->Connect();
		$result = $db->ExecuteQuery('INSERT INTO session (id, userID, time) VALUES (\''.$this->id.'\',0,'.$this->time.')');
		//echo 'bylem w tworzeniu funkcji';
		$db->Close();
	}
		
	// update session
	private function UpdateSessionTime()
	{		
		$this->time = time();
		
		$db = new DBAccess();
		$db->Connect();
        $query = 'UPDATE session SET time='.$this->time.' WHERE id=\''.$this->id.'\'';    
        $db->ExecuteQuery($query);
		$db->Close();
	}
	
	// change user name
	public function ChangeUserID($_userID)
	{
		// update class
		$this->userID = $_userID;
		
		// update cookie
		setcookie($this->cookieName, $this->id, time() + $this->cookieExpire);
	
		// update database
		$db = new DBAccess();
		$db->Connect();  
        $query = 'UPDATE session SET userID='.$this->userID.' WHERE id=\''.$this->id.'\'';    
        $db->ExecuteQuery($query); 
		$db->Close();
	}
	
	
	// delete old sessions
	private function DeleteOldSessions()
	{
		// delete old user's data
		$db = new DBAccess();
		$db->Connect();
		$query = 'SELECT id FROM session WHERE time < '.(time() - $this->cookieExpire);
		$result = $db->ExecuteQuery($query);
		
		// if there are old sessions in database delete also their's folders
		while ($row = mysql_fetch_array($result))
		{ 
			if (file_exists(PLAYGROUND.$row['id']))
			{
				$path = PLAYGROUND.$row['id'];

				foreach (new DirectoryIterator($path) as $file) {
					if (true === $file->isFile()) {
						unlink($file->getPathName());

					}
				}
				rmdir($path);
			}
		}
	
		// delete old sessions		
		$db->ExecuteQuery('DELETE FROM session WHERE time < '.(time() - $this->cookieExpire));    
		$db->Close();
	}
		
	// get user name
	public function GetUserID()
	{
		return $this->userID;
	}
	
	public function GetSessionID()
	{
		return $this->id;
	}
		
	public function WasBefore()
	{
		return $this->wasBefore;
	}
	
}
	

?>
