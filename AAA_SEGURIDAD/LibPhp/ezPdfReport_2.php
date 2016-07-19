<?
/** 
* ezPdfReport
*
* A PHP class to create a basic tabular pdf reports acquiring data from a resultset
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
* @rev		fah 10/11/09  Agregar evento before ROw
*/ 
//error_reporting(E_ALL);
if (!defined("SID")) session_start();
/*
include("/SERVER/PHP/includes/adodb/adodb.inc.php");
include("/SERVER/PHP/includes/general/General.inc.php");
include("/SERVER/PHP/includes/general/GenUti.inc.php");
include("../LibPhp/ezPdfReportLib.php");
include('/SERVER/PHP/includes/general/class.ezrpdf.php');
*/
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
include("../LibPhp/ezPdfReportLib.php");
include('class.ezrpdf.php');

$alBlankLine = Array();
$acols = Array();
$agrps = Array();
$data  = Array();
$fmtRec  = Array();
if (!isset($db)){
    $db = NewADOConnection("mysql");        //      Define a global connection.
    if (fGetParam('pTip', 'pdf') == "excel") $db->SetFetchMode(ADODB_FETCH_NUM); // fah Feb/07/07
    else $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
    $db->debug=fGetParam('pAdoDbg', 0);
}
class ezPdfReport
{
    var $rs = NULL;//
    var $title=false;//         Contains text for the Title of document
    var $subTitle=false;//      Contains text for the Sub Title of document
    var $condition=false;//     Contains text for a comment / condition line
    var $titleOpts=Array();//   Options for Title
    var $groups=Array();//      Report Groups definition
    var $font="Helvetica";//    Report Font
    var $fontSize=10;//         Report Font size
    var $pageSize="A4";//       Report PAge Size
    var $orientation="portrait";// Report orientation
    var $columns=Array();//     Definition of Columns
    var $colHead=Array();//     Column Headers Text
    var $printColHead = true;// Flag for printing the column headers.
    var $rptOpt=Array();//      General Report Options
    var $colOpt=Array();//      Column options. Global, default for every Group. (see col options at ezTable class)
    var $beforeRow = false;	// if this report have a beforeRow event
	var $pdf=NULL;//		    pdf content
	var $margins=array(0.5,1,1,1);//  Report MArgins in Cms (top,bottom,left,right)
	var $pagnumpos=Array();//  a, y position of page numerator
	var $brkLevel=0;
	var $lastGrp=false;//       Last group processing flag
	var $firstGrp=true;//       First Group processing flag
	var $firstRec=true;//       First Record processing flag
	var $noDataMsg="NO EXISTE INFORMACION PARA GENERAR EL REPORTE\n (Debido a que la condicion de busqueda no arrojo resultados)";
	var $stop = false; //       Flag to continue/stop  operation 
	var $leftBorder = 0;
	var $printFooter=true;//    Flag for printing a foot line (user, date an page number)
	var $typeOut="pdf";
	var $gridContent =""; //    Contenido de los datos base en tabla html (para excel)
/**
*   Report Initiator
*   @access  private
*   @param  string  $pPage      page Size
*   @param  string  $pOrient    Orientation of page
*   @param  string  $pFont      Name of the General Font
*   @param  string  $pSize      Font Size
*   @return void
*/
    function ezPdfReport($pRs, $pPage="letter", $pOrie="portrait", $pFont="./fonts/Helvetica", $pSize="10") {
        if (is_null($pRs) or !isset($pRs)) {
            echo "DEBE DEFINIR UN ORIGEN DE DATOS VALIDO";
            return;
        }
        $urlFN = fGetParam('pFon', false);   //                                    Font Name from URL
        $urlPO = fGetParam('pPos', false); //                                      Page Orientation  from url
        $pOrie= ($urlPO) ? $urlPO : $pOrie;
        $this->rs = $pRs;
        $slType =fGetParam('pTip', false);
        $this->typeOut = (strlen($slType)>1) ?  $slType : "pdf";  //                           Fah Feb/07/07  tipo de salida: excel �PDF
        $this->pageSize=$pPage;
        $this->orientation=$pOrie;
        $this->font = $pFont; //                                                 Font Size at Design time
        $this->font = $pFont; //                                                 Font Size at Design time
        $this->fontSize = $pSize;
        $this->addGrp('general');
        $this->groups['general']->type = 'B';
        $this->_colsFromRs();
        $this->pdf = & new Cezrpdf($this->pageSize,$this->orientation);
        if ($this->typeOut == "excel")
	{  //                                     Fah Feb/07/07
            include_once('baaGrid.php') ;
        }
    }
/**
*   Define basic info from column from Recordset. Take name, type and length, defines justificsation and width
*   access  private
*/
    function _colsFromRs() {
       	$i = 0;
       	$lacols=$this->rs->fetchrow();
       	if (!$lacols) {
               $this->stop=true;
                return;
        }
        $this->rs->movefirst();
        foreach($lacols as $col => $val) {//              Populate the columns from recordset info
          $olf = $this->rs->FetchField($i);
          $slt = $this->rs->MetaType($olf->type);
          $fmt=NULL;
          if ( $slt = 'C' && is_numeric($val)) $slt='N';
          switch ($slt){
            case "N":
            case "R":
                $this->colOpt[$col]['justification']= 'right';//        Default column options for num fields
                $fmt="2";
                break;
            case "I":
                $this->colOpt[$col]['justification']= 'right';//        Default column options for int fields
                $fmt="0";
                break;
            case "D":
                $this->colOpt[$col]['justification']= 'center';//        Default column options for date fields
                $fmt="d-m-y";
            case "T":
                $this->colOpt[$col]['justification']= 'center';//        Default column options for date fields
                $fmt="d-m-y";
                break;
            case "C":
            default:
                $this->colOpt[$col]['justification']= 'left';//        Default column options for char fields
                break;
          }
    	  $this->addCol($col, $slt, $olf->max_length, $fmt, true, "", false, true);
    	  +$i;
    	}
    }
/**
*   Initialization of group values
*   @access  public
*   @param   string     $key  name of group to initialize; if null, aplies to all groups
*/
    function _iniGrps($key=false) {
    	if (!$key) $akeys=array_keys($this->groups);
    	else $akeys=array($key);
        $acols=array_keys($this->columns);
        foreach($acols as $col){
        	foreach($akeys  as  $grp) {
			    $this->groups[$grp]->sums[$col] = 0;    //              Initialize sums for every column
                $this->groups[$grp]->counts = 0;                //              Initialize counter for the group
            }
//print_r($this->columns[$col]);
//echo "<br>";
            $this->columns[$col]->previous = NULL;
        }
        unset($acols);
        unset($akeys);
    }
/**
*   Set some option defaults for report
*   @access  private
*   @return  void
*/
    function _rptDefs(){
        $urlFS = fGetParam('pSiz', false); //                                      Font Size from url, at runtime
        if ($urlFS ) {
            if ($urlFS <> $this->fontSize) {;//                Resize the col widths accordin to new FontSize
                $acols=array_keys($this->columns);
                foreach($acols as $col){
                    $this->colOpt[$col]['width'] = ($this->colOpt[$col]['width']  * $urlFS) / $this->fontSize;
                }
                $this->fontSize=$urlFS;//                                       Font Size at Run time
            }

        }
        $this->rptOpt['fontSize'] = $this->fontSize;
//        $this->rptOpt['xOrientation']='left';
        if (!isset($this->rptOpt['colGap']))       $this->rptOpt['colGap'] =2;
        if (!isset($this->rptOpt['rowGap']))       $this->rptOpt['rowGap'] =0;
        if (!isset($this->rptOpt['fontSize']))       $this->rptOpt['fontSize'] =       $this->fontSize;
        if (!isset($this->rptOpt['titleFontSize']))  $this->rptOpt['titleFontSize'] =  $this->fontSize;
        if (!isset($this->rptOpt['showHeadings']))   $this->rptOpt['showHeadings'] =   1;
        if (!isset($this->rptOpt['shaded']))         $this->rptOpt['shaded'] =         1;
        if (!isset($this->rptOpt['showLines']))      $this->rptOpt['showLines'] =      1;
//        if (!isset($this->rptOpt['maxWidth']))       $this->rptOpt['maxWidth'] =       800;
        if (!isset($this->rptOpt['maxWidth']))       $this->rptOpt['maxWidth'] = $this->ez['pageWidth'] - $this->ez['rightMargin'] - $this->ez['leftMargin'];
        if (!isset($this->rptOpt['innerLineThickness'])) $this->rptOpt['innerLineThickness'] = 1;
        if (!isset($this->rptOpt['outerLineThickness'])) $this->rptOpt['outerLineThickness'] = 1;
        if (!isset($this->pagnumpos[0]))             $this->pagnumpos[0] = 0;
        if (!isset($this->pagnumpos[1]))             $this->pagnumpos[1] = 700;
        
        if (!isset($this->titleOpts['T']['fontSize'])) $this->titleOpts['T']['fontSize']=$this->fontSize +2;     // si no se ha definido el tama� para Titulo
        if (!isset($this->titleOpts['S']['fontSize'])) $this->titleOpts['S']['fontSize']=$this->fontSize;      // si no se ha definido el tama� para SubTitulo
        if (!isset($this->titleOpts['C']['fontSize'])) $this->titleOpts['C']['fontSize']=$this->fontSize -2;      // si no se ha definido el tama� para Condicion

        if (!isset($this->titleOpts['T']['justific'])) $this->titleOpts['T']['justific']='center';
        if (!isset($this->titleOpts['S']['justific'])) $this->titleOpts['S']['justific']='center';
        if (!isset($this->titleOpts['C']['justific'])) $this->titleOpts['C']['justific']='left';

        if (!isset($this->titleOpts['T']['leading'])) $this->titleOpts['T']['leading']=$this->titleOpts['T']['fontSize'] +2;
        if (!isset($this->titleOpts['S']['leading'])) $this->titleOpts['S']['leading']=$this->titleOpts['S']['fontSize'] +2;
        if (!isset($this->titleOpts['C']['leading'])) $this->titleOpts['C']['leading']=$this->titleOpts['C']['fontSize'] +2;
        $this->rptOpt['firstFlag']=true;            //Initial value for first flags, trun false after printing a group
        reset($this->columns);
        // For every column, compute the width (based on his maxlen) if it have not defined previously
        if ($this->stop) return;
        foreach ($this->columns as $slKey => $oCol) {
            if (!is_a($oCol, "clscol")){  // to void the 'property name is indefined' message
				echo "LA COLUMNA o ATRIBUTO '" . $slKey . "' ESTA MAL DEFINIDA <br>";
            }
            else {
				$col = $oCol->name;
	            if (!isset($this->colOpt[$col]['width'])) {
	                if ($oCol->maxLen > 0) { // compute the col width
	                    $ilFsize=$this->fontSize;
	                    if (isset($this->rptOpt['titleFontSize'])) $ilFsize = $this->rptOpt['titleFontSize'];
	                    $this->colOpt[$col]['width']= $this->textWidth($ilFsize, str_pad("0", $oCol->maxLen, "O"));
	                }
	                else    $this->colOpt[$col]['width']= 100;  // default col width
	            }
            }
        }
        $this->rptOpt['cols']=$this->colOpt;
    }

/**
*   Process every row of the recordset
*   @access  private
*   @return  pdf code for render the report
*/
    function _processRows() {
        global $alBlankLine;
        global $acols;
        global $agrps;
        global $data;
        global $fmtRec;
        $idat=0;
        $ilbreaks=0;
        $this->firstGrp=true;
        $this->last=false;
//        if (is_array($this->columns)) $acols=array_keys($this->columns); //   array of column names
//        if (is_array($this->groups)) $agrps=array_keys($this->groups);  //   array of group names
        $record=array(); //                     data record
        $fmtRec=array(); //                     formatd record, ready to print
        while (!$this->rs->EOF) {
            $grp=count($this->groups);
            $ilbreaks=0;
            $record = $this->rs->FetchRow();// -----------------                    DATA RECORD TO PROCESS
            $this->groups['general']->counts +=1;//                                 Increment General record counting
            $this->brkLevel=0;
	    if($this->beforeRow=true) before_row($data, $record); // #fah 16/11/09
            $ilbreaks=$this->_checkGroups($agrps, $fmtRec, $record, $data);
            if ($idat == 0 && $this->firstGrp) { //                                 For first line, process all headers before data
                    foreach ($agrps as $slGroup) {
						$this->groups[$slGroup]->lastRec = $record;
                    	if ($slGroup <> "general") {
                    		$this->groups[$slGroup]->currValue = $record[$slGroup];
                            if ($this->groups[$slGroup]->beforeEvent ) {
                                $this->_processEvent('before_group_', $slGroup); //   process the header
                            }
                    	}
                    }
                    $this->firstGrp = false;
            }
            $idat++;
            if ($ilbreaks ) {
                $this->_processBreak($data, $acols, $agrps);
                unset($this->rptOpt['before']);
                if ( $this->pdf->y <= $this->pdf->ez['bottomMargin'] ||
                    (isset($options['minRowSpace']) &&
                    $this->pdf->y <= ($this->pdf->ez['bottomMargin']+$options['minRowSpace'])) ){ //               Check if exist enought space for  continue in the page
                    $this->rptOpt['firstFlag']=true; //                                                             make sure that next table begeins with header
                }
                else $this->rptOpt['firstFlag']=false; //                                                    next table in the same page, not begins with header
                unset($data);
                $idat=0;
//  __ correc
                $ilcount=count($agrps)-1;
                for ($l=$this->brkLevel; $l<=$ilcount; $l++){
                    $slGroup=$agrps[$l];
                    if ($this->groups[$slGroup]->beforeEvent ) {
                        $this->_processEvent('before_group_', $slGroup); //   process the group headers for next section
                    }
                }
//
            }
	    $data[] = $fmtRec;            
            $this->_accumulate($record);

            $this->firstRec=false;
            if ($this->rs->EOF) $this->lastGrp=true;//                                 To force the last group after last record
    	}
	$slFuncName= 'before_footer';
	if (function_exists($slFuncName)) {
	    $slFuncName($this, $obj);//                             call the before footer event
	}
    	$ilbreaks=$this->_checkGroups($agrps, $fmtRec, $record, $data); // process the last groups /mainly for set the group->currvalue
        $this->brkLevel=0; //                       Force To process the general group
        $this->_processBreak($data, $acols, $agrps);
        unset($this->rptOpt['before']);
    	return $this->pdf;
    }
/**
*   Process a column break
*   @access     private
*   @param      array   $data        Reference to an array containing the actual data records
*   @param      array   $acols       The actual column names (ByRef)
*   @param      array   $agrps       The actual group names (ByRef )
*   @return     void
*/
    function _processBreak(&$data, &$acols, &$agrps)  {                     // process the group break
        global $alBlankLine;
        reset($this->groups);
        $flag = false;
        reset ($acols);
        $ilcount = count($this->groups) - 1; //                              Total Num of groups
        $ilFinal= $this->brkLevel; //                                        Level that fires the break
        for ($l=$ilcount; $l>=$ilFinal; $l--){ //                            Reverse scanning of groups
                $flag = true;
                $col=$agrps[$l];
                $group=$this->groups[$col];
                $j=count($group->resume);
                $this->skipLines($data, $group->linesBefore);
                if ($j && $this->groups[$col]->counts > 1) { //  if exists resume line(s)
                    for ($i=0; $i<$j; $i++) {
                        reset ($acols);
                        $vlColValue = '';
                        foreach ($acols as $colname) {
                            if (!is_a($this->columns[$colname], "clscol"))  continue;  // to void the 'property name is indefined' message
                            if($this->columns[$colname]->visible) { //                  For every visible column define the aggregate value
                                switch($group->resume[$i][$colname]) {
                                    case 'S':
                                        $vlColValue =$group->sums[$colname];
                                        break;
                                    case 'A':
                                        $vlColValue =$group->counts <>0 ? round($group->sums[$colname] / $group->counts,2) : '??';
                                        break;
                                    case 'C':
                                        $vlColValue =$group->counts;
                                        break;
                                    case '-':
                                    default:
                                        $vlColValue =' ';
                                        break;
                                }
                                $alLine[$colname] = $this->_formatCol($colname, $vlColValue);
                            }
                        }
                        $slCol = $group->textCol ? $group->textCol : $group->name;  //                      if group's text column is not defined, use the group's name as text col's name
                        if ($slCol && $group->resume[$i]['resume_text']) $alLine[$slCol]= $group->resume[$i]['resume_text'];
                        if (!$this->groups[$col]->detail) $data=NULL; //
                        $data[]= $alLine;                    //                                             Put resume data in  buffer
                        unset($alLine);
                    }
                }
                $this->skipLines($data, $group->linesAfter);
                $this->groups[$col]->break = false;
                if (count($data) > (NZ($group->linesAfter) + NZ($group->linesBefore)) ) {
                    $ilHead=$this->rptOpt['showHeadings'];
                    $ilShow=$this->rptOpt['showLines'];
//echo "<br> $group->name " . $group->counts;
//echo "<br> $group->name " . $this->lastGrp;
                    if ($this->lastGrp and $group->name == 'general') {
    	                $this->rptOpt['showHeadings']=0;
    	                $this->rptOpt['showLines']=0;
    	            }
                    $this->pdf->ezTable($data,$this->colHead,'', $this->rptOpt);
                    $this->rptOpt['showHeadings'] = $ilHead;
                    $this->rptOpt['showLines'] = $ilShow;
                }
                $data=null;
                $data = Array();
                if ($this->groups[$col]->afterEvent ) {
                    $this->_processEvent('after_group_', $group->name); //                         process a event
                }
                $this->_iniGrps($col);//                                                Initialize cummulatives
            }
    }
/**
*   Skip $plin blank lines
*   @access  private
*   @param   array     $data        data Array
*   @param   integer    $plin       Number of lines to Skip
*   @return  void
*/
function skipLines(&$data, $plin) {
    global $alBlankLine;
    for ($i= 1; $i<=$plin; $i++) {
        $data[]=$alBlankLine; //                                             adds a blank lines to data array
    }
}
/**
*   Event Before/After procesor: determines if a function for processing before or after a group break exists then calls it
*   The function must be called "before_XXXX" or "after_XXX", where XXXX = groupname; must receive a group object and return
*   text string to add at current position in pdf output.
*   @access  private
*   @param   string     $pType      Type of event
*   @param   string     $pGrp       Group name
*   @return  void
*/
    function _processEvent($pType, $pGrp='') {
        $slFuncName=$pType . $pGrp;//                                               name of the function to call ('event' After Group)
        if (strlen($pGrp))   $slFuncName($this, $this->groups[$pGrp]);//  			call the function to simulate "Before/After Group Event"
    }

/**
*   Analizes the break of columns, and assign to every column the formated data
*   @access  private
*   @param   array      $agrps       Array of group names (reference)
*   @param   array      $fmtRec      Current formated data rec (reference)
*   @param   array      $record      Current data record (reference)
*   @return  integer    $ilbreaks    Number of colum breaks
*/
    function _checkGroups(&$agrps, &$fmtRec, &$record, &$data) {
        $ilbreaks = 0;
        foreach ($record as $col => $val) {//                            FOR Every Field in record
            if(isset($this->groups[$col]) ) { //                                When  a group for this column exist analize except for first record
                $this->groups[$col]->counts +=1;//                              Increment Record counting for group
                $this->groups[$col]->lastRec=$record;
                if (!$this->firstRec) { //                                                   If is not the first record
                    if ($this->groups[$col]->currValue != $record[$col] || $this->lastGrp){ //                                               If the column value has changed or it is the last record
                        $this->groups[$col]->lastValue = $this->groups[$col]->currValue;
                        $this->groups[$col]->break = true;  //                              break Flag for this col group
                        $ilbreaks+=1; //                                                    Col change counter
                        $brkLevel = array_search($col, $agrps);//                           Level of break, for recursive process
                        if (!$this->brkLevel || $this->brkLevel > $brkLevel) $this->brkLevel = $brkLevel ; //   point to lowest level
                    }
                }
                $this->groups[$col]->currValue = $record[$col]; //          Set the current value for the column break
            }
//          If there is an acumulator that breaks in any group or in a specific group, resets it to zero

	    if ( $ilbreaks){ $this->columns[$col]->acumValue =0;}
/**/
            $j = count($data);
            $flag = false;
            if (strlen($val) > 0) {//                                         If there is some data in column
                if ($j >= 1 && $this->columns[$col]->visible && !$this->columns[$col]->repeat) {
        			    if ($this->columns[$col]->previous == $val) { //                     Put " in repetead text
                            $k=round(strlen($val)/2);
        					$fmtRec[$col] = str_pad('"', $k, ' ', STR_PAD_LEFT);
        					$flag = true;
        	            }
    	           }
    	        if (!$flag && $this->columns[$col]->type == 'A') {  // Value for 'Acummulative Columns.
    	                $this->columns[$col]->acumValue += $record[$col];
    	                $fmtRec[$col] = $this->_formatCol($col, $this->columns[$col]->acumValue);
                    }
                else
                    if (!$flag && $this->columns[$col]->visible) $fmtRec[$col] = $this->_formatCol($col, $record[$col]); // Assign the current value from data
            }
           
            else $fmtRec[$col] = '';
            if ($ilbreaks>0) $this->columns[$col]->previous = NULL; //  if there is a group break, nulls to prev value
            else $this->columns[$col]->previous = $val;
        }
        return $ilbreaks;
    }
/**
*   Generate the final text that appears in column
*   @access  public
*   @param   $col       Column name to process
*   @param   $val       Value to format
*   @return  void
*/
    function _formatCol($col, $val){
        $sltxt="";
        switch ($this->columns[$col]->type) {
            case 'N':
            case 'A':
            case 'I':
            case 'R': //                      NUmeric processing
                if (is_numeric($val)) {
                    if (!$this->columns[$col]->zeroes && $val == 0 ){
                         $sltxt =' ';
                    } else {
                        if ($this->columns[$col]->format){
                            $long=null;
                            $prec=null;
                            $decim=null;
                            $separ=null;
                            $negat=false;
                            $zero=0;
                            $ilcount = substr_count($this->columns[$col]->format, ":");
                            switch ($ilcount) {
                                case 5://       negative string
                                    list($long,$prec,$decim,$separ,$zero,$negat) = explode(":",$this->columns[$col]->format);
                                    break;
                                case 4://       zero string
                                    list($long, $prec,$decim,$separ,$zero) = explode(":",$this->columns[$col]->format);
                                    break;
                                case 3:
                                    list($long, $prec,$decim,$separ) = explode(":",$this->columns[$col]->format);
                                    break;
                                case 2:
                                    list($long, $prec) = explode(":",$this->columns[$col]->format);
                                    break;
                                case 1:
                                    list($long) = explode(":",$this->columns[$col]->format);
                                    break;
                                case 0:
                                    $long = NZ(intval($this->columns[$col]->format),8);
                                    break;
                            }
                            if (is_null($long))  $long = 8;   //                      Default long
                            if (is_null($prec))  $prec = 0;   //                      Default precision
                            if (is_null($decim)) $decim = '.'; //                      Default decimal sep.
                            if (is_null($separ)) $separ = ''; //                      Default thousands sep.
                            if ($val == 0.00 || $val == 0) {
                                $sltxt = $zero;
                            }
                            else $sltxt = substr(number_format($val, $prec, $decim, $separ), -$long);
                             #$sltxt = substr(number_format($val, $prec, $separ, $decim), -$long);
 //fah_bug
                             $sltxt = number_format($val,$prec,$decim,$separ);
                            return $sltxt;
                        }
                    }
                }
                else $sltxt = $val;
                break;
            case 'D': //                      Date processing
                if (!$this->columns[$col]->format && strlen($val) > 0 )
                        $sltxt = fTex2Fecha($val,$this->columns[$col]->format);
                break;
            case 'T': //                      TimeStamp Processing
                if (!$this->columns[$col]->format && strlen($val) > 0 )
                        $sltxt = date($this->columns[$col]->format, $val);
                break;
            case 'C': //                      Capitalized String
                $sltxt=ucwords(strtolower($val));
                break;
            case 'c': //                      String in Lowercase
                $sltxt=strtolower($val);
                break;
            case 'U': //                      String in Upperrcase
                $sltxt=strtoupper($val);
                break;
            default:
                if (is_numeric($val)) {
                    $this->groups['general']->sums[$col] += $val;
                    if (!$this->columns[$col]->zeroes && ($val+0) == 0 ) $sltxt ==' ';
                }
                else
                    $sltxt=$val;
                break;
        }
        return $sltxt;
    }
/**
*   acumulate the col values
*   @access  public
*   @param   array      $rec       record to acumulate
*   @return  void
*/
    function _accumulate(&$rec){
        $cols=array_keys($this->columns);
        $grps=array_keys($this->groups);
        foreach($cols as $col) {
            foreach($grps as $grp) {
//echo "<br>" . $grp . " - " . $col . " : " . $this->groups[$grp]->sums[$col];
                if (isset($rec[$col]) && is_numeric($rec[$col]) ) {
		    $this->groups[$grp]->sums[$col] += $rec[$col];
		    }
                }
            }
    }


/**
*   Report Ejecution
*   @access  public
*   @return  pdf code for render the report
*/
    function run(){
        global $alBlankLine;
        global $agrps;
        global $acols;
        if ($this->typeOut=="excel") { //                           Fah Feb/07/07
            $this->hideColumns();
            $this->excelOut();
            return;
        }
	
	if(function_exists("before_row")) $this->beforerow=true; // #fah 16/11/09
	else $this->beforerow=false;
    	$this->pdf->selectFont('../fonts/'. $this->font);
    	if (count($this->margins) ==2)	$this->pdf->ezSetCmMargins($this->margins[0], $this->margins[1]) ;
    	if (count($this->margins) ==4)	$this->pdf->ezSetCmMargins($this->margins[0], $this->margins[1], $this->margins[2], $this->margins[3]);
    	$this->_rptDefs();
        if (!$this->stop) $this->_iniGrps();	
        if (function_exists('before_report')) before_report($this);
        $this->rptHeader($this->pdf);
        if (!$this->stop) $this->rptFooter();
        if (!$this->stop) $this->hideColumns();
        if (!$this->stop) {
            if (is_array($this->columns)) $acols=array_keys($this->columns); //   array of column names
            if (is_array($this->groups))  $agrps=array_keys($this->groups);  //   array of group names
            reset($acols);
            foreach ($acols as $colname) $alBlankLine[$colname] = " ";       //   a blank line
            $this->_processRows();
            }
        if ($this->stop)  $this->pdf->ezText($this->noDataMsg, $this->titleOpts['T']['fontSize'],array('justification'=>'center',
                                                                             'leading'      =>$this->titleOpts['T']['leading']));
    }
/**
*   Output generated pdf code
*   @access  public
*   @param   bool       $pDbg   Debugging flag
*   @return  pdf code for render the report
*/
    function output($pDbg=0) {
        return $this->pdf->ezOutput($pDbg);
        
    }
/**
*   Displays the pdf content on browser screen
*   @access  public
*   @return  void
*/

    function preview(){
//    if (preg_match("/MSIE/i", $_SERVER['HTTP_USER_AGENT']))  $this->view($this->title, $this->saveFile("CON_COM_"));
//    else

/* //$slOut = $this->pdf->output();
////header("Content-type: application/pdf");
//header('Content-type: application/vnd.ms-excel');
//header("Content-disposition: inline; filename=reporte.pdf");
//header("Content-length: " . strlen($slOut));
$opt['Content-type'] ='application/pdf';
$opt['Content-disposition'] ='inline; filename=test.pdf';
$opt['Content-length'] =strlen($slOut);
      $this->pdf->ezStream($opt); // no funciona en IE
//echo $slOut;
*/
    $this->view($this->title, $this->saveFile("CON_COM_"));
}

/**
*   Saves a file with the pdf code. It receive a filename prefix, adds a random sufix and creates the file
*   @access  public
*   @param   string       $pFile    Prefix for file name
*   @return  complete file name
*/
    function saveFile($pFile='output') {
        $pdfcode = $this->pdf->ezOutput();
        //echo $pdfcode;
        $dir = '../pdf_files';
        //save the file
        $w = 0 ;
        if (!file_exists($dir)){
            mkdir ($dir,0777);
        }
        mt_srand();
        $fname = $dir . "/" . $pFile . mt_rand(1,5000) . '.pdf';
        $fp = fopen($fname,'w');
        fwrite($fp,$pdfcode);
        fclose($fp);
        return $fname;
    }

/**
*   Displays the pdf content
*   @access  sgTitulo       Titulo de ventana
*   @param   fname          Nombre de archivo a generar y presentar
*   @param   fContent       Contenido
*/
    function view($sgTitulo="REPORTE", $fname){ //                      fah Feb/07/07
        global $db;
        if ($this->typeOut != "pdf" && strlen($this->gridContent) ==0) return; // salir si el contenido est�vac�
        $wname = str_replace(".","",substr(basename($_SERVER['SCRIPT_NAME']),0,14));
        $slHtml= '<html>
        <title> ' . $sgTitulo. ' </title>
        <head>
        <link rel="stylesheet" type="text/css" href="../Themes/Cobalt/Style.css">
        <script language="JavaScript1.3" src="../LibJava/browser_detect.js"></script>
        <script language="JavaScript1.3" src="../LibJava/general.js"></script>
        <script language="JavaScript1.3">
        function fGenMail() {
            pOpc="status=yes,titlebar=no,toolbar=no,menubar=no,scrollbars=yes, alwaysRaised=1,dependant";
            slUrl = "../Ge_Files/GeGeGe_mail.html?pFrom=' . $_SESSION['email'] . '&pFile=' . $fname .
					'&pCopia=' .$_SESSION['email'] . '"
//            document.location.replace(slUrl);
           fAbrirWin(slUrl, "wMail", pOpc, 600, 250)
        }
        function fGenExcel() {
            pOpc="status=yes,titlebar=no,toolbar=no,menubar=no,scrollbars=yes, alwaysRaised=1,dependant";
            slUrl = document.location.protocol + "//" + document.location.hostname + "/" +
                    document.location.pathname + 
                    document.location.search + "&pTip=excel";
            //document.location.replace(alUrl);
            document.getElementById("divText").innerHtml=slUrl;
            document.location.replace(slUrl);
//            alert(alUrl);
//            window.frames["fr2"].location.replace("about:blank");
//            window.frames["fr2"].location.replace(alUrl)
//            fAbrirWin(slUrl, "wExcel", pOpc, 900, 800)
        }
        function go_now () { ' ;
        if ($this->typeOut == "pdf") {
                $slHtml .= 'window.frames["fr2"].location.replace("'. $fname . '")';
        }
        $slHtml .=  '}
        </script>
        </head>
        <body style="font-size=9" onLoad="go_now()"; > ' ;
        if (strlen($this->gridContent) > 1)
            $slHtml .= '<div id="divText"></div><div id="divContent" >' . $this->gridContent . '</div>';
        else {
            $slHtml .= '<input type="button" onclick="fAbreArchivo()" VALUE="ARCHIVO" title="Si no ve el documento es pantalla, presione este boton para Abrir directamente el archivo Pdf del reporte">
            		<input type="button" onclick="fGenMail()" VALUE="Correo" title="Envia por correo este reporte">
            		<input type="button" onclick="fGenExcel()" VALUE="Excel" title="Presenta los datos Base de reporte para ser exportados a excel">
                    <div id="divText"></div>' ;
            $slHtml .= '<iframe name="fr2" frameborder="1"  margin:1; style="WIDTH: 100%; HEIGHT: 650px" source=""/>';
        }
        $slHtml .= '</body>';
        echo $slHtml;
        $this->gridContent = "";
    }
    
/**
*   Define as hidden column those not included in colHead array
*   @access  public
*   @return  void
*/
    function hideColumns() {
        foreach($this->columns as $oCol) {
            if (!is_a($oCol, "clscol"))  continue;  // to void the 'property name is indefined' message
            if (!isset($this->colHead[$oCol->name])) $this->columns[$oCol->name]->visible=false;
        }
    }
/**
*   Aplies  a value for a column option trough all columns
*   With This method, you can set a common column OPTION for all columns without setting it
*   individually for every column. Just redefine the OPTION for those columns that have
*   different option value.
*   @access  public
*   @param   string     $pOpt   Option to set
*   @param   string     $pVal   Value for pOpt
*   @return  void
*/
    function setDefaultColOpt($pOpt=false, $pVal=false) {
        if ($pOpt && $pVal) {
            $acols = array_keys($this->columns);
            reset($acols);
            foreach($acols as $col) {
                $this->colOpt[$col][$pOpt] = $pVal;
            }
            unset($acols);
        }
    }
    
/*  Aplies  a value for a column Property trough all columns
*   With This method, you can set a common column property for all columns without setting it
*   individually for every column. Just redefine the property for those columns that have
*   different property value.
*   @access  public
*   @param   string     $prop   Property to set
*   @param   string     $pVal   Value for property
*   @return  void
*/
    function setDefaultColPro($pPro=false, $pVal=false) {
        if ($pPro && $pVal) {
            $acols = array_keys($this->columns);
            reset($acols);
            foreach($acols as $col) {
                $this->columns[$col]->$pPro = $pVal;
            }
            unset($acols);
        }
    }

/**
*   New Header Line Definition
*   @access  public
*   @param   string     $pCCont     Line Contents
*   @param   variant    $pXpos      X coordinate for text position ( or L/R/C for LEFT, Rigth, Center)
*   @param   integer    $pFsize     Font Size
*   @param   string     $pFname     Font Name for this line
*   @param   integer    $pYpos      Y coordinate for text position
*/

    function addHeaderLine($pCont,  $pXpos='L',  $pFsize=false, $pFname=false, $pYpos=NULL ) {
        if (!$pFname) $pFname = $this->font;
        if (!$pFsize) $pFsize = $this->fontSize;
        $oline  = new clsText($pCont, $pFsize, $pFname,  $pXpos, $pYpos=NULL);
        $this->header[]=$oline;
    }
/**
*   New Column definition
*   @access  public
*   @param   string     $pName  name of new column
*   @param   string     $pType  data type for column
*   @param   string     $pFmt   format for column
*   @param   string     $pVis   visibility for column
*   @param   string     $pRep   repeat duplicates for column
*/
    function addCol($pName, $pType='C', $pLen=-1, $pFmt=false, $pVis=true, $pHead="", $pZero=true, $pRep=true ) {
        if (!isset($this->columns[$pName]))    $this->columns[$pName]= new clsCol($pName, $pType, $pLen, $pFmt, $pVis, $pHead, $pZero, $pRep);
        else echo " YA EXISTE UNA COLUMNA '$pName'";
    }
/**
*   Set the visible attribute for a column
*   @access  public
*   @param   string     $pName  name of the column column
*   @param   Bool       $pVis   Value of Visibility property
*/
    function setVisible($pName, $pVis=true ) {
        if (isset($this->columns[$pName]))    $this->columns[$pName]->visible =$pVis;
    }
/**
*   New Group definition
*   @access  public
*   @param   string     $pGrp  name for group (must be a column name)
*/
    function addGrp($pGrp) {
        if ($this->stop) return;
        if (!isset($this->groups[$pGrp])) {
            if (isset($this->columns[$pGrp]) || strtolower($pGrp)=='general') {
                $lGrp = new clsGrp($pGrp);
                $lGrp->font = $this->font;
                $lGrp->fontSize = $this->fontSize;
                $this->groups[$pGrp]= $lGrp;
                unset($lGrp);
            }
            else  echo " $pGrp DEBE SER UN NOMBRE DE COLUMNA PREVIAMENTE DEFINIDO";
        }
        else echo " YA EXISTE UN GRUPO LLAMADO '$pGrp'";
    }
/**
*   Add a Resume line to the group header / footer definition
*   @access     public
*   @param      string      $pGrp   Group name
*   @param      string      $pAgg   Aggegate function to aply
*   @param      string      $pTxt   Text to present in resume line
*   @param      integer     $pBll   Blank Line Definition for resume: 0=Nothing, 1=Before, 2=After, 3=Both
*   @return     void
*/
    function addResumeLine($pGrp, $pAgg='S', $pTxt="", $pBll=0) {
        $alres= array();
        $alres['resume_text'] = $pTxt;
        $alCol=array_keys($this->columns); //    names of columns
        $this->groups[$pGrp]->linesBefore=$pBll;
        foreach($alCol as $col) {
           $alres[$col] = $pAgg;
        }
        $this->groups[$pGrp]->resume[] = $alres;
        unset($alres);
    }

/**
*   set the aggregate function to aply for a column in a resume line
*   @access     public
*   @param      string      $pGrp   Group name
*   @param      integer     $pRes   Number of resume line
*   @param      string      $pCol   Column name
*   @param      string      $pTipo  Aggregate function to aply
*   @return     void
*/
    function setAggregate($pGrp, $pRes, $pCol, $pAgg) {
        if (preg_match("/[SAC-]/i",strtoupper($pAgg))) { // S=sum, A=Averagge, C=Count, -=nothing
            $this->groups[$pGrp]->resume[$pRes][$pCol] = $pAgg;
        }
    }
/**
*   Defines a object and put in the main document in all pages as Header
*   @access  public
*   @param   object     $obj    pointer to pdf object of report (this->pdf)
*   @return  void
*/
    function rptHeader(&$obj ){
        $all = $this->pdf->openObject();
        $obj->saveState();
        $obj->setStrokeColor(0,0,0,1);
        $ftsize=0;
        $slFuncName= 'before_header';
        if (function_exists($slFuncName)) {
           $slFuncName($this, $obj);//                             call the before header event
        }
        //                                                         puts text on header object
        $ilSize=0;
        if ($this->title) {
            $obj->eztext($this->title, $this->titleOpts['T']['fontSize'],array('justification'=>$this->titleOpts['T']['justification'],
                                                                            'leading'      =>$this->titleOpts['T']['leading']));
            $ilSize=$this->titleOpts['C']['fontSize'];
        }
        if (strlen($this->subTitle) > 1) {
            $obj->eztext($this->subTitle, $this->titleOpts['S']['fontSize'],array('justification'=>$this->titleOpts['S']['justification'],
                                                                                'leading'      =>$this->titleOpts['S']['leading']));
            $ilSize=$this->titleOpts['C']['fontSize'];
        }
        $this->pdf->y += $this->pdf->getFontDecender($ilSize);
        $obj->y= $this->pdf->y;
        $this->pdf->y;
        $slFuncName= 'after_header';
        if (function_exists($slFuncName)) {
           $slFuncName($this, $obj);//                             call the after header event
        }
        $alBlankLine=array();
        $ilTabWidth = 0;
        if (!$this->stop) {
            foreach ($this->columns as $oCol) {
                if (!is_a($oCol, "clscol"))  continue;  // to void the 'property name is indefined' message
                $alBlankLine[$oCol->name] = ' ' ;
                if(isset($this->colHead[$oCol->name])) {     //                          if a exists a header with this name ...
                    $this->columns[$oCol->name]->visible = true;
                    $ilTabWidth = $ilTabWidth + $this->colOpt[$oCol->name]['width'] + $this->rptOpt['colGap']; // compute the table width
                   }
                 else $oCol->visible = false;
            }
        }
        $blline=array();
        unset($this->rptOpt['before']);
        $this->rptOpt['onlyHeader']=true;
        $showHeadings=$this->rptOpt['showHeadings']; //                     Memorize actual value
        $showLines=$this->rptOpt['showLines']; //                        Memorize actual value
        $this->rptOpt['showHeadings']=2;
        $this->rptOpt['showLines']=3;
        $this->rptOpt['firstFlag']=1;
        $this->rptOpt['testHeadings'] = true;
        $ilHight = $this->pdf->ezTable($alBlankLine,$this->colHead,'', $this->rptOpt);//    computes de table header hight
        $this->leftBorder= $this->pdf->ez['leftMargin'] ;
        $flFreeSpc=$this->pdf->ez['pageWidth'] - $this->pdf->ez['leftMargin'] -$this->pdf->ez['rightMargin']- $ilTabWidth;
        $this->leftBorder= ($this->pdf->ez['leftMargin'] + ($flFreeSpc / 2) )* 1.1;
/*
echo "<br>PW " .$this->pdf->ez['pageWidth'];
echo "<br>LM " .$this->pdf->ez['leftMargin'];
echo "<br>RM " .$this->pdf->ez['rightMargin'];
echo "<br>TW " .$ilTabWidth;
    echo "<br>LB " . $this->leftBorder;
*/
        if ($ilTabWidth > $this->pdf->ez['pageWidth'] ) {  // si el tama� de la tabla > que la pagina
            $this->pdf->ez['leftMargin'] =10;
            $this->pdf->ez['rightMargin'] =0;
            $this->leftBorder = 10;
        }
        if ($this->condition) {
            $obj->eztext($this->condition, $this->titleOpts['C']['fontSize'],
                         array('justification'=>$this->titleOpts['C']['justification'], 'leading'      =>$this->titleOpts['C']['leading'],
                               'aleft'      =>$this->leftBorder
                                ));
            $ilSize=$this->titleOpts['C']['fontSize'];
            $this->pdf->y -=2;
        }
        $this->rptOpt['testHeadings'] = false;
        if ($this->printColHead) { //                                                           Without headers
            $obj->ezTable($alBlankLine,$this->colHead,'', $this->rptOpt);//                     puts the table header
        } else $ilHight = 0;

//        $this->pdf->y += $ilHight;    //                              <fah 888> Modif por eficiencia
        $this->pdf->y -=4;
        $obj->y = $this->pdf->y;
        $j = $this->pdf->y;
//        $this->pdf->ez['topMargin'] = $this->pdf->ez['pageHeight'] - $this->pdf->y + ($ilHight / 2) ;//      Reset top margin = actual position after include header text
        $this->pdf->ez['topMargin'] = $this->pdf->ez['pageHeight'] - $this->pdf->y;
        $obj->restoreState();
        $obj->closeObject();
        $this->rptOpt['showHeadings']=$showHeadings;
        $this->rptOpt['showLines']=$showLines;
        $this->rptOpt['onlyHeader']=false;
        $this->pdf->addObject($all,'all');//                      add this object to all pages
    }
/**
*   Defines a object and put in the main document in all pages as Footer
*   @access  public
*   @return  void
*/
    function rptFooter(){
        if ($this->printFooter) {
            $ilY = $this->pdf->y ; //                               Store the actual y position
            $all = $this->pdf->openObject();
            $this->pdf->saveState();
            $this->pdf->setStrokeColor(0,0,0,1);
        	$x = $this->pdf->ez['pageWidth'] - $this->pdf->ez['rightMargin'] - $this->textWidth(6, "P�. XXX de XXX");
        	$slText = isset($_SESSION['g_user']) ? $_SESSION['g_user'] . ", " : "";
        	$slText .= date("D M j / y    G:i:s ");
        	$y=$this->pdf->ez['bottomMargin'] - 8;
        	$this->pdf->addText($this->pdf->ez['leftMargin'] + 10, $y, 8, $slText);
        	$this->pdf->ezStartPageNumbers($x,$y,8,'', basename($_SERVER['PHP_SELF'], '.rpt.php') .'       Pag. {PAGENUM} de {TOTALPAGENUM}',1);
            $this->pdf->restoreState();
            $this->pdf->y = $ilY; //                                Restore the y position to initial value
            $this->pdf->closeObject();
            $this->pdf->addObject($all,'all');//                    add this object to all pages
        }
    }
/**
*   Wrapper for ezText Function: Puts any text in the report,
*   @acces  public
*   @param  string      pText   Text to put
*   @param  int         pSize   FontSize
*   @param  string      pLead   'leading' param. for eztext
*   @param  string      pFNam   Font name
*   @param  string      pJust   Justification
*   @param  string      pLeft   Option 'left' for eztext funct.
*   @param  string      pALef   Option 'aleft' for eztext funct.
*/

function putText($pText, $pSize=false, $pLead = false, $pFnam = false, $pJust = 'left', $pLeft = false, $pAlef=0){
        $aOpts= Array(); //                      <---@TODO---> Change of current font
        $pSize= ($pSize) ? $pSize : $this->fontSize;
        if ($pLead) $aOpts['leading']=$pLead ;
        if ($pJust) $aOpts['justification']=$pJust;
        if ($pLeft) $aOpts['left']=$pLeft;
        if ($pAlef<>0) $aOpts['aleft']= $pAlef;
        else $aOpts['aleft']= $this->leftBorder;
        $this->pdf->eztext($pText, $pSize, $aOpts);
        $this->pdf->y -=2;  //                      Saltar un espacio m�imo
//die();
}
/**
*   Wrapper for addTextWrap Function (from base class): Puts any text in the report,
*   @acces  public
*   @param  number      x       x position of text
*   @param  number      y       y position of text
*   @param  number      pwidth  width of text
*   @param  int         pSize   FontSize
*   @param  string      pText   Text to put
*   @param  string      pLead   'leading' param. for adddtext
*   @param  string      pJust   Justification
*   @param  number      pAng    Angulo de Inclinacion del texto
*   @param  number      pTest   Flag for testing (0=false)
*/

function putTextWrap($x, $y, $pWidth, $pSize=false, $pText="",  $pLead = 0, $pJust = 'left', $pAng = 0, $pTest=0){
        $pSize= ($pSize) ? $pSize : $this->fontSize;
        $pos = strpos($pWidth, 'cm');
        if ($pos) $pWidth= ((substr($pWidth,0,$pos)) * 72)/2.5; //       cms to units conversion
        else {
            $pos = strpos($pWidth, 'pt');
            if ($pos) $pWidth= substr($pWidth,0,$pos); //       cms to units conversion
            else
                $pWidth= floatval($pWidth);
            }
        $ilHeight = $this->pdf->getFontHeight($pSize) + $pLead; // add a spacing
         while (strlen($pText)){
            $pText= $this->pdf->addTextWrap($x,$y,$pWidth,$pSize,$pText,$pJust,$pAng,$pTest);
            $y -=$ilHeight;  //                      Saltar un espacio m�imo
        }
        return $y;
}
/**
*   Idem as putTextWarp function, but puts a label before text makes left-margin for text = label's right-margin
*   @acces  public
*   @param  number      x       x position of text
*   @param  number      y       y position of text
*   @param  string      pText   Text to put
*   @param  string      pLabel  Label for Text
*   @param  numeric     pWidth  Text width
*   @param  string      pLwidth Desidred Width for Label. If 0, computes textwidth() of label
*   @param  int         pSize   FontSize
*   @param  int         pLsize  FontSize for Label
*   @param  string      pLead   'leading' param. for adddtext
*   @param  string      pJust   Text Justification
*   @param  string      pLjust  Label Justification
*   @param  number      pAng    Angulo de Inclinacion del texto
*   @param  number      pLang   Label angle
*   @param  number      pTest   Flag for testing (0=false)
*/

function putTextAndLabel($x, $y, $pText, $pLabel, $pWidth, $pLwidth=false,  $pSize=false, $pLsize=false, $pLead = 0, $pJust = 'left', $pLjust = 'left',$pAng = 0, $pLang = 0,$pTest=0){
        $pSize= ($pSize) ? $pSize : $this->fontSize;
        $pLsize= ($pLsize) ? $pLsize : $pSize;
        $pos = strpos($pWidth, 'cm');
        if ($pos) $pWidth= ((substr($pWidth,0,$pos)) * 72)/2.5; //       cms to units conversion
        else {
            $pos = strpos($pWidth, 'pt');
            if ($pos) $pWidth= substr($pWidth,0,$pos); //       cms to units conversion
            else
                $pWidth= floatval($pWidth);
            }
        if ($pLabel) {
            $flDx = NZ($this->textWidth($pLabel, $pLsize));
            $this->pdf->addTextWrap($x,$y,$pLwidth,$pLsize,$pLabel,$pLjust,$pLang);   // puts label at x position
            $x += $flDx;
        }
        if ($pLwidth) $x += $pLwidth;   //                  New text position
        $y=$this->putTextWrap($x,$y,$pWidth, $pSize, $pText, $pLead, $pJust,$pAng,$pTest);
        return $y;
}

/**
*   A mutation of R&OS function (in ezPdf class) that returns the text width
*   @access  public
*   @param      $text       text to evaluate
*   @param      $size       font size of text 
*   @return                 width of text
*/
 function textWidth($size, $text) {
    $text = "$text";
  // hmm, this is where it all starts to get tricky - use the font information to
  // calculate the width of each character, add them up and convert to user units
  $w=0;
  $len=strlen($text);
  $cf = $this->pdf->currentFont;
  for ($i=0;$i<$len;$i++){
      $f=1;
      $char=ord($text[$i]);
      if (isset($this->pdf->fonts[$cf]['differences'][$char])){
        // then this character is being replaced by another
        $name = $this->pdf->fonts[$cf]['differences'][$char];
        if (isset($this->pdf->fonts[$cf]['C'][$name]['WX'])){
          $w+=$this->pdf->fonts[$cf]['C'][$name]['WX'];
        }
      } else if (isset($this->pdf->fonts[$cf]['C'][$char]['WX'])){
        $w+=$this->pdf->fonts[$cf]['C'][$char]['WX'];
      }
    }
  $this->pdf->setCurrentFont();
  return $w*$size/1000;
}
/**
*   Generacion del contenido basico de los datos, en excel
*
*/
function excelOut(){
    global $rs, $db;
error_reporting(0);
    ob_start();
//header('Content-type: application/vnd.ms-excel');
//header("Content-disposition: inline; ");
    $db->SetFetchMode(ADODB_FETCH_NUM);
	$goGrid = new baaGrid ($slSql, "DB_ADORS", null, $rs);
	$goGrid->setTableAttr('border="1" align="center" cellspacing="1" style="font-size:' . $this->rptOpt['fontSize']);
	$goGrid->showErrors(); // just in case
	$goGrid->setHeaderClass('CobaltColumnTD');
	$goGrid->setRowClass('CobaltDataTD,CobaltAltDataTD');
	$goGrid->externHead=$slHead1;
	$goGrid->getData();
	$i = 0;
//	print_r($goGrid->dataFields);
	foreach ($goGrid->dataFields as $k =>$oField){
	    if (isset($this->colOpt[$k]) && isset($this->colOpt[$k]['width'])) $ilWidth = $this->colOpt[$k]['width'];
	    else{
	       $ilLong = $oField->max_length;
	       if ($oField->max_length >= 40) $ilLong = 40;
               $ilWidth = $ilLong * 5;
        }
//echo $i . " " . $k . " / " . $this->columns[$k]->visible . "<br>" ;
//print_r($this->columns[$k]);
        if (isset($this->columns[$k]) && $this->columns[$k]->visible ==false)  $goGrid->hideColumn($i);
        $goGrid->setWidth($i, $ilWidth);
        $ilDec=0;
        if (isset($this->columns[$k]) && isset($this->columns[$k]->format)) {
            list($ilEnt, $ilDec, $ilSep, $ilDcp) = split(':', $this->columns[$k]->format,4);
            $goGrid->setDecPlaces($i,$ilDec);
        }
	   if (isset($this->colOpt[$k]) && isset($this->colOpt[$k]['justification'])) {
            $goGrid->setAlign($i,substr($this->colOpt[$k]['justification'],0,1));
        }
        if (isset($this->columns[$k]) && isset($this->columns[$k]->repeat) && $this->columns[$k]->repeat == false) {
		  $goGrid->setOnChange($i,0,0);
		}
	   if (isset($this->groups[$k]) && isset($this->groups[$k]->resume) ) {
          $goGrid->setOnChange($i,1,1);
          foreach ($this->groups[$k]->resume as $k => $v){  //           Resume lines
            $nn = 0;
            foreach ($v as $kk => $vv){                     //          Columns in Resume Line
              $nn++;
              list($ilEnt, $ilDec, $ilSep, $ilDcp) = split(':', $this->columns[$kk]->format,4);
              if ($this->columns[$kk]->type == "N"  && $this->columns[$kk]->visible) $goGrid->setTotal($nn,$ilDec);
		    }
		  }
		}
	   $i++;
	}
//print_r($this->columns);
    $goGrid->setSubTotalClass('sub1');
//	echo $goGrid->externHead;
	$goGrid->display();
    $this->gridContent = ob_get_contents();
//header("Content-length: " . strlen($this->gridContent) );
	ob_end_clean();
//	$goGrid->debug();
    $this->view("",false);
}
}
//----------------------------------------------------------------------  End Class pdfReport

?>

