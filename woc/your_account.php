<?php
/*********************************************************************
*
* This file is a part of Web Interface to Octave project.
*
*
*********************************************************************/

require_once('code/interfaces.php');

class YourAccount implements Content
{
	private $user;
	private $allow;
	private $passwordChanged;

	public function __construct($user)
	{
		$this->user = $user;
		$this->allow = !$user->IsAnonymous();
		
		$this->passwordChanged = false;
	}
	
    public function Initialize()
    {	
		if (isset($_POST['password']) && isset($_POST['newpassword']) && isset($_POST['renewpassword']))
		{
			if ($_POST['newpassword'] == $_POST['renewpassword'])
			{
				//  change user password
				$this->user->ChangePassword($this->user->GetLogin(), $_POST['password'], $_POST['renewpassword']);
				
				$this->passwordChanged = true;
			}
		}	
	}
    
    public function ShowContent()
	{
	echo '<div class="panel">';
	echo '<div>';
        if (!array_key_exists('p',$_GET))
           $signup=false;
        elseif ($_GET['p'] != 'signup')
           $signup=false;
        else $signup=true;

        if(!$signup)
          if ($this->user->IsAnonymous())
          {
            echo '<form action="index.php" method="post">
	    <table>
	    <tr>
	    <th><label for="login">Login</label>
	    <td><input type="text" name="login" id="login">
	    </tr>
	    <tr>
             <th><label for="password">Password</label>
	     <td><input type="password" name="password" id="password">
  	    </tr>
	    </table>
            <p>         <input type="submit" value="Log in" >
                        </form>';  
	   echo '<p class="msg">If you are not registered, <a href="index.php?p=signup">sign up</a>.';
           
          }
          else
           echo '<p class="msg">Hello: <strong>'.$this->user->GetLogin().'</strong>! <a href="index.php?logout">Log out</a> ';
    echo '</div>';

	 if ($this->allow)
      {
		if ($this->passwordChanged == true)
			echo '<p class="msg">Your password has been changed';
	  
echo '
<p class="msg">Change password:
<form action="index.php?p=ya" method="post">
<table>
<tr>
<th><label for="password">Password</label>
<td><input type="password" name="password" id="password">
</tr>
<th><label for="newpassword">New password</label>
<td><input type="password" name="newpassword"  id="newpassword">
</tr>
<tr>
<th><label for="renewpassword">Retype new password</label>
<td><input type="password" name="renewpassword"  id="renewpassword">
</tr>
</table>
<p><input type="submit" value="Change password" >
</form>';

	}
	echo '</div>';
	} //ShowContent
}
?>
