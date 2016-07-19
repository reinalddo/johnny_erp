<?php
/*
 *	ColModel  Structure of a Ext-grid, Loads a Template file, witl basic Html and rendersit.
 *
 *	Fecha de Modificacion: 29-abr-2009
 *	Cambios:
 *		1. Se agrego a $dateFmts el formato de la base de datos Y-m-d
 *		2. Se agrego a columnas tipo date:
 *				a. 'dateFormat' => 'Y-m-d': formato que tiene en la base
 *				b. 'renderer' => 'formatDate': formato con el que se mostrara (extExtensions)
 **/
class clsExtGrid {
	var $id = "";
    var $sqlID = "id";
    var $typeOptions= Array(); // Default options for fields by type 
    var $userOptions= Array(); // User defined Options by field;
    var $typeEditors= Array(); // User defined Options by type;
	var $hiddenCols=  Array(); //  Columns to Hide;
	var $metaData  =  Array();
    var $minColSize = 5;
    var $maxColSize = 40;
    var $colWidthFlag = false; // Define the col width by its header width
    var $charToPixFactor = 5.3; // Character to Pixel conversion factor (for a font-size of 10)
	/*@var DB source Date Format  */
    var $dateFmt  ='Y-m-d';//'d-M-y';
	/*@var Alternate Date Formats for auto conversion on input*/
    var $dateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|Y-m-d|';
    /*@var DB source Date-Time Format  */
	var $dateTimeFmt  ='d-M-y h:i';
	/*@var Alternate Date-Time  Formats for auto conversion on input*/
    var $dateTimeFmts = 'd-m-y h:i|d-m-Y h:i|d-M-y h:i|d-M-Y h:i|d/m/y h:i|d/m/Y h:i|d/M/y h:i|d/M/Y h:i|d-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y|d/M/Y';
	var $charSet = "iso-8859-1";
	var $tplFile = "../GeGeGeFiles/GeGeGe_Autogrid.tpl";
	


 function clsExtGrid ($pName=false) {
		$this->id = ($pName ? $pName : "Grid_" . date('dHis', TIME())); // Nombre del grid, o Grid + marca de tiempo
        $this->typeOptions['string'] 	= Array('type'=>'string', 'hidden'=> false, 'width'=>25);
        $this->typeOptions['int'] 		= Array('type'=>'int', 'hidden'=> false, 'width'=>10, 'align'=>'right');
        $this->typeOptions['float'] 	= Array('type'=>'float', 'hidden'=> false, 'width'=>10, 'align'=>'right');
        $this->typeOptions['boolean'] 	= Array('type'=>'boolean', 'hidden'=> false, 'width'=>6);
        $this->typeOptions['date'] 	= Array('type'=>'date','hidden'=> false, 'width'=>10, 'format' => $this->dateFmt, 'altFormats' => $this->dateFmts, 'dateFormat' => 'Y-m-d','renderer' => 'formatDate');//, "renderer"=>"Ext.util.Format.dateRenderer('m/d/Y')");
        $this->typeOptions['datetime']  = Array('type'=>'string', 'hidden'=> false, 'width'=>12, 'format' => $this->dateFmt, 'altFormats' => $this->dateFmts);

        $this->typeEditors['string']	= Array('type'=>'TextField','config' => Array('allowBlank' => false, 'width'=>100));
        $this->typeEditors['int'] 		= Array('type'=>'NumberField', 'config' =>Array('align'=>'right'));
        $this->typeEditors['float'] 	= Array('type'=>'NumberField', 'config' =>Array('align'=>'right'));
        $this->typeEditors['boolean'] 	= Array('type'=>'CheckBox');
        $this->typeEditors['date'] 		= Array('type'=>'DateField', 'config' =>Array('width'=>10));
        $this->typeEditors['datetime'] 	= Array('type'=>'DateField', 'config' =>Array('width'=>12));
    }
    function afterInit(){
    }

    function setDateFmt($pFmt){
		$this->dateFmt=($pFmt);        
    }
    function setDateTimeFmt($pFmt){
		$this->dateTimeFmt=($pFmt);        
    }
    function setAltDateFmt($pFmt){
		$this->dateTimeFmt=($pFmt);        
    }
    function setAltDateTimeFmt($pFmt){
		$this->dateTimeFmts=($pFmt);        
    }
    /*
     *  Set an option value por every field in metadata. Note:  Use this AFTER all actions, just before outJson
     *  @param  pName       String      Name of option
     *  @param  pValue      String      Value of the option
     */
    function setGlobalOpt($pName, $pValue){
        reset($this->userOptions);
        if (fGetParam("pAppDbg", 0) == 4) { echo "****" . $pName . " : " . $pValue . "<br>"; obsafe_print_r($this->metaData, false, true, 4) ;}
		if (isset($this->metaData['fields'])){          // @fah Abr/20/08
            foreach ($this->metaData['fields'] as $f) {
                $this->userOptions[$f["id"]][$pName] = $pValue;
            }
        }
//        obsafe_print_r($this->userOptions, false, true, 4) ;
    }
	function setMetaData($pData){
		if (is_array($pData)) $this->metaData=$pData;
		else echo "La Infrormacin de los datos (metadata) debe ser un arreglo";
	}

    /*
     *  User Options setter
     *  @param String       recordset Field name
     *  @param Array    Array of grid options, based in structure of ext-grid
     */
    function setFieldOpt($pFld, $pOpt=NULL){
        if(!array_key_exists ($pFld, $this->userOptions )) $this->userOptions[$pFld]= array();
        if (is_array($pOpt)) $this->userOptions[$pFld]= $pOpt;
    }
	/*
	 *	Takes a Metadata, compares with default values and outputs a new metaData
	 */
	function processMetaData($pMetaData){
        if (!is_array($pMetaData)) return NULL;
		$newFields=array();
		$this->metaData=$pMetaData;
//obsafe_print_r($this->metaData, false, true, 4) 	;
        reset($this->metaData["fields"]);
        $ilDbg = fGetParam("pAppDbg", 0);
        if ($ilDbg >= 1 ) echo " min " . $this->minColSize . "   max " . $this->maxColSize . "   flag " .        $this->colWidthFlag;
		foreach ($this->metaData["fields"] as $arrField) {
            if ($ilDbg >= 1 ) { echo "<br>ancho calc: " . $arrField['name'] . " ". $arrField['width'] * $this->charToPixFactor;
                echo "  " . $arrField['width'] . "  * " . $this->charToPixFactor, "  db meta <br>" ;
                obsafe_print_r($arrField, false, true, 4);
            }
			$arrField = array_merge($arrField, $this->typeOptions[$arrField['type']]); // Merge basic option with options by type of field
            if ($ilDbg >= 1 ){echo "post tipo ";  obsafe_print_r($arrField, false, true, 4) ;  }
			if (isset($this->userOptions[$arrField['name']]))
				$arrField = array_merge($arrField, $this->userOptions[$arrField['id']]); // Merge basic option with options by type of field
            if ($ilDbg >= 1 ){echo "pre calc ";  obsafe_print_r($arrField, false, true, 4) ;  }
            if ($arrField['width'] < $this->minColSize) $arrField['width'] = $this->minColSize;
            if ($arrField['width'] > $this->maxColSize) $arrField['width'] = $this->maxColSize;
            if ($this->colWidthFlag && ($arrField['width'] < strlen($arrField['header']))) {
                $arrField['width']  = strlen($arrField['header']) * $this->charToPixFactor;
            }
            else $arrField['width'] *= $this->charToPixFactor;
            if ($ilDbg >= 1 ){  obsafe_print_r($arrField, false, true, 4) ;  echo "<br>";}
			$newFields[] = $arrField;
		}
        $this->metaData["fields"] = $newFields;
		unset($newFields);
//obsafe_print_r($this->metaData, false, true, 4) 	;
		return $this->metaData;
	}
}	
?>
