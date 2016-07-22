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

class Information implements Content
{
    public function Initialize()
    {}
    
    public function ShowContent()
    {
echo '
<p><a href="http://www.gnu.org/software/octave/about.html">Octave</a> is a  
<a href="http://www.gnu.org">GNU</a> scientific/numerical computing environment, 
which allows the user to perform typical numerical calculations, such as finding 
solutions to systems of nonlinear equations, scientific visualization, etc., 
using a very simple command line user interface to quite sophisticated libraries, 
such as <a href="http://www.netlib.org/lapack/">LAPACK</a>, 
<a href="http://www.netlib.org/quadpack/">QUADPACK</a>, 
<a href="http://www.fftw.org">FFTW</a>, etc. It is available for 
a variety of operating systems, including Linux and Windows.

<p>Octave is quite often referred to as <i>a <a href="http://www.mathworks.com">MATLAB</a> clone</i>, 
because of its very high level of compatibility with this leading advanced numerical computing 
environment. Contrary to MATLAB, which is a commercial system, Octave is a free software.

<h2 id="about">About this service</h2>

<p>This web interface to Octave has been provided for individuals to experiment with Octave, i.e. to run some small, <i>ad hoc</i> calculations. Please do not launch jobs which use either a lot of CPU time, or consume a lot of memory, or filespace. All your activity on this page will be logged. If you don\'t like this policy, please <a href="http://www.gnu.org/software/octave/download.html">get a standalone version</a> of Octave.

<p>Please send your comments or bug reports to <a href="mailto:knn@students.mimuw.edu.pl">knn@students.mimuw.edu.pl</a>. We <strong>need</strong> your feedback.
 
<h3>Features</h3>

<ul>

<li>Every user is granted a separate workspace (provided the browser accepts cookies). With the aid of <code>load</code> and <code>save</code> functions, you may keep some of your variables for later usage. Please <strong>do not abuse</strong> this feature!

<li>It is possible to make plots with the usual commands such as <code>plot</code>, <code>mesh</code>, etc. However only the first one is displayed in the browser.

</ul>

<h3>Some of current restrictions</h3>

<ul>

<li>Present versions of Octave are (much) more powerful. 

<li>For security, some functionality of the original Octave 2.1.73 has been disabled in our installation.

<li>From time to time, all user workspaces are completely removed.

<li><a href="http://octave.sf.net">Octave-forge</a> extensions are not available (yet).

<li>Only the first plot is displayed in the browser.

</ul';    
    }    
}
?>
