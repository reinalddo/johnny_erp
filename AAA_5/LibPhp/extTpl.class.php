<?
/**
*   Clase que define gebera el codigo HTml para cualquier componente Ext
*   Utiliza una plantilla Html con  la estructura Basica necesaria.
*   El codigo JS siempre debe colocarse en un archivo con el mismo nombre del script padre, pero
*   con extension js.
*   @rev    fah 20/06/08    Inclusion of properties: dataSourceUrl, jsObjectName
*   @rev    fah 29/09/08    Support for MUltiCelSelection Model
*   @rev    fah 24/05/09    Redo setOptions method to add options dinamically
*   @rev    fah 24/05/09    Change order of execution: loadOprtions before jsScript
**/
//include_once "../De_Files/DeGeGe_cabecera.php";
if (!isset ($_SESSION)) session_start();
class clsExtTpl
{
    var $isPage=true ; // Inidica si es una pagina  standalone, sino se trata de un componente
	var $headerFlag = false;
	var $headerFile = "";
	var $headerContent = null;
	var $htmlFile =  ""; //default, puede cambiarase a voluntad
	var $htmlOut  = "";
	var $pageTitle  = "AAA";
	var $preScript  = "";   /* Script que deba  cargarse antes de las librerias ext */
	var $postScript = "";	/* Script que deba  cargarse despues de las librerias ext */
	var $bodyScript = "";   /* Script que deba  cargarse en la seccion body  */
	var $jsBeforeScripts = "";   /* Script que deba  cargarse al inicio de los scripts*/
	var $jsAfterScripts  = "";   /* Script que deba  cargarse en final de los scripts*/							  
	var $cssFiles   = "";
	var $scripts    = "";
	var $title="AAA"; 	// default page title	
    /*@var string Name of the main JavaScript file to load (when the magic begins) */
    var $jsFileName = "";
    /* @var string  Url for the Data Source of the grid, it put is js code inside Html template */ 
    var $dataSourceUrl  = null;
    /* @var string  Name of the js variable references the grid in page's js code, in global scope */
    var $jsObjectName   ="grid";
    /* @var string     List of Basic Options required*/
    var $basicOptions        = "general, extExt";
    /* @var string     List of Options required  */
    var $options        = "";
    /* @var extensions  Array of the extensions file required for every option*/
    var $extensions     = "";
    /* @var dbgMode     boolean For debugging data*/
    var $dbgMode        = false;
    /* @var string     Default Css File*/
    var $defaultCssFile = "../LibJs/ext/resources/css/ext-all.css";
    /* @var string     Default Inline style for page*/
    var $style          ="body {font-weight:normal; font-size:0.2em!important; background-color:transparent; text-align:left} input {font-weight:normal;font-size:8px; width:90%;}";
  /*
   *	Constructor
   *	@param  string  pTplFile    Path to template file
   *	@param  bool    $pPage      Flag to generate a Standalone Page (true) or a  compònent script (false)
   *	@param  bool    $pProt      Flag to include prototype library
   **/
  function clsExtTpl($pTplFile="../Ge_Files/GeGeGe_extpanels.tpl", $pPage=true, $pProt=true){
    global $FileName;
	$this->jsFileName = basename($_SERVER["PHP_SELF"], ".php");			
    if (strlen($FileName) > 3) $FileName;
    $this->isPage = $pPage;
    $this->htmlFile=$pTplFile;
    $this->dbgMode = (isset($_GET["pAppDbg"]) ? $_GET["pAppDbg"]>= 1 : false);
    $gsUrl=fGetParam('pUrl', false);    // Parameter for grid`s datasource      fah 20/06/08
    $gsObj=fGetParam('pObj', 'grid');   // Js Object name
    if ($gsUrl == false) $gsUrl = $_SERVER["PHP_SELF"];     // If Url not defined via Get/POST Param, usus this script as Datasource
    $this->dataSourceUrl = $gsUrl;
    $this->jsObjectName  = $gsObj;

	if ($this->isPage==true) { //                 Loads all ext and other basic files
        $this->addScripts("../LibJs/browser_detect");
        if ($pProt) $this->addScripts("../LibJs/prototype1.5");
        $this->addScripts("../LibJs/general");
        $this->addScripts("../LibJs/ext/adapter/ext/ext-base");
        if($this->dbgMode){
          $this->addScripts("../LibJs/ext/ext-all-debug");
        } else {
            $this->addScripts("../LibJs/ext/ext-all");
        }
        $this->addScripts("../LibJs/ext/build/locale/ext-lang-es-min");
    }
    $this->configOption("general", "../LibJs/general");
    $this->configOption("multiCell", "../LibJs/ext/ux/Ext.ux.MultiCellSelectionModel");
    $this->configOption("cellAndRowSM", "../LibJs/ext/ux/Ext.ux.RowWithCellSelectionModel");
    $this->configOption("extExt", "../LibJs/extExtensions");
    $this->configOption("summary", "../LibJs/ext/ux/grid/GridSummary, ../LibJs/ext/ux/grid/GroupSummary");
    $this->configOption("expander", "../LibJs/ext/ux/grid/RowExpander");
    $this->configOption("auto", "../LibJs/extAutogrid");
    $this->configOption("inputMask", "../LibJs/ext/ux/Ext.ux.InputTextMask");
    $this->configOption("filter", "../LibJs/ext/ux/menu/RangeMenu, ../LibJs/ext/ux/menu/EditableItem, ".
                         "../LibJs/ext/ux/grid/GridFilters,         ../LibJs/ext/ux/grid/filter/Filter, ".
                         "../LibJs/ext/ux/grid/filter/StringFilter, ../LibJs/ext/ux/grid/filter/DateFilter, ".
                         "../LibJs/ext/ux/grid/filter/ListFilter,   ../LibJs/ext/ux/grid/filter/NumericFilter, ".
                         "../LibJs/ext/ux/grid/filter/BooleanFilter");
    
	$this->readTpl();
	}
	function addScripts($value){
    	$this->scripts .= "<script type='text/javascript' src='$value.js'></script>\r";
	}
	function addBodyScript($value){
    $this->bodyScript .= "<script type='text/javascript' src='$value.js'></script>\r";
	}
	function addJsBeforeScripts($value){
		$this->jsBeforeScripts .= ";\r/*-----*/\r" . $value;
	}
	function addJsAfterScripts($value){
		$this->jsAfterScripts .= ";\r/*-----*/\r" . $value;
	}
	function addCssFile($value, $media='screen'){
    	$this->cssFiles  .= "<link type='text/css' rel='stylesheet' media='$media' href='$value.css'></link>\r";
	}
	function addCssRule($value){
        $this->style  .= $value;
	}
	function setHtmlFile($file){
        $this->htmlFile = $file;
	}
	function setIsPage($pPar){
        $this->isPage = $pPar;
	}
    /*
     *  Generic setter
     *  @param  string  Property name
     *  @param  variant Property Value
    */
    function set($pProp, $pVal){
        $this->$pProp = $pVal;
    }
    /*
     *  Sets the list of Options. (Option = list of js files to include)
     *  @param  string $pPar    Comma separated list of options
    */
	function setOptions($pPar){   //#fah 24/05/09
        $this->options = $pPar;
	}
	 /*
     *  Adds an Option to the list of Options. (Option = list of js files to include)
     *  @param  string $pPar    Comma separated list of options
    */
	function addOptions($pPar){   //#fah 24/05/09
        $this->options .= ((strlen($this->options)>=1)? ",":"" ) . $pPar;
		if (fGetParam("pAppDbg",0)) { echo "<br> Añadir: " .$pPar . " Resultante: " ; print_r($this->options);}
	}
    /*
     *  Sets the Url for grid's datasource
     *  @param  string $pPar    A Valid url that returns the record for grid, in format required
    */
	function setDataSourceUrl($pPar){
        $this->dataSourceUrl = $pPar;
	}
    /*
     *  Sets the NAme  for grid's variable name in Java Script code of the page. Global Scope.
     *  @param  string $pPar    A Valid url that returns the record for grid, in format required
    */
	function setJsObjectName($pPar){
        $this->jsObjectName = $pPar;
	}
	function setTplVar($name, $value){
        $this->htmlOut = str_replace("{_" . $name . "_}",  $value, $this->htmlOut);
	}
	function setTplBlock($name, $value){
        $this->htmlOut = str_replace("<!--_" . $name . "_-->",  $value, $this->htmlOut);
	}
	function readTpl(){
		$lf = fopen($this->htmlFile, "r");
		$this->htmlOut = fread ($lf, filesize ($this->htmlFile));
		fclose($lf);
	}
	function renderHeader(){
		if ($this->headerFlag && strpos($this->htmlData, "{_tpCabecera_}") >= 0){
		    $this->headerObj = new clsCabecera($this->headerFile);
		    $this->htmlOut= str_replace("{_tpCabecera_}",  $this->headerObj->getHtmlOut(), $this->htmlOut) ;
		    }
		else {
			$this->htmlOut= str_replace("{_tpCabecera_}",  '', $this->htmlOut) ;
		}
		$this->htmlOut= str_replace("{_tpTitle_}",  $this->title, $this->htmlOut) ;
	}
    /*
     *  Configures a new option, adding extensions files required to the list of extensions
     *  @param  string $pOpt    Option Name
     *  @param  array $pExt     List of js files names (WHITOUT .js extension) needed to load for the option added
    */
    function configOption($pOpt, $pExt=array()){
        if (!is_array($pExt)) $pExt = explode(",", str_replace(" ","", $pExt));
        if (is_string($pOpt)) $this->extensions[$pOpt] = $pExt;
    }
    /*
     *  Scans a lis of options required and  load the extensions file needed
     ** @param  array/string    $pList List of options to load
     */
    function loadOptions($pList){
        if (!is_array($pList)) $pList = explode(",", str_replace(" ","", $pList));
        $v=null;
        foreach($pList  as $k => $v){
                if (isset($this->extensions[$v])){
                    foreach($this->extensions[$v] as $k1 => $v1){
                        $this->addBodyScript($v1);
						if (fGetParam("pAppDbg",0)) echo "<br>cargando: " .$v1;						
                    }
                }
        }
    }
	function beforeRender(){
	}
  
	function render(){        global $FileName;
        $this->renderHeader();
        $this->setTplVar("tpTitle", $this->pageTitle);
        if (strlen($this->jsBeforeScripts)) $this->jsBeforeScripts = "<script type='text/javascript'>$this->jsBeforeScripts</script>";
		$this->htmlOut= str_replace("<!--_tpPreScripts_-->", $this->jsBeforeScripts . "<!--_tpPreScripts_-->", $this->htmlOut);
        if (strlen($this->jsAfterScripts)) $this->jsAfterScripts = "<script type='text/javascript'>$this->jsAfterScripts</script>";
		$this->htmlOut .= $this->jsAfterScripts;
		if (strlen($this->style))   $this->setTplBlock("tpStyle", "<style>" . $this->style. "</style>");
        if (strlen($this->preScript))   $this->setTplBlock("tpPreScripts",  $this->preScript);
        if (strlen($this->scripts))	    $this->setTplBlock("tpScripts",     $this->scripts);
        if (strlen($this->postScript))  $this->setTplBlock("tpPostScripts", $this->postScript);
        $this->setTplBlock("tpCssFiles", $this->cssFiles);
        $this->loadOptions($this->basicOptions);
        $this->loadOptions($this->options);
        $this->bodyScript .= "\r<script type='text/javascript' src='" . $this->jsFileName . ".js'></script>";
        $this->setTplBlock("tpBodyScripts", $this->bodyScript);
        $this->addJsBeforeScripts("sgLoadUrl='" .$this->dataSourceUrl. "'; gsObj='" . $this->jsObjectName .  "'");
//print_r($this);        
        $this->beforeRender();
        echo $this->htmlOut;
	}
}
?>
