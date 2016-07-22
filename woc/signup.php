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


// load interfaces
require_once('code/interfaces.php');

class Singup implements Content
{
    private $session;
    private $user;
    
    public function __construct(&$_session, &$_user)
    {
        $this->session = $_session;
        $this->user = $_user;
    }
    
    // initialize page
    public function Initialize()
    {   
        if(array_key_exists('login',$_POST) &&  array_key_exists('password',$_POST) && array_key_exists('repassword',$_POST))      
          if (($_POST['login'] != '') && ($_POST['password'] != '') && ($_POST['repassword'] != ''))
            if ($_POST['password'] == $_POST['repassword'])
            {
                // create new account 
                $this->user->CreateUser($_POST['login'], $_POST['password']);
                
                // log in
                $this->session->ChangeUserID($this->user->GetId()); 
                
                // change site 
                header("Location: index.php");
            }
    }
    
    // show page
    public function ShowContent()
    {
    echo '<div class="panel">';
echo HT_SIGNUP_INFO;
echo '
<p class="msg">Fill in:</p>
<form action="index.php?p=signup" method="post">
<table>
<tr>
<th><label for="login">Login</label>
<td><input type="text" name="login" id="login">
</tr>
<tr>
<th><label for="password">Password</label>
<td><input type="password" name="password" id="password">
</tr>
<tr>
<th><label for="repassword">Retype password</label>
<td><input type="password" name="repassword" id="repassword">
</tr>
</table> 
<p><input type="submit" value="Sign up!" >
</form>';
echo '</div>';
    }
}

 


?>
