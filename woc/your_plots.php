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


require_once('code/interfaces.php');
require_once('code/plot.php');
require_once('config/environment.php');

/// Manage interface of user-defined files
class YourPlots implements Content
{
    private $session;///< session ID
    private $user;///< variable belonging to user
    private $usdir;///< user's directory
    
    /// Setup class for given \a _session and \a _user
    /// \param _user User class instance of given user
    /// \param _session Session class instance of current session
    public function __construct(&$_session, &$_user)
    {
      $this->session = $_session;
      $this->user = $_user;
      $this->usdir=$_user->GetHomeDirectory();
    }
        
    /// Currently does nothing
    public function Initialize()
    {
    }
    
    /// Prints HTML output to web page 
    /// and manages to user's actions
    public function ShowContent()
    {
      $plo = new Plot($this->usdir);

      $pic = $plo->PlotFiles();
      
      
      $imax=sizeof($pic);
      
       echo '<div class="panel">';
      if($imax>0)
      {
        $i=1;
	echo '<p class="msg">Plots: ';
	if ($imax > 1)
	{
		$il = $i;
		echo '<a href="#plot'.$il.'">plot'.$il.'</a>';
		$il = $il+1;

		while ($il <= $imax)
		{
			echo ', <a href="#plot'.$il.'">plot'.$il.'</a>';
			$il = $il + 1;
		}
		echo '.';
        }
	while($i <= $imax)
        {
           echo '<div class="plot" id="plot'.$i.'"> 
	   <span class="msg">Download <a href="'.$pic[$i].'">plot'.$i.'.png</a></span> <br>';
           echo '<img class="plot" src="'.$pic[$i].'" alt="Plot '.$i.'">
	   </div>';
           
           $i=$i+1;
        }
        
      }
      else
      {
        echo '<p class="msg">No graphic output available.';
      }
 	echo '</div>'; 
    }//ShowContent
}
?>
