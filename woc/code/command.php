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
require_once('code/logs.php');
require_once('code/octave.php');
require_once('code/security.php');
require_once('code/plot.php');
require_once('your_plots.php');

class Command implements Content
{
    private $session;
    private $user;
    
    private $anonymous;
    //user's directory name
    private $usdir;
    
    private $username;
   
    private $evaluate;///< Do I have to evaluate Octave's expression

    //how to initialise command line for first run
    private $firstruncmd=CF_FIRSTCMD;
    
    //private $playground_dir = "data/";

    
    public function __construct(&$_session, &$_user)
    {
         //global $Playground;
         $this->session = $_session;
         $this->user = $_user;
         // $this->usdir=PLAYGROUND.$this->session->GetUserID();
         // echo $this->user->GetHomeDirectory();
         $this->usdir=$this->user->GetHomeDirectory();
         $this->username=$this->user->GetLogin();
         $this->anonymous=$_user->IsAnonymous();
    }

    
    public function Initialize(){}
    
    public function ShowContent()
    {
      $sec= new Security();
      $oct = new Octave($this->username,$this->usdir);

      $this->evaluate=true;
//       $command = $_POST['commands'];
      if(!array_key_exists('commands',$_POST))
      {
        $this->evaluate=false;
        if($oct->AvailLastInput())
        {
          $command=$oct->GetLastInput();
        }
        else
        {
          $command=$this->firstruncmd;
        }
      }
      else
      {
        $command=$sec->CheckInput($_POST['commands']);
        
        // Extracting functions from command line
        // if(!$this->anonymous)  //old policy
        {
          $_fun = new Functions($this->usdir);
          $_fun->ExtractAllFunctions($command,$farr);
          for($i=0;$i<sizeof($farr);$i++)
          {

            $sbody= $sec->CheckFunction($farr[$i][1]);
            $funk=  $farr[$i][0];
            $_fun->Add($funk,$sbody);
          }    
        }
      }
      $fun = new Functions($this->usdir);
      
      echo '<div class="panel">';

/*
// a table for user defined functions - we do not show then here, but use a separate tab for them!
echo '
<table>
<tr>
<td>';
*/
echo '
<p class="msg">Input your commands here
<form action="index.php#menus" method="post">
<p><textarea name="commands" rows="6" cols="72">';
echo $command;
echo '
</textarea>
<br><input type="submit" value="Submit to Octave">
<script type="text/javascript">
<!--
document.write("<input type=\"button\" value=\"Clear\" onclick=\"this.form.elements[\'commands\'].value=\'\'\">");
//-->
</script>
</form>'
;
/*
if(!$this->anonymous)
{
echo '
<td>User-defined functions:
';
echo $fun->ShowIns();
echo
'
</td>
';
}
echo'
</tr>
</table>
';
*/

  

    if ($command!='')
    {
      if(!SV_FORWARD)
      {
        // you will probably want to use this one: 
        $log=new Logs($this->username,$_SERVER["REMOTE_ADDR"]);
      }
      else
      {
        // the other one will not work properly in most cases
        $log=new Logs($this->username,$_SERVER["HTTP_X_FORWARDED_FOR"]);      
      }

      $plo = new Plot($this->usdir);

      //we do not need this, if we do not requested to submit commands to Octave's 
      if($this->evaluate)  
      {
        //commands evaluation is requested

        $plo->RemovePlotFiles();
      
        //$log->OctaveQuery($_POST['commands']);
        $log->OctaveQuery($command);


      
        $out=$oct->NewQuery($command);
      }
      else
      {
        //commands evaluation not requested
        //get old output
        $out=$oct->GetOutput($command);
      }

      $outch=$sec->CheckOutput($out,$this->usdir);
      
      if(strlen($outch)>0)
      {
        echo '<p class="msg">Output: <pre>';
        echo $outch;
        echo '</pre>';
      }
      else
      {
        echo '<p class="msg">No text output available.';
      }

      if(!CF_INLINE_PLOTS)
      {
        $yp=new YourPlots($this->session, $this->user);
	$yp->ShowContent();
     }

      $err= $oct->ShowErrors();
      $errch= $sec->CheckErrOutput($err);
      //$errch= ltrim(rtrim($errch));// cut blank spaces at the end and beginning
      // if length of error string is nonzero, we will print it
      if(strlen($errch)>0)
      {
        echo '<p class="msg">Errors: <pre class="error">';
        echo $errch;
        echo '</pre>';
      }

    }
          echo '</div>';

    }
}

?>
