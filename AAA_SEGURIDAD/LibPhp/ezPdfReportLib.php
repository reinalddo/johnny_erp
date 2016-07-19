<?php
/**
* ezPdfReportLib
*
* Library file for ezPdfReport
*
* IMPORTANT NOTE
* there is no warranty, implied or otherwise with this software.
* This class uses the excellent R&OS Class called "ezPdf" with some modifications, not documented yet, to get
* the desired behavoir.
*
* LICENCE
* This code has been placed in the Public Domain for all to enjoy.
*
* @author		Fausto Astudillo <fausto_astudillo@yahoo.com>
* @version 	001
* @package	pdfReport
*/

 /**
 *  Group Definition
 */
  class clsGrp
  {
    var $name="";//             Name of the Column
    var $font=NULL;//           Font for Group data
    var $fontSize=NULL;//       Font size for group
    var $type="S";//            Type of grouping function : S=Sum, C=Count, B=both, A=Avg, X=None
    var $sums=Array();//     	Sums for this groups
    var $counts=0;//   	        Count for this groups
    var $break=false;//         Break indicator
    var $currValue = NULL;//    Current value
    var $lastValue = NULL;//    Last value
    var $resume=Array();//      Resume lines, define the column content
    var $linesBefore=0;//       Skip blank lines before Resume Lines (between 1 and 99)
    var $linesAfter=0;//        Skip blank lines before Resume Lines (between 1 and 99)
    var $textCol=false;//       Name of text column for the group
    var $lastRec=Array();//     Last record of data
    var $detail=true;//         Requires  detail lines for this group
    var $beforeEvent=false;//   When a group have a "before group" event
    var $afterEvent=false;//    When a group have an "after group" event
/*
*   Group initiator
*   @access  private
*   @param   string     $pName  Column name for group
*/
    function clsGrp($pName) {
        $this->name = $pName;
        if (function_exists('before_group_' . $pName)) $this->beforeEvent = true;
        if (function_exists('after_group_' . $pName)) $this->afterEvent = true;
    }
} //----------------------------------------------------------------------  End Class clsGrp
 /**
 *  Column definition
 *  */
  class clsCol
{
    var $name="";//              Nombre de columna
    var $type="S";//             Type of data: C=String, N=NUmeric, D=Date - Time, T=TimeStamp
    var $format=NULL;//          Format to aply for every column
    var $head="";//              Header text for column
    var $options=Array();//      Options for column (see ezpdf class definition)
    var $maxLen=-1;//             Longotud maxima, en caracteres;
    var $zeroes=0;//             If the cols with value of 0 must be printed
    var $visible = true;
    var $previous = false;
    var $repeat = true;
    var $acumBreaker=false;//     Accumulative Breaker, the group scope for the acumulator, defaults to any (false)
    var $acumValue=0;//           Accumulative valor for this column, starts in zero.
/**
*   Column initiator
*   @access     public
*   @param      string      pName       Column name
*   @param      string      pType       data type for column
*   @param      string      pFormat     Format for colum,n
*   @param      Bool        pVis        if this column is visible
*   @param      string      pHead       Head of column, if not defined = col->name
*   @param      Bool        pZeroes     Id tjis columns print zeroes
**/
  function clsCol($pName, $pType='S', $pLong=-1, $pFormat=NULL, $pVis=true, $pHead = false, $pZeroes=0, $pRep){
      $this->name = $pName;
      $this->type= strtoupper($pType);
      $this->format=$pFormat;
      $this->maxLen= $pLong;
      $this->zeroes= $pZeroes;
      $this->repeat= $pRep;
      if (!$pHead) $this->head = strtoupper($this->name);
      else $this->head = strtoupper($pHead);

    }
}//--------------------------------------------------------------------- End Class clsCol

/*
*   Class for definition of a text component
    @author		Fausto Astudillo <fausto_astudillo@yahoo.com>
*   @version 	001
*   @package	pdfReport
*/
  class clsText
  {
    var $fontSize;//    Font
    var $font;//
    var $content='';
    var $xpos=0;//        X position of text
    var $ypos=0;//        Y position of text
/*
*   Text initiator
*   @access     private
*   @param  string  $Cont    Text to print
*   @param  string  $pFsize  Font size
*   @param  string  $Fname   font for text
*   @param  numeric $pXpos   x position for text (L=LEFT, R=RIGHT, C=CENTER, integer=exact position)
*   @param  numeric $pYpos   y position for text (IF align = NULL)
*/
  function clsText($pCont,  $pFsize, $pFname=false, $pXpos='L', $pYpos=NULL) {
    $this->content  = $pCont;
    $this->font     = (strlen($pFname)) ? $pFname : "../fonts/Helvetica";
    $this->fontSize = ($pFsize) ? $pFsize : 10;
    $this->xpos     = (strlen($pXpos) > 0) ? $pXpos : 30;
    $this->ypos     = $pYpos;
  }
} //--------------------------------------------------------------------- End Class clsText
?>

