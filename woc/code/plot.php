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


/// Manages files with plots
class Plot
{

  private $user_dir = '';///< user's working directory

  /// Construct class with plots to be stored in \a _user_dir
  public function __construct( $_user_dir) 
  {

    $this->user_dir = $_user_dir;
 }
  
 
  ///Give names of files with user's plot in chronological order
  ///\return
  ///array of filenames, or empty
  public function PlotFiles()
  {
    // display all graphic files

    //$arrfilenames="";
    for ( $i = 1; file_exists( $this->user_dir."/plot".(string)$i.".png"); $i++ )
    {
      $arrfilenames[$i]=$this->user_dir."/plot".(string)$i.".png";
    }
    if($i>1)
      return $arrfilenames;
  }
  
  /// Removes old plot files from user's directory
  public function RemovePlotFiles()
  {
    $plots=$this->PlotFiles();

    $imax=sizeof($plots);
    for($i=1; $i <= $imax; $i++)
    {
      unlink($plots[$i]);
    }
  }
}

?>

