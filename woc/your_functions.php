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
require_once('code/functions.php');

/// Manage interface of user-defined functions
class YourFunctions implements Content
{
    private $session;///< session ID
    private $user;///< variable belonging to user
    private $usdir;///< user's directory
    private $allow = false;///< whether user is allowed to create and manage (his) functions
    private $playground_dir = "data/";///< directory with user working dirs
    
    /// Setup class for given \a _session and \a _user
    /// \param _user User class instance of given user
    /// \param _session Session class instance of current session
    public function __construct(&$_session, &$_user)
    {
      $this->session = $_session;
      $this->user = $_user;
      $this->usdir=$_user->GetHomeDirectory();
      $this->allow=true;
    }
        
    /// Currently does nothing
    public function Initialize()
    {
    }
    
    /// Prints HTML output to web page 
    /// and manages to user's actions
    public function ShowContent()
    {
      if(!$this->allow)
      {
        echo '<p class="error">Sorry, you have to login to get access to personal functions \n';
      }
      else
      {

        $fun = new Functions($this->usdir);
	
	//find out which tab is active
	$yf_tab_class_active = "";
	$def_tab_class_active = "";
	 switch ($_GET['yf'])
            {
                case 'def': 
			$def_tab_class_active = ' class="active"';
			break;
		default:
			$yf_tab_class_active = ' class="active"';
			break;
	     }
        echo
        '<ul class="xmenu">
	<li><a href="?p=yf" '.$yf_tab_class_active.'>Show functions</a>
        <li><a href="?p=yf&amp;yf=def" '.$def_tab_class_active.'>Define new function</a>
	</ul>';
    
        if (isset($_GET['yf']))
        {
            switch ($_GET['yf'])
            {
                case 'edit': 
                  //echo('edit');
                  $funk=$_GET['funk'];
                  //echo $fun->Get($funk);
    echo '<div class="panel">';
                  echo '
                  <p class="msg">Editing function <strong>'.$funk.'</strong>.
                  <form action="index.php?p=yf&amp;yf=change&amp;funk='.$funk.'" method="post">
                  <p><textarea name="funcedit" rows="6" cols="72">';
                  echo $fun->Get($funk);
                  echo '
                  </textarea>
                  <br><input type="submit" value="Save">
                  </form><form action="index.php?p=yf&amp;yf=delete&amp;funk='.$funk.'" method="post">
                  <p>  <input type="submit" value="Delete">
                  </form>
                  ';
       echo '</div>';
               break;
                case 'change': include('code/security.php');
                  $body=$_POST['funcedit'];
                  $sec= new Security();
                  $sbody= $sec->CheckFunction($body);
                  
     echo '<div class="panel">';
                 $funk=$fun->FunctName($sbody);
                  if($funk!=INVALID_STRING)
                  {
                    $fun->Add($funk,$sbody);
                    echo '<p class="msg">Function '.$funk.' updated</p>';
                  }
                  else
                  {
                    echo '<p class="error">This definition is invalid. I cannot read correct function name. </p>';
                    echo '<p class="msg">Remember that function name can be any combination of letters, digits and ';
                    echo "underlines, but first character cannot be a digit. Also it <strong>must</strong> be finished with \"<code>endfunction</code>\" (not just \"<code>end</code>\")</p>";
                    echo '
                    <form action="index.php?p=yf&amp;yf=change" method="post">
                    <p><textarea name="funcedit" rows=6 cols=74>';
                    echo $body;
                    echo '
                    </textarea>
                    <br><input type="submit" value="Save">
                    </form>
                    ';
                  }
      echo '</div>';
                break;
                case 'def':
     echo '<div class="panel">';
                  echo '<p class="msg">Enter function body:';
                 echo '
                  <form action="index.php?p=yf&amp;yf=change" method="post">
                  <p><textarea name="funcedit" rows=6 cols=74>';
                  echo 'function 
endfunction
</textarea>
                  <br><input type="submit" value="Define and save">
                  </form>';
      echo '</div>';
                break;
                case 'delete':
                  $funk=$_GET['funk'];
                  $fun->Del($funk);
                  echo '<p class="msg">Function '.$funk.' deleted.';
                break;


            }
        }
        else
        {
           //$fun->Show('<a href="?p=yf,yf=edit">','</a>');
           echo '<div class="panel"><p class="msg">User-defined functions<ul>';
           $fun->ShowIns('<li><a href="?p=yf&amp;yf=edit&amp;funk=*">*</a>');
           //$fun->Add("sdad","blablabla\n sadsadsa \n\n\nsdsasad");
           //echo "<BR>".$fun->Get("f");
	   echo "</ul></div>";
        }
       
      }    
    } //ShowContent
}
?>
