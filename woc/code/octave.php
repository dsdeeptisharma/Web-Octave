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


/// Fires %Octave with given input and stores/returns output
class Octave
 {
   private $output = 'no output';///< output read from %Octave is stored here
   private $diarycommand = '';///< command to add "diary outfile" to the list of %Octave commands
   
   private $plotcommand = '';///< command to allow saving plot output to a file
   private $user_dir = '';///< user's directory
   private $username = '';///< user's username
  
   private $ovr_plot_fctns = array("plot","loglog","mesh","semilogx","semilogy",
                                   "polar","contour", "surf", "bar", "stairs", "errorbar", "plot3"
				  );///< commands used to create plots
  
  /// Construct class for given user
  /// fills \a plotcommand and \a diarycommand
  /// \param _username user's username
  /// \param _user_dir user's working directory
  public function __construct( $_username, $_user_dir) 
  {
  
    $this->username = $_username;
    //umask(0);
    $this->user_dir = $_user_dir;
        
    if(!file_exists ($this->user_dir))
    {
      mkdir($this->user_dir, 0777);
    }
#    if(!file_exists ($this->user_dir."__wio_plot._m"))
#    {
#      copy ("./proc/__wio_plot._m" , $this->user_dir."/__wio_plot._m");
#    }
#    $this->plotcommand = '__gnuplot_raw__ ("set term png large;\n"); __gnuplot_raw__ (["set output ", "\"'.OCTAVE_GRAPH.'\";"]); '."\n";
#    $this->plotcommand = 'global __wio_plot_index=0;'."\n". //image index
#                         'autoload("__wio_plot","__wio_plot._m");'. //load function
#                         'dispatch("plot","__wio_plot","any");'."\n". //change
#                         'autoload("__wio_mesh","__wio_plot._m");'. //load function
#                         'dispatch("mesh","__wio_mesh","any");'."\n"; //change
#    if(CF_PLOTS) 
#    {
#      $this->CreatePlotFctnsFile();
#      $this->plotcommand = $this->GenPlotCommand();
#    }
#      $this->plotcommand = file_get_contents("./proc/__wio_start.m");
  }
   
  /// Run octave with given input
  /// \param query - given input
  /// \return %Octave output
  public function NewQuery($query) 
  {
    //global $OctaveIn, $OctaveOut, $OctaveErr,$UserIn;
    
    //put user's query to file without alteration
    file_put_contents($this->user_dir."/".USER_IN, $query);

    // change the prompt and echo user commands;
    // special tags [OCT_PROMPT_BEGIN] and [OCT_PROMPT_END]
    // are replaced by HTML constructs in OctaveCuts() function
    $query = "\n\n# User's input starts here \n\n PS4('[OCT_PROMPT_BEGIN]\\s>[OCT_PROMPT_END] ');\n echo_executing_commands(1);\n".$query;
    
    // do not dump core file on crashes
    $query = "\n\ncrash_dumps_octave_core(0); octave_core_file_limit(1)\n".$query;
    
    // add "diary outfile" to the list of commands
    
    $query = $this->diarycommand.$query;

    // set graphics file
    
    #query = $this->plotcommand.$query;

    
    if(CF_PLOTS) 
    {
      $query = $this->GenPlotCommand().$query;
      $query = $this->GenPlotFctns().$query;
    }
    // write to infile 
   


    file_put_contents($this->user_dir."/".OCTAVE_IN, $query);
    
    $maxtime = CF_MAXTIME;
    $maxsize = CF_MAXSIZE;
    
    set_time_limit($maxtime);
    
    // run octave; do not allow files greater than 1000*512 bytes
    
    //passthru("cd ".$this->user_dir.";ulimit -f 1000 -m 10000 -t ".$maxtime."; octave -f -V -H --no-line-editing -q ".OCTAVE_IN." > ".OCTAVE_OUT." 2> ".OCTAVE_ERR);
    passthru("cd ".$this->user_dir.";ulimit -f ".$maxsize." -m 10000 -t ".$maxtime."; octave -H --no-line-editing ".OCTAVE_IN." > ".OCTAVE_OUT." 2> ".OCTAVE_ERR."; rm octave-core");
    
    $output = file_get_contents($this->user_dir."/".OCTAVE_OUT);
    
    // return the output
    return "\n".$output;
    
  }

  public function GetOutput()
  {
    $file=$this->user_dir."/".OCTAVE_OUT;
    if(file_exists($file))
      $output = "\n".file_get_contents($file);
    else
      $output="";
    
    // return the output
    return $output;
    
  }

  /// \brief Generates string with overridden plot functions
  function GenPlotFctns()
  {
    $content="\n";
    foreach($this->ovr_plot_fctns as $fctn)
    {
       $content.="function __wio_".$fctn."(varargin)\n".
                 "global __wio_plot_index;\n".
                 "builtin(\"".$fctn."\",varargin{:});\n".
                 "print(strcat(\"plot\", int2str(__wio_plot_index), \".png\") , \"-dpng\");\n";
       if(CF_INLINE_PLOTS)
         $content.="disp(strcat(\"[WIO_plot\", int2str(__wio_plot_index), \".png]\"));\n";
         
       $content.="__wio_plot_index+=1;\n".
                 "endfunction\n\n";
    }
    //file_put_contents($this->user_dir."/__wio_plot._m", $content);
    return $content;

  }
 
  /// \brief Generates necessary startup commands 
  function GenPlotCommand()
  {
    $content="global __wio_plot_index=1;  #image index\n";
             
    foreach($this->ovr_plot_fctns as $fctn)
    {
      $content .= //"autoload(\"__wio_".$fctn."\",\"__wio_plot._m\"); #load function\n".
                  "dispatch(\"".$fctn."\",\"__wio_".$fctn."\",\"any\");    #change\n";
    }
    if(CF_GNUPLOT_FIGURE)
      $content.="figure(1, \"visible\", \"off\");\n\n";
    
    return $content;
  }
  
  /// \return string with error summary output
  public function ShowErrors()
  {
    $file=$this->user_dir."/".OCTAVE_ERR;
    if(file_exists($file))
      $output = "\n".file_get_contents($file);
    else
      $output="";
    
    // return the output
    return $output;
    //global $OctaveErr;
  }
  
  /// Read last input from disk
  /// \return last input
  public function GetLastInput()
  {
    //global $UserIn;
    
    $inputfile=$this->user_dir."/".USER_IN;
    $str="";
    
    //echo $inputfile;
    
    if(file_exists ($inputfile))
    {
      $str = file_get_contents($inputfile);
    }
    return $str;
  }
  
  /// Checks if last input is available
  public function AvailLastInput()
  {
    //global $UserIn;
    
    $inputfile=$this->user_dir."/".USER_IN;
    if(file_exists ($inputfile))
    {
      return true;
    }
    else
    {
      return false;
    }
  }
}

 ?>
    
