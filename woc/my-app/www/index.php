
<?php
/*********************************************************************
* 
* This file is a part of Web Interface to Octave project.
* 
*********************************************************************/


    // set absolute path
    set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] );

    // global variables
    require('config/config.php');
    require('config/environment.php');

    // include necessary files
    require_once('code/session.php');
    require_once('code/user.php');
    
     // start session
    $session = new Session();
	
    $user = new User($session->GetUserID(), $session->GetSessionID());
    
	
    // log in user
    if (isset($_POST['login']) and isset($_POST['password'])) 
       if ($user->Login($_POST['login'], $_POST['password']))
       {
            $session->ChangeUserID($user->GetId());
            
            // reload page
            header("Location: index.php");
       }
                          
   // log out
   if ($user->IsAnonymous() == false)                  
    if (isset($_GET['logout']))
    {
        $user->Logout();
        $session->ChangeUserID(0);
        
        // reload page
        header("Location: index.php");
    } 
    
    
    // load content file
    
    //verify whcih tab is active
    $c_tab_class_active = "";
    $ya_tab_class_active = "";
    $yf_tab_class_active = "";
    $yfiles_tab_class_active = "";
    $plots_tab_class_active = "";
    $y_tab_class_active = "";
    $yc_tab_class_active = "";
     if (isset($_GET['p']))
            switch ($_GET['p'])
            {
                case 'c': include('code/command.php');
                          $content = new Command($session, $user);
			  $c_tab_class_active = ' class="active"';
                    break;
                case 'ya': include('your_account.php');
                           $content = new YourAccount($user);
			   $ya_tab_class_active = ' class="active"';
                    break;
                case 'yf': include('your_functions.php');
                           $content = new YourFunctions($session, $user);
			   $yf_tab_class_active = ' class="active"';
                break;
                case 'plots': include('your_plots.php');
                           $content = new YourPlots($session, $user);
			   $plots_tab_class_active = ' class="active"';
                break;
                case 'yfiles': include('your_files.php');
                           $content = new YourFiles($session, $user);
			   $yfiles_tab_class_active = ' class="active"';
                break;
                case 'signup':
                        include('signup.php');                        
                        $content = new Singup($session,$user);
			$ya_tab_class_active = ' class="active"';
                break;
                case 'yc':
                        include('your_comments.php');                        
                        $content = new YourComments($session,$user);
			$yc_tab_class_active = ' class="active"';
        
        
              
         }
        else // if the page wasn't chosen load the default page
        {                
            include('code/command.php'); 
            $content = new Command($session, $user);
	    $c_tab_class_active = ' class="active"';
        }
        
    // initialize content
    $content->Initialize();
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>Web interface to Octave</title>
	<meta NAME="description" CONTENT="Web interface to Octave">
	<meta NAME="author" CONTENT="Ko³o naukowe numeryków, Students' numerical research group">
	<meta NAME="keywords" CONTENT="scientific computing, obliczenia naukowe, Octave, 
	web interface, matematyka obliczeniowa, metody numeryczne, analiza numeryczna">
	<link href="weboctave.css" rel="stylesheet">
	<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-2">
</head>

<body>
	<div id="title"><h1>Web Interface to Octave</h1><p style="font-size: smaller">Version <?php echo WIO_VERSION;?></div>
	
	<h2>Try Octave in your browser!</h2>

	<p>Web Interface to Octave makes it possible to use <a 
	href="http://www.octave.org">Octave</a> remotely through your browser. Learn more <a href="http://knn.mimuw.edu.pl/weboctave-project">about the <cite>Web Interface to Octave</cite> project</a>.You may refer to my <a href='https://deepti96.wordpress.com/category/technical-writing/six-weeks-training/web-octave/' target="_blank">blog</a> also.
	
	
	
	<p><strong>Please do not submit codes that will run for a long time or take up a lot of memory.</strong> This service is intended for occasional, quick computations. Use "<kbd>Files -> Remove all files</kbd>" button to clear your workspace after you are done. All your activities are being logged. If you don't like this policy,  don't use this service.
	
	<p>If you are new to Octave, you may find it useful to read <a href='http://www.gnu.org/software/octave/doc/interpreter'>Octave's manual</a> prior to using it. If you like Octave, it is recommended to <a href='http://www.gnu.org/software/octave/download.html'>get your own copy</a> - it is all free! 
You can have a look<a href='https://deepti96.wordpress.com/'> here</a> also.
	

	
        <?php echo HT_GREETING; ?>
    
    <hr>	
	<div id="menus">
	<ul class="xmenu">
	
	<?php
	
	echo '<li><a href="?p=c#menus" '.$c_tab_class_active.'>Commands</a>';
	echo '<li><a href="?p=plots#menus" '.$plots_tab_class_active.'>Plots</a>';
	echo '<li><a href="?p=yfiles#menus" '.$yfiles_tab_class_active.'>Files</a>';
	echo '<li><a href="?p=yf#menus" '.$yf_tab_class_active.'>Functions</a>';
	echo '<li><a href="?p=ya#menus" '.$ya_tab_class_active.'>Account</a>';
        echo '<li><a href="?p=yc#menus" '.$yc_tab_class_active.'>Comments</a>';
	
	
	?>
	</ul>
	</div>
    
	<div>
	
	<?php
		// load content
        $content->ShowContent();		
	?>	
	
	
	</div>

	<hr> 

        <?php 
         echo HT_INFO_PANEL; 
        ?>
	
	<p style="font-size: smaller">Last updated: 
	<?php echo date ("F d Y H:i:s.", getlastmod()); ?>
	</p>
	
	<p style="font-size:xx-small">This page intentionally uses directly a <a href="http://www.gnu.org/software/octave/octave.css">stylesheet</a> from <a href="http://www.gnu.org/software/octave/about.html">Octave project</a> webpage for basic formatting.
	
</body>

</html>
