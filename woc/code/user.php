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
	User class
	v 0.1

*/
// include class to access data base
include_once('code/dbaccess.php');  

class User
{
	// user information
	private $id;
	private $login;
	private $password;
	private $lastVisit;
	
	private $userDirectory;
	
	// constructor
	public function __construct($_id = 0, $_sessionID)
	{ 	
		// if the user is set load his data
		if($_id != 0)
		{
		    $db = new DBAccess();
		    $db->Connect();
		    $result = &$db->ExecuteQuery('SELECT id, login, password, lastvisit FROM user WHERE id='.$_id);
			$row = mysql_fetch_array($result);
		    $db->Close();
			
			// if there is such user in data base
			if (is_array($row))
			{		
				$this->id = $row['id'];
				$this->login = $row['login'];
				$this->password = $row['password'];
				$this->lastVisit = $row['lastvisit'];

				// set user directory
				$this->userDirectory = PLAYGROUND.$this->login;
				
				//echo '11111111111111111111';
			}
			else // anonymous
			{
				$this->id = 0;
				$this->login = '';
				$this->password = '';
				$this->lastVisit = time();
				
				// set user directory, if it doesn't exist, create one
				if (!file_exists(PLAYGROUND.$_sessionID))
					mkdir(PLAYGROUND.$_sessionID, 0777);
					
				// set user directory
				$this->userDirectory = PLAYGROUND.$_sessionID;		
				
				$this->lastVisit = filemtime($this->userDirectory);		
				
				//echo '22222222222222';
			}
		}
		else // user id  was not given
		{
			$this->id = 0;
			$this->login = '';
			$this->password = '';
			$this->lastVisit = time();
			
			// set user directory, if it doesn't exist, create one
			if (!file_exists(PLAYGROUND.$_sessionID))
				mkdir(PLAYGROUND.$_sessionID, 0777);
				
			// set user directory
			$this->userDirectory = PLAYGROUND.$_sessionID;
			
			$this->lastVisit = filemtime($this->userDirectory);	
			
			//echo '3333333333333333333 ' + $_sessionID + ' sdfas ';
		}
	}
		
		// is the user anonymous
		public function IsAnonymous()
		{
			return ($this->id == 0 ? true : false);
		}		
		
		// get user id
		public function GetId()
		{
			return $this->id;
		}
		
		// get user login
		public function GetLogin()
		{
			return $this->login;
		}
		
		// get user password
		public function GetPassword()
		{
			return $this->password;
		}
		
		// get user last visit
		public function GetLastVisit()
		{
			return date('d.m.Y, H:i', $this->lastvisit);
		}
		
        // create new user
		public function CreateUser($_login, $_password)
		{
			if (!($_login == '') and !($_password == ''))
			{
				// check if there is no user with that user name
			    $db = new DBAccess();
			    $db->Connect();
			    $result = &$db->ExecuteQuery('SELECT login FROM user WHERE login=\''.$_login.'\'');
				$row = mysql_fetch_array($result);
				
				// if there is no user, create one
				if (!is_array($row))
				{
					$this->id = 0;
					$this->login = $_login;
					$this->password = md5($_password);
					$this->lastVisit = time();
					
				    $db->ExecuteQuery("INSERT INTO user (login, password, lastvisit) 
									VALUES ('$this->login', '$this->password', $this->lastVisit)");
					
					// get user id
				    $result = $db->ExecuteQuery('SELECT id FROM user WHERE login=\''.$this->login.'\'');
					$row = mysql_fetch_array($result);					
				    $db->Close();
                    
                    $this->id = $row['id']; 
					
					// create user directory
					mkdir(PLAYGROUND.$_login, 0777);
					
					// set user directory
					$this->userDirectory = PLAYGROUND.$_login;
		
					return 1;
				}
                else
                {				
			        $db->Close();				
				    return 0;
                }
			}			
		}

		
		// login
		public function Login($_login = '', $_password = '')
		{
			if (!($_login == '') and !($_password == ''))
			{
                $_password = md5($_password);
                
			    $db = new DBAccess();
			    $db->Connect();
                $query = 'SELECT id, login, password, lastvisit FROM user WHERE login=\''.$_login.'\' and password=\''.$_password.'\'';
			    $result = &$db->ExecuteQuery($query);
				$row = mysql_fetch_array($result);
			    $db->Close();
				
				// if the user exist login
				if (is_array($row))
				{
					$this->id = $row['id'];
					$this->login = $row['login'];
					$this->password = $row['password'];
				    $this->lastVisit = $row['lastvisit'];  
						
						
					// set user directory
					$this->userDirectory = PLAYGROUND.$_login;
                   
					
					return 1;
				}
				else
					return 0;
			}
            else
                return 0;			
		}
        
        // login
        public function Logout()
        {      
            $this->id = 0;
            $this->login = '';
            $this->password = '';
            $this->lastVisit = '';       
                   
         return 1;         
        }
          
		
		public function ChangePassword($_login = '', $_oldPassword = '', $_newPassword = '')
		{           
			if (!($_login == '') and !($_oldPassword == '') and !($_newPassword == ''))
			{			
                // convert oldPassword and newPassword to hash
                $_oldPassword = md5($_oldPassword);
                $_newPassword = md5($_newPassword);
                   
				// check if oldPassword match password in data base
			    $db = new DBAccess();
			    $db->Connect();
                $query = 'SELECT id, login, password, lastvisit FROM user WHERE login=\''.$_login.'\' and password=\''.$_oldPassword.'\'';
			    $result = &$db->ExecuteQuery($query);
				$row = mysql_fetch_array($result);
				
				// if there is no user, return 0
				if (!is_array($row))
				{   					
				    $db->Close();
					
					return 0;
				}
				
				// change password
				$this->password = md5($_newPassword);
                $query = 'UPDATE user SET password=\''.$_newPassword.'\' WHERE login=\''.$_login.'\' and password=\''.$_oldPassword.'\'';
			    $db->ExecuteQuery($query);
				
				return 1;
			}		
            else
                return 0;	
		}
		
		public function GetHomeDirectory()
		{
			return $this->userDirectory;
		}
	}
	
?>