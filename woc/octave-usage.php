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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>Web interface to Octave - logfile</title>
<meta NAME="description" CONTENT="Ko³o naukowe numeryków">
<meta NAME="author" CONTENT="Ko³o naukowe numeryków">
<meta NAME="keywords" CONTENT="student, obliczenia naukowe,
Octave, PETSc, MPI, matematyka obliczeniowa, metody numeryczne, 
analiza numeryczna">
<link href="http://www.gnu.org/software/octave/octave.css" rel="stylesheet">
<style type="text/css">
body { 
	background-image: url("images/gradback.png"); 
	background-repeat: repeat-y; 
}
</style>

<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-2">
</head>
<body>
<div id="title"><h1><a href="http://knn.mimuw.edu.pl/weboctave">Web interface to Octave</a> - logfile</h1></div>

<dl>
<?php
include("Logs/octave-usage.log");
?>
</dl>
</html>
