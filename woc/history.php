<?php
/*********************************************************************
*
* This file is a part of Web Interface to Octave project.
* 
*********************************************************************/

require_once('code/interfaces.php');

class History implements Content
{
    public function Initialize()
    {}
    
    public function ShowContent()
    {
echo '
<h3>History</h3>

<p>This web page has been prepared and programmed in PHP by 
<a href="http://knn.mimuw.edu.pl">knn.mimuw.edu.pl</a>. Our approach is 
based on the ideas from a <a href="http://www.ms.uky.edu/~statweb">similar web service</a>,
 which contains a web interface to both <a href="http://www.r-project.org">R</a> and 
 <a href="http://www.octave.org">Octave</a>, written in <acronym title="Common Gateway Interface">CGI</acronym>  
 by <a href="mailto:mai@ms.uky.edu">Mai Zhou</a> inspired by MJ Ray\'s Rcgi code.
 ';
    }    
}
?>
