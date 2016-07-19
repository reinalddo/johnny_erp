<?
/**
*   Clase que define gebera el codigo HTml para cualquier componente Ext
*   Utiliza una plantilla Html con  la estructura Basica necesaria.
*   El codigo JS siempre debe colocarse en un archivo con el mismo nombre del script padre, pero
*   con extension js.
**/
//start_ob();
//include_once "../De_Files/DeGeGe_cabecera.php";
if (!isset ($_SESSION)) session_start();
$FileName=basename($_SERVER["PHP_SELF"], ".php"); //   Definicion obligatoria para que funciones Seglib
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
	var $dbgMode = false;
	var $title="AAA"; 	// default page title	
	var $defaultCssFile = "../LibJs/ext/resources/css/ext-all.css";
    var $style  ="body {font-weight:normal; font-size:8px!important; background-color:transparent; text-align:left} input {font-weight:normal;font-size:8px; width:90%;}";
  /*
   *	Constructor
   *	@param  pTplFile    string  Path to template file
   *	@param  $pPage      bool    Flag to generate a Standalone Page (true) or a  compònent script (false)
   **/
  function clsExtTpl($pTplFile="../Ge_Files/GeGeGe_extpanels.tpl", $pPage=true){
    global $FileName;
    $this->isPage = $pPage;
    $this->htmlFile=$pTplFile;
    $this->dbgMode = (isset($_GET["pAppDbg"]) ? $_GET["pAppDbg"]>= 1 : false);
    if ($this->isPage) { //                 Loads all ext and other basic files
        $this->addScripts("../LibJs/browser_detect");
        $this->addScripts("../LibJs/prototype1.5");
        $this->addScripts("../LibJs/general");
        $this->addScripts("../LibJs/ext/adapter/ext/ext-base");
        if($this->dbgMode){
          $this->addScripts("../LibJs/ext/ext-all-debug");
        } else {
            $this->addScripts("../LibJs/ext/ext-all");
        }
        $this->addScripts("../LibJs/ext/build/locale/ext-lang-es-min");
    }
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
  
	function beforeRender(){
	}
  
	function render(){
        global $FileName;
        $this->renderHeader();
        $this->setTplVar("tpTitle", $this->pageTitle);
        //$this->setTplBlock("tpStyle", "<style type='text/css' media='screen'>" . $this->style . "</style>");
        if (strlen($this->jsBeforeScripts)) $this->jsBeforeScripts = "<script type='text/javascript'>$this->jsBeforeScripts</script>";
		$this->htmlOut= str_replace("<!--_tpPreScripts_-->", $this->jsBeforeScripts . "<!--_tpPreScripts_-->", $this->htmlOut);
        if (strlen($this->jsAfterScripts)) $this->jsAfterScripts = "<script type='text/javascript'>$this->jsAfterScripts</script>";
		$this->htmlOut .= $this->jsAfterScripts;
		if (strlen($this->style))   $this->setTplBlock("tpStyle", "<style>" . $this->style. "</style>");
        if (strlen($this->preScript))   $this->setTplBlock("tpPreScripts",  $this->preScript);
        if (strlen($this->scripts))	    $this->setTplBlock("tpScripts",     $this->scripts);
        if (strlen($this->postScript))  $this->setTplBlock("tpPostScripts", $this->postScript);
        $this->setTplBlock("tpCssFiles", $this->cssFiles);
        $this->bodyScript .= "\r<script type='text/javascript' src='$FileName.js'></script>";
        $this->setTplBlock("tpBodyScripts", $this->bodyScript);
        $this->beforeRender();
        echo $this->htmlOut;
	}
}
?>
