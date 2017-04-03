<?php
/*********************************************************************
*
* This file is a part of Web Interface to Octave project.

*********************************************************************/



include('session.php');
include('user.php'); 


$s = new Session();

$s->ChangeUserID(0);
		

$t = new User(1);

echo $t->ChangePassword('anonymous2', 'anonymous', 'anonymous2');

?>
