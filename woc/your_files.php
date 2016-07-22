<?php
/*********************************************************************
*
* This file is a part of Web Interface to Octave project.
*
*********************************************************************/


require_once('code/interfaces.php');
require_once('config/environment.php');

/// Manage interface of user-defined files
class YourFiles implements Content
{
    private $session;///< session ID
    private $user;///< variable belonging to user
    private $userdir;///< user's directory
    private $allow = false;///< whether user is allowed to create and manage (his) functions
    private $playground_dir = "data/";///< directory with user working dirs
    
    /// Setup class for given \a _session and \a _user
    /// \param _user User class instance of given user
    /// \param _session Session class instance of current session
    public function __construct(&$_session, &$_user)
    {
      $this->session = $_session;
      $this->user = $_user;
      $this->userdir=$_user->GetHomeDirectory();
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
        echo '<p class="error">Sorry, you have to login to get access to personal files \n';
      }
      else
      {

 	$hiddenfiles = array("__wio_plot._m", USER_IN, OCTAVE_IN, OCTAVE_OUT, OCTAVE_ERR);
	
	echo '<div class="panel">';
	
	if (isset($_GET['yfiles']))
	{
		switch ($_GET['yfiles'])
		{
			case 'delete':
				foreach (new DirectoryIterator($this->userdir) as $file)
				{
					if (true === $file->isFile()) 
					{
						unlink($file->getPathName());
					}
				}

				echo '<p class="msg">All files deleted.</p>';

				break;

			default:
				echo '<p class="msg">This is a list of your files</p>';
				break;
		}

	}
	
	echo '<ol>';
        
	foreach (glob($this->userdir."/*") as $filename) 
	{
		if ( !(array_search(basename($filename),$hiddenfiles) > 0 ))
		{
			$filesize = ceil((filesize($filename)/1024));
			if($filesize > 500)
			{
				$filesize='<strong>'.$filesize.'</strong>';
				echo '<li style="background-color: yellow">';
			}
			else
			{
				echo '<li>';
			}
			echo '<a href="'.$filename.'">'.basename($filename).'</a> ('.$filesize.'KB)';
		}
	}
	echo '</ol>
	<form action="index.php?p=yfiles&amp;yfiles=delete" method="post">
                  <p>  <input type="submit" value="Remove all files">
                  </form>
	</div>';
    
	}    
    } //ShowContent
}
?>
