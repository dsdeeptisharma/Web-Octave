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

/// Maintains log files
class Logs
{
  private $username;///< name of user being logged
  private $address;///< IP address belonging to user
  private $hostname;///< DNS name belonging to user
  private $octavelogfilename;///< in this file Octave commands log would be stored
  private $logfilename;///< in this file any (non-Octave commands) log would be stored
  private $logdir = "Logs/";///< subdirectory where log files should be kept
  
  /// Constructor
  /// \param _username - username
  /// \param _address - user's IP address (may be as string)
  /// \param _hostname - user's DNS name if available, do not fill if it is not
  public function __construct( $_username, $_address, $_hostname = "")
  {
    if(!file_exists ($this->logdir))
    {
      mkdir($this->logdir, 0777);
    }
    $this->username=$_username;
    $this->hostname= ( strlen($_address)>0 ? gethostbyaddr($_address) : $_hostname);
    $this->address=$_address;
    $this->octavelogfilename = $this->logdir.'octave-usage.log';
    $this->logfilename = $this->logdir.'default.log';
  }
  
  /// Checks if file exists, if not creates it
  /// \param filename - name of a file o check (with relative path)
  private function MakeFileExist($filename)
  {
    if (!file_exists($filename)) 
    {
      $handle = fopen($filename, "w");
      fclose($handle);
    }
  }
  
  /// Logs issuing an Octave query by a user
  /// \param logstring - octave query issued by user
  public function OctaveQuery($logstring)
  {
    // checking if the log file exists
    $this->MakeFileExist($this->octavelogfilename); 
    $logcontents = file_get_contents($this->octavelogfilename);
    file_put_contents($this->octavelogfilename, "<dh><b>".date('d/m/y ')."</b>".date('G:i:s').' <b>user:'.$this->username.' '.$this->hostname.'</b> ('.$this->address.")</dh><dd><pre>\n".strip_tags(htmlspecialchars(stripslashes($logstring)))."</pre></dd>\n".$logcontents);
 
  }
  
  /// Logs anything releated to the user
  /// \param logstring - inscription to log
  public function Anything($logstring)
  {
    // checking if the log file exists
    $handle=fopen($this->logfilename,"a");
    fwrite($handle, "-".$this->username."-".date('d/m/y ').",".date('G:i:s').' - '.$this->hostname.'- ('.$this->address.") - ".$logstring);
    fclose($handle); 
  }

}




?>
