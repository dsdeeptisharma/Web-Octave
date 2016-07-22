<?php
/*********************************************************************
*
* This file is a part of Web Interface to Octave project.
*
*********************************************************************/


/*****
* Database setup (you have to fill this)
*****/

// Database server name
define("DB_HOST","localhost");

// Database user name 
define("DB_USER","root");

// User's password 
define("DB_PASSWORD","deep");

// Database name
define("DB_DATABASE","weboctav");


/*****
* Octave's interface parameters
*****/

// Allow plot functions (plot, loglog, ...) to operate
// (it may not work with Octave<3.0.0)
// Setting this to false will disable graphical output.
define("CF_PLOTS",true);

// Display plots after commands
define("CF_INLINE_PLOTS",true);

// Disable gnuplot plots X11 visiblity
// On some older Octave versions, you need to 
// set this to false if you get error
// about "figure" command
// Otherwise leave it as true
define("CF_GNUPLOT_FIGURE",true);

// Initial command, to be filled into command textbox
// when user logs in first time
define("CF_FIRSTCMD","A = [1,2;3,4]\neig(A)\ny = x = linspace(0,10);\n[X,Y] = meshgrid(x,y);\nmesh(X,Y,sin(X).*cos(Y).*X);\n");

// Computation time limit (per one submit)
// in secounds
define("CF_MAXTIME",60);

// Computation size limit (per one user)
// User's input,output and temporary Octave files will not exceed this size,
// but if they reach, probably output would be incomplete
// in kilobytes
define("CF_MAXSIZE",1000);


/*****
* Server parameters
*****/

// If your server is behind firewall with forwarding, 
// you may want to enable this to get correct addresses
// in logs.
define("SV_FORWARD",false);


/*****
* Site-specific inscriptions
*****/

// Greeting, displayed on top of the page
define("HT_GREETING",
"


");

// Informations displayed at the bottom of the page
define('HT_INFO_PANEL',"
	<hr />
	<p>All computation is performed on my LOCALHOST Web Server
	<p>Local maintainer: Deepti Sharma. Send your questions and remarks to sharmadeepti888@gmail.com
");

// Information about password storage when signup
define('HT_SIGNUP_INFO',
"<p><strong>Your login and password will be stored on our server.</strong> <br /><br />
");



