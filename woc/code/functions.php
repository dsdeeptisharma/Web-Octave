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


/// Manages files with user-defined functions
class Functions
{
  
    private $dir;///<working directory
    private $functions;///< array of function names
    private $count;///< how many functions user has
  
    /// Checks if file exists, if not creates it
    /// \param filename name of a file o check (with relative path)
    private function MakeFileExist($filename)
    {
      if (!file_exists($filename)) 
      {
        $handle = fopen($filename, "w");
        fclose($handle);
      }
    }
    
    /// Extracts all functions from a given directory.
    /// \param _dir   directory, probably belonging to some user
    public function __construct(&$_dir)
    {
      
      $this->dir=$_dir;
      
      //Checking for function files in directory
      $files = scandir ($this->dir);
      // check how many files are there
      $fileno = count( $files );
      
      //fill functions
      $counter=1;
      for ( $i = 0; $i < $fileno; $i++ )
      {
        $file = $files[$i];
        if (strpos($file, '.m',1)) 
        {
//           echo $file;
            $this->functions[$counter]=str_replace(".m","", $file);
            $counter+=1;
        }
      }
      $this->count=$counter;//this many functions we have
    }
    
    /// Displays user's functions, allowing additional formatting with \a beg and \a end
    public function Show($beg = "<BR>",$end= "")
    {
      for ( $i = 1; $i < $this->count; $i++ )
      {
        echo $beg.$this->functions[$i].$end;
      }
    }
    
    /// Displays user's functions, putting names instead of $pattern in given %string
    public function ShowIns($string = "<BR>*</BR>",$pattern= "*")
    {
      for ( $i = 1; $i < $this->count; $i++ )
      {
        echo str_replace($pattern,$this->functions[$i], $string);
      }
    }

    /// Adds function \a name with \a contents - contents of a function file, or changes existing to new content
    /// \a name should contain no dots or spaces, and be correct with octave naming sheme.
    /// Warning: \a contents should be checked against security holes first
    public function Add($name, $contents)
    {
      $filename=$this->dir."/".$name.".m";
      $this->MakeFileExist($filename);
      file_put_contents($filename, $contents);
    }

    /// Deletes function %name and the file belonging to it
    public function Del($name)
    {
      $filename=$this->dir."/".$name.".m";
      Unlink($filename);
    }
    
    /// Returns function file contents
    public function Get($name)
    {
      $filename=$this->dir."/".$name.".m";
      if(file_exists($filename))
      {
        $contents=file_get_contents($filename);
      }
      else
      {
        $contents="This function was not defined before";
      }
      return $contents;
    }
    
    /// Extracts all correctly-defined functions from string, then cuts them out from %string. 
    /// It returns array of function names (%funcarr[%i][0]) and bodys (%funcarr[%i][1])
    public function ExtractAllFunctions(&$string,&$funcarr)
    {
      $this->FunctPattern($pattern,$fno);
      $pattern="/".$pattern."/";
      preg_match_all($pattern,$string,$regs);
      $cntr=0;
      for ( $i = 0; $i < sizeof($regs[$fno]); $i++ )
      {
          $funcarr[$i][0]=$regs[$fno][$i];
          $funcarr[$i][1]=$regs[0][$i];
//           echo "Body:".$funcarr[$i][0]."<BR>";
//           echo "Name".$funcarr[$i][1]."<BR>";
//           echo $fno;
      }
      $string=preg_replace($pattern,"",$string);

    }
    
    
    /// Returns regular expression to extract or validate function definition
    /// \param pattern returns pattern
    /// \param fno returns in which parenthesis pair lies function name
    private function FunctPattern(&$pattern, &$fno)
    {
      // pattern of any amount of spaces, tabulars, end of lines or other blank parameters
      define("PS","[ \n\r\t]*");
      // pattern of function or variable name
      define("PNAME_RAW", "[a-zA-Z_]{1}[a-zA-Z0-9_]*");
      define("PNAME", PS.PNAME_RAW.PS);
      // pattern of parameters (one variable or more separated with comma)
      define("PPARAMS", PNAME."(,".PNAME.")*".PS);
      
      // Function declaration should start like this
      $pattern = PS."[Ff][Uu][Nn][Cc][Tt][Ii][Oo][Nn]".PS."((".
          PNAME."|\[".PPARAMS."\])".PS."=)?".PS."(".PNAME_RAW.")".PS."(\(".PPARAMS."\))?".
          "(.|\n)*?[Ee][Nn][Dd][Ff][Uu][Nn][Cc][Tt][Ii][Oo][Nn]";
      // In which parenthesis pair name of function supposed to be
      $fno = 4;
      // Warning: up to current definition of $pattern, function name would be in fourth parenthesis.
      //          Be aware that if you change $pattern, you should also alter $fno.
    }
    
    /// Checks if function in %string is written in a form allowing extraction of its name
    public function FunctName($string)
    {
      
      $this->FunctPattern($pattern, $fno);
      
      $pattern="/^".$pattern."/";
      
      if (preg_match($pattern,$string,$regs)) 
      {
        return $regs[$fno];
      } 
      else 
      {
        return INVALID_STRING;
      }
    }

}

?>

