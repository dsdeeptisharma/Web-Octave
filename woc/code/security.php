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



/// Strips dangerous commands and unwanted substrings from various range of strings
class Security
{

  private $dangerous_strings  = array("fopen", "fclose", "system", "print", "cd", "builtin", "link", "symlink", "readlink", "unlink", "readdir", "mkdir", "rmdir", "mkfifo", "unmask", "stat", "lstat", "fileattrib", "movefile", "copyfile", "tempname", "P_tmpdir");///< strings that we think to be dangerous

  
  /// Cuts dangerous %Octave commands from \a string
  /// \return \a string with no dangerous commands
  private function BasicCuts($string)
  {
    $string = str_replace("..","", $string);
    
    // remove some other unsafe commands but don't expect miracles... 
    return str_replace($this->dangerous_strings,"", $string);
  }

  /// Ensures \a string does not contain any HTML-releated commands 
  /// \return \a string with HTML tags stripped
  private function HtmlElementsCuts($string)
  {
    $string = strip_tags(htmlspecialchars(stripslashes($string)));
    return $string;
  }
  
  /// Cuts globally-known paths from \a string
  /// \return altered \a string
  private function PathCuts($string)
  {
    
    $string = str_replace(OCTAVE_IN, "", $string);
    $string = str_replace(OCTAVE_OUT, "", $string);
    $string = str_replace(OCTAVE_ERR, "", $string);
    $string = str_replace(OCTAVE_GRAPH, "", $string);
    $string = str_replace(PLAYGROUND, "", $string);
    return $string;
  }

  /// Cuts Octave-releated words from \a string
  /// \return altered \a string
  private function OctaveCuts($string)
  {
    //$string = str_replace("Octave", "", $string);
    //$string = str_replace("home/misc/knn", "", $string);
    //$string = str_replace(chr(27)."[?1034h", "", $string);// no idea why this appears
    
    //visually differentiate command echo from the others
    $string = str_replace('[OCT_PROMPT_BEGIN]', '<span class="prompt">', $string);
    $string = str_replace('[OCT_PROMPT_END]', '</span>', $string);
    return $string;
  }

  /// \brief Changes addresses and emails to links 
  /// Replaces strings with internet addresses and emails
  /// with equivalent HTML-tagged links
  /// \param string base string
  /// \return altered string
  private function AddrsToLinks($string)  
  {
    //replace addresses to links
    $string=ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]",
                     "<a href=\"\\0\">\\0</a>", $string); 

    //replace emails to links
    $string=ereg_replace("[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}",
                     "<a href=\"mailto:\\0\">\\0</a>",$string);
    
    return $string;
  }
 
  /// \brief Converts plot subcommands to HTML plots
  /// \param string base string
  /// \param userdir user's directory to be put into link
  /// \return string with HTML plots
  private function SubToImages($string,$userdir)
  {
    $string=ereg_replace("\[WIO_plot([0123456789]+)\.png\]",
                     "<div class=\"plot\"><span class=\"msg\">Download <a href=\"".$userdir."/plot\\1.png\">plot\\1.png</a></span><br><img class=\"plot\" src=\"".$userdir."/plot\\1.png\" alt=\"Plot \\1\"></div>", 
		     $string); 
		     
		       
	   
    return $string;
  }

  /// Strips dangerous parts of string, that would be input to octave
  /// \param string - given string
  /// \return \a string with dangerous parts cutted out
  public function CheckInput($string)
  {
    // If magic_quotes_gpc is on, we must remove escape slashes from input 
    if (get_magic_quotes_gpc() == 1)
    {
      $string=stripslashes($string);
    }
    //applying security functions
    $string = $this->BasicCuts($string);
    
    //removing leading and finishing spaces/empty lines
    $string = ltrim(rtrim($string));
    
    return $string;
  }
  

  /// Strips dangerous parts of string (string would be treated as a user-defined function)
  /// \param string - given string
  /// \returns \a string with dangerous parts cutted out
  public function CheckFunction($string)
  {
    //applying security functions
    $string = $this->BasicCuts($string);
    
    //removing leading and finishing spaces/empty lines
    $string = ltrim(rtrim($string));
    
    return $string;
  }

  /// Strips dangerous parts of string, (string would be treated as a Octave output)
  /// \param string - given string
  /// \return \a string with dangerous parts cutted out
  public function CheckOutput($string,$userdir)
  {
    //applying security functions
    $string = $this->HtmlElementsCuts($string);
    $string = $this->PathCuts($string);
    $string = $this->OctaveCuts($string);

    $string = $this->AddrsToLinks($string);
    $string = $this->SubToImages($string,$userdir);
    //removing leading and finishing spaces/empty lines
    $string = ltrim(rtrim($string));
    
    return $string;
  }

  /// Strips dangerous parts of string, (string would be treated as a Octave error-summary output)
  /// \param string - given string
  /// \returns \a string with dangerous parts cutted out
  public function CheckErrOutput($string)
  {
    //applying security functions
    $string = $this->HtmlElementsCuts($string);
    $string = $this->PathCuts($string);
    $string = $this->OctaveCuts($string);
    
    // This is not necessary to be seen
    $string = str_replace("Permission denied", "", $string);
    $string = str_replace("error:", "", $string);
    
    // Line and column number is omited since user can not see line number anyway
    // Also we may append something to input, so line number may be invalid
    $string = preg_replace("/near line [0-9]*,? column [0-9]*/", "", $string);
    
    //removing leading and finishing spaces/empty lines
    $string = ltrim(rtrim($string));
    
    return $string;
  }

}



?>

