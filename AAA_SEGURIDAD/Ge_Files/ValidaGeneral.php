<?php
/*	Proceso de Validacion de Datos en linea
*
*   @param  $sCond      String:  Condicion de busqueda
*	@param	$scampos    Array:   Campos que recupera el query
*	@param	$sTabla		String:  Tabla de consulta
*	@param	$sMensj		String:  mensaje de Error
*	@param	$sTipo		Integer: Tipo de Validacion:  0= No debe existir, 1 = Debe existir solo uno,  2 = Puede existir mas de uno , 3 devolver un valor
*	@param	$sTraer		Array:   Datos que se retornaran
*	@param	$sForm		string:  Formulario receptor de los datos
*	@param	$sDesti		Array:   Lista de campos de destino
*	@param	$sFocus		Array:   Campo al que se hará focus
*	@param	$sPageR		String:  Página q ue presenta resultados existen mas de uno y la validacion es de registro unico
*/
//  ini_set("include_path", ".;C:\SERVER\PHP\includes\adodb;C:\SERVER\PHP\includes\adodb\drivers;C:\SERVER\PHP\includes\general");
  session_start();
  include_once ("General.inc.php");
  include_once ("GenUti.inc.php");
  include_once ("adodb.inc.php");
  include_once ("../LibPhp/SegLib.php");
  include_once('adodb-pager.inc.php');
  echo "<!--  ";
  $iDbg   = fGetParam("pDbg", 0);
    if ($iDbg)  echo "1<br>" . DBSRVR;
    if ($iDbg)  echo "2<br>";
  $sSepar   = fGetParam("pSepar", " "); 								 // Separador de params
  $sIgual   = fGetParam("pIgual", "="); 								 // Simbolo sutstituto de "="
  $sOpts    = fGetParam("pOpts", " "); 								     // Opciones de apertura de ventana de seleccion
  $sSelec   = str_replace($sIgual, "=", stripslashes(fGetParam("pSelec", ""))); 								 // Cadena que define el 'SELECT ... '
  $sCond    = str_replace($sIgual, "=", stripslashes(fGetParam("pCond", " "))); // Condicion de busqueda convirtiendo simbolo "="
  $sCampos  = fGetParam("pCampos", " ");			    				 //
  $sTabla   = fGetParam("pTabla", " ");			    				      // Tabla
  $sMensj   = fGetParam("pMensj", " ");  			    				  // Mensaje en caso de error
  $sTipo    = fGetParam("pTipo", " ");                				      // Tipo de validacion: 0= No debe existir, 1 = Debe existir solo uno,  2 = Puede existir mas de uno , 3 devolver un valor
  $sTraer   = explode(";" , fGetParam("pTraer", ""));				      // Nombre de los campos que traera
  $sForm    = fGetParam("pForm", "");							          // Elemento a enfocar si hay error
  $aCampos  = explode(",", $sCampos);
  $sPageR   = fGetParam("pPageR", "");                                    // Página
  $sCondEx  = str_replace($sIgual, "=",fGetParam("pConEx", ""));           // Condicion Extra para el Query
 echo "/*<br>";
  if (strlen(fGetParam("pDesti", "")) > 1)
	  $aDesti  = explode(",", fGetParam("pDesti", ""));								// Nombre de los campos que traera
  $sFocus  = fGetParam("pFocus", " ");								// Elemento a enfocar si hay error

  if (isset($sForm)) $sForm.= ".";

  if (isset($aCampos[0]) && !isset($aDesti[0])) {               		 // si no existe $pdesti, se asume = a pCampos
     	for ($i=0; $i < count($aCampos); $i++) {
    							$aDesti[$i] = $aCampos[$i];
       	}
   }
  $sSelec = $sSelec ? $sSelec : $sCampos;                                 // Si no hay cadena SELECT, utilizar los nombres de campos
  $sCond .= (strlen($sCondEx>1))?  " AND " . $sCondEx  : "";              // Agregar la condicion extra

  $db = ADONewConnection('mysql');
  $db->debug = true;
  if(!$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME))
    die ("<br><br><br><br><center>NO SE PUDO CONECTAR AL SERVIDOR DE BASE DE DATOS</center>") ;
  $rs =  $db->Execute("SELECT " . $sSelec . " FROM " . $sTabla . 	" WHERE " . $sCond);
  $slJScript = "";
  echo "--> ";
echo " SELECT " . $sSelec . " FROM " . $sTabla . 	" WHERE " . $sCond;
  switch ($sTipo ) {
    case 0:
if ($iDbg)  echo "0  ";                             			                // Validar no existencia (no repetir un registro)
        if($rs->RecordCount()> 0) {
            echo "<html><head><script language='JavaScript'>\n";
            echo "window.top.document.forms." . $sForm . $aDesti[0] . ".focus();\n"; //sE MANTIENE ENFOCADO EL MISMO ELEMTO
            echo "if(confirm('$sMensj\\n\\nDESEA PRESENTARLO EN PANTALLA?')){\n";
            echo "lct=window.top.location.protocol + '//' + ".
                     "window.top.location.hostname + " .
                     "window.top.location.pathname + " . "'" .
                     "?$aDesti[0]=" . $rs->fields[0] . "';\n";
if ($iDbg)  echo "alert(lct);\n";
            echo "window.top.location.href = lct ;}";
            echo "</script><head><html>";
            }
        else {
            if (strlen($sFocus) > 1) {
                echo "<html><head><script language='JavaScript'>\n";
                echo "window.top.document.forms." . $sForm . $sFocus . ".focus();\n"; //sE Enfoca el sigte elemento
                echo "</script><head><html>";
            }
        }
        break;
    case 1:                                                         // Validar existencia (Integridad referencial)
if ($iDbg)  echo "1";
    	switch ($rs->RecordCount())  {
        	case -1:
        	case 0:	           											   // No hay coincidencias

if ($iDbg)  echo "10";
                    fMensaje($sMensj);
            		break;
        	case 1:        	           									   // Hay una coincidencia
if ($iDbg)  echo "11";
            		if ($sTipo == 3) fRetornaValores();				       // Debe regresar datos
		            break;
            default:
if ($iDbg)  echo "12";
            		fMensaje ($sMensj . "<BR> EXISTE MAS DE UNA OCURRENCIA");
                    break;
        }
        break;
   	case 2:                                                         // SI EXISTE MAS DE UNO
if ($iDbg)  echo "2";
        if ($rs->RecordCount() > 0) {
   	       fSelecWin();
   	    }
   	    break;
   	case 3:                                                         //DEBE RETORNAR UN VALOR
if ($iDbg)  echo "3<br>";
   	    switch ($rs->RecordCount()) {
            case 1:
if ($iDbg)  echo "31<br>";
                fRetornaValores();				                            // Retorna los datos del registro encontrado
                break;
            default:
if ($iDbg)  echo "32<br>";
                fSelecWin();                                                // pagina de seleccion
                break;
        }
   	}
   	echo "<!-- ";
   	echo "/* <br> " . $slJScript . "*/";
   	echo "--!> ";
    fIniHtml();
    echo $slJScript;
    fFinHtml();
/**
*       Abrir una ventana de selección, basada en parametros de consulta y operacion enviados en URL
*       @return     void
*/
function fSelecWin() {
    global $sSepar,   $sIgual,   $sOpts,   $sCond,   $sCampos,   $sTabla,   $sMensj;
    global $sTipo,    $sTraer,   $sForm,   $aCampos,  $sPageR,   $aDesti,   $sFocus;
    global $slJScript;
    $slJScript = "";
    $sTxt = "window.parent.document." . $sForm;
    if (strlen($sPageR) > 2)  {
        $aPageR = explode ($sSepar, str_replace($sIgual, "=", $sPageR ));           // Separa parametros de url (reasigna el caracter "=" representadio por $sIgual)
        $sPageR = $aPageR[0] . "?" . $aPageR[1];
        for($i=2; $i<count($aPageR);  $i++) {
              $sPageR   .= "&" . $aPageR[$i];
              }
        $sPageR .= "&pMensj=**** ". $sMensj . " ****"  ;
//      $slWinName = "wSearch_" . mt_rand(1,100);                                    // Nombre aleatorio para la ventana
        $slWinName = $aDesti[0] . mt_rand(1,100);                                    // Nombre aleatorio para la ventana
        $slJScript .= "\n window.parent.mSelectflag=true; " ;                          // Status de ventana de seleccion activada
        $slJScript .= "\n var winsel=window.open(\"" . stripslashes($sPageR) . "\",'" . $slWinName . "',". stripslashes($sOpts) .");";

/*        if (strlen($aDesti[0]) > 0 && $aDesti[0] <> "x" ) 	{
            $slJScript .= $sTxt . $aDesti[0] . ".focus();\n";                       // Quedarse en el campo y en modo select
            $slJScript .= $sTxt . $aDesti[0] . ".select();\n";
            }
*/
        $slJScript .= "\n winsel.focus()" ;
        }
    else  fMensaje($sMensj);
}
/*
*		fIniHtml:	Funcion para escribir una cadena de inicio Html
*		@params
*       @return     void
*/
function fIniHtml() {
	echo "<html> <head><script language='JavaScript'>  ";
}
/**
*		Funcion para escribir una cadena de Fin Html
*		@params
*       @return     void
*/
function fFinHtml() {
    echo "</script> ".
         "</head> ".
         "<body>  ".
            "<form name='Form1'> " .
            "</form> ".
         "</body> ".
         "</html> ";
}
/**
*		Retornar valores del resultado de la consulta a los campos de destino cuando la condicion
*       genera solo un registro
*		@params
*       @return     void
*/
function fRetornaValores() {
	global $rs;
    global $aCampos;
    global $aDesti;
    global $sForm;
    global $sFocus;
    global $slJScript;
    $slJScript = "";
    if (isset($aCampos[0])) {
        for ($i=0;$i < count($aDesti);$i++){
	    	$slJScript .="window.parent.document." . $sForm . $aDesti[$i] . ".value='". $rs->Fields($aCampos[$i]) . "';";   // Para cada $sDesti, se asigna el valor $sTraer
	    	echo $aCampos[$i] . "<br>";
        }
        $slJScript .= " window.parent.oValidador.On = false; \n" ;
        $slJScript .= " window.parent.mSelectflag = false; \n" ;
        $slPrefijo =   "window.parent.document.forms." . $sForm;
        if (strlen($sFocus) > 1) {
            $slJScript .= $slPrefijo . $sFocus . ".focus();\n"; //sE Enfoca el sigte elemento
	       $slJScript .=  "if(" . $slPrefijo . $sFocus . ".tagName == 'INPUT') " .  $slPrefijo . $sFocus . ".select();\n";
        }
    }
}
?>
