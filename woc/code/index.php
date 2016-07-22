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



include('session.php');
include('user.php'); 


$s = new Session();

$s->ChangeUserID(0);
		

$t = new User(1);

echo $t->ChangePassword('anonymous2', 'anonymous', 'anonymous2');

?>
