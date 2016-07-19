<?
/** File SegLib.php:  Funciones de Seguridad
*   @rev   Feb 18/04
*   @rev   fah 10/06/09    Aplicar restricciones de acceso a modulos por tipo de comprobante, segun perfil /usuario en $_SESSION[restr]
**/

/**
 * function fEstaLogeado()
 *
 * Determina si el usuario esta logeado un usuario. Utiliza variables de sesiòn y Servidor
 *
 **/
// include_once ("General.inc.php");
if (!isset($DBSeguridad)) {
//    include_once("../Common.php");
//	$DBSeguridad = new clsDBseguridad();
	}
;

function fEstaLogeado() {
    global $DBSeguridad;
    if (!isset($_SESSION["g_empr"])) {
        $_SESSION["g_empr"]  = false;
        $_SESSION["g_dbase"]  = false;
    }
	if(!isset($_SESSION["g_user"]) || (isset($_SESSION["g_user"])  && !$_SESSION["g_user"])) {
			auth();
    }
    else return fUsuarioValido($_SESSION["g_user"], $_SESSION["g_pass"]);
}
/**
 * function fUsuarioValido()
 *
 * Determina si el usuario existe y el pass es correcto, asigna valores a las variables de servidor correcpondientes
 *  @access     public
 *  @param      string  $pUser      usuario
 *  @param      string  $pPass      password
 **/
function fUsuarioValido($pUser, $pPass){
    global $DBSeguridad;
	$DBSeguridad = new clsDBseguridad();
        $sSql = "SELECT usu_login, usu_email, usu_Dpto, usu_Grupo " .
	 		"FROM seguridad.segusuario " .
            	"where usu_login = '" . $pUser . "' AND usu_password = '" . $pPass . "' ";
    $DBSeguridad->query($sSql);
    $rst = $DBSeguridad->next_record();
        if ($rst) {
         $_SESSION["g_user"] = $DBSeguridad->f("usu_login");
	 $_SESSION["g_grupo"] = $DBSeguridad->f("usu_Grupo");
	 $_SESSION["g_Dpto"] = $DBSeguridad->f("usu_Dpto");
	 $_SESSION["g_email"] = $DBSeguridad->f("usu_email");
	         $_SESSION["g_pass"] = $pPass;
	         $_SESSION["logged"] = +1;
	         return true;
		} else {
		     $_SESSION["logged"] = 0;
		     return false;
        }
}
/**
 * function fLogoff()
 *
 * Anula los valores de las variables para deslogear un usurio
 * @params   ninguno
 **/
function fLogoff(){
   $_SESSION["g_user"] = false;
   $_SESSION["g_pass"] = false;
   $_SESSION["g_empr"] = false;
   $_SESSION["g_dbas"] = false;
   $_SESSION["PHP_AUTH_USER"] = false;
   $_SESSION["PHP_AUTH_PW"] = false;
   $_SESSION = Array();
   if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time()-360000, '/');
   }
// Finally, destroy the session.
    session_start();
    session_unset();
    session_destroy();
   session_regenerate_id(true);
   $_SESSION = array(); 
   session_destroy();
}
/**
 * function auth()
 *
 * Proceso de Autenticaciòn de usuarios
 * @params      nionguno
 * @return
 **/
function auth () {
    $lQryStr ="";
    if (isset($_SERVER['QUERY_STRING']))    $lQryStr= "?" . $_SERVER['QUERY_STRING'];
    $_SESSION["g_retPage"]=$_SESSION["g_retPage"] = $_SERVER['PHP_SELF'] . $lQryStr;
    $_SESSION["g_user"]=false;
    header("location: ../Se_Files/Se_Login.phtml?pMod=" . substr(basename($_SERVER["PHP_SELF"], ".php") ,0,6));
	exit;
}
/**
 * function fValidAcceso()
 *
 * Validacion del nivel de acceso del usuario a una pagina y funcion especifica basada en sus atributos y perfiles
 * @params      $pFile          Pagina a la que se desea accesar, si es vacia, se toma el nombre del archivo
 *              $pOperac        Operación a ejecutar en la pagina
 *              $pFuncion       Funcion a ejecutar
 * @descr       Si no se especifica $file, definir el modulo como el contenido de $FileName,
 *              si ésta no ha sido definida, asumir como el nombre del archivo actual
 *              Si la operacion no ha sido definida, asumir ooo (general)
 * @return      Verdadero si el usuario tiene privilegios para la pagina-operacion-funcion, sino, falso.
 **/
function fValidAcceso($pFile=false, $pOperac, $pFuncion)
{
  	global $FileName;
	global $Redirect;
//  echo "<br> mod: " . $slModulo . $pOperac;
	if ($pFile=="Se_Log") return true;
 	if(!fEstaLogeado())   die("DEBE LOGEARASE CORRECTAMENTE");
	if (Empty($pOperac) or is_Null($pOperac) or $pOperac=="ooo")  $pOperac = "ooo";
    $slModulo = substr(basename($_SERVER["PHP_SELF"], ".php"),0,6);
	if(strlen($pFile) > 1) $slModulo = $pFile;
    if (Empty($FileName) or is_Null($FileName) or strlen($FileName) <1 ) $FileName = substr(basename($_SERVER["PHP_SELF"], ".php"),0,6);
	if (Empty($pFile) or is_Null($pFile) )      $slModulo = substr($FileName,0,6);
	if ($slModulo == "GeGeGe") return true; //  Los Scripts GeGeGe no tenen restriccion de uso
    if (!isset($_SESSION['atr'][$slModulo]))    fPerfilUsuario();
    //echo $_SESSION['atr'][$slModulo]["ADD"]."--2--".$_SESSION['atr'][$slModulo][$pOperac]."--3--".$slModulo."--4--".$pOperac."<br/>";
	//print_r($_SESSION['atr'][$slModulo]);
    if (isset($_SESSION['atr'][$slModulo][$pOperac])  && $_SESSION['atr'][$slModulo][$pOperac] > 0 ) return true;
    return false;
}
/**
 * Genera un array de sesion, con el perfil de accesos
 *
 * @params      $pFile          Pagina a la que se desea accesar, si es vacia, se toma el nombre del archivo
 * @return      Void
 * @rev		fah	Jun/10/09 Filtrado efectivo por empresa (ver condicion de SQl: usp_IDempresa ....)
 **/
function fPerfilUsuario($pFile=false)
{
  	global $FileName;
	global $Redirect;
	global $DBSeguridad;
 	fEstaLogeado();
//   echo "<br>ARCHIVO:" . $pFile . " vacio" . Empty($pFile) . "  nulo: " . is_Null($pFile). "file: " . $FileName . "  self: " . substr(basename($_SERVER["PHP_SELF"], ".php") ,0,6);
	if (Empty($pFile) or is_Null($pFile) )      $slModulo = substr(basename($_SERVER["PHP_SELF"], ".php") ,0,6);
    $sSql =  "SELECT mod_descripcion
   			FROM seguridad.segmodulos
            where concat(mod_subsistema, mod_modulo, mod_submod)  = '" . $slModulo . "'";
 	$resultado = $DBSeguridad->Query($sSql);
 	if (!$DBSeguridad->num_rows()) {
       fMensaje ("ESTE MODULO NO ESTA REGISTRADO: " . $slModulo );
       die();
	}
   /*$sSql = "SELECT mod_Operacion, usu_Nombre, usu_email, max(atr_Nivel) AS atr_Nivel
            FROM segusuario, segusperfiles, segatributos, segmodulos
                    where usu_Login = '"  . $_SESSION["g_user"]  . "' AND
					usp_IDusuario = usu_IDusuario AND  " . // usp_IDempresa =  '"  . $_SESSION["g_empr"]  . "' AND 
					"atr_IDperfil  = usp_IDperfil and mod_codigo = atr_codmodulo and
					concat(mod_subsistema, mod_modulo, mod_submod)  = '" . $slModulo . "'
            GROUP BY 1, 2, 3 ";*/
	$sSql="SELECT mod_Operacion, usu_Nombre, usu_email, max(atr_Nivel) AS atr_Nivel
	    FROM segusuario u
		    JOIN segusperfiles p ON u.usu_idusuario=p.usp_idusuario
		    JOIN segatributos a ON p.usp_idperfil=a.atr_idperfil
		    JOIN segmodulos m ON m.mod_id=a.atr_codmodulo
		    JOIN segempresas e ON usp_IDempresa=emp_IDempresa
	    WHERE usu_login='"  . $_SESSION["g_user"]  . "' AND emp_Descripcion='"  . $_SESSION["g_empr"]  . "'
	    AND CONCAT(mod_subsistema, mod_modulo, mod_submod)='" . $slModulo . "'
	    GROUP BY 1, 2, 3 ";
	    //echo $sSql;
//die ($sSql);
 	$resultado = $DBSeguridad->Query($sSql);
 	if (!$DBSeguridad->num_rows()) {
       fMensaje ("PRIVILEGIOS INSUFICIENTES:  UD. NO TIENE NINGUN ACCESO A ESTE MODULO. " .  $slModulo .  " en " . $_SESSION["g_empr"] );
       die();
	}
//echo $sSql . "<br>" ;
//echo $row->atr_Nivel . "<br>";
    if (!isset($_SESSION['atr'] )) $_SESSION['atr'] = Array( Array());
    $_SESSION['atr'][$slModulo] = Array();
    if ($DBSeguridad->next_record())
    {
        do
        {
        	$_SESSION['nomb'] = $DBSeguridad->f('usu_Nombre');
        	$_SESSION['email'] = $DBSeguridad->f('usu_email');
            $slOper = $DBSeguridad->f('mod_Operacion');
            $slPerm = $DBSeguridad->f('atr_Nivel');
            $_SESSION['atr'][$slModulo] [$slOper] = $slPerm;
        } while ($DBSeguridad->next_record());
    }

}
/*
**  Valida si el usuario tiene autorizacion para ejecutar determinada funcion. Si no, deshabilita la sentencia SQL
**  @param  string      $pOper      Codigo de operacion a ejecutar
**  @param  obj         $pOpbj      Objeto sobre el que se procesa
*/
function fValidOperacion($pOper, &$pObj)  {                           //
    $slOper = "EJECUTAR ESTA OPERACION";                              // PEND: Utilizar un array con las descripciones de cada operacion
	if (!fValidAcceso("",$pOper,"")) {                                // PEND: Utilizar variables de session
	    $slMens="UD. NO PUEDE $slOper";                               // PEND: Utilizar variables de session
    	fMensaje ($slMens, 1);
		$pObj->ds->SQL="";					//			para evitar que se jecute el query
    }
	$pObj->Errors->AddError($slMens. " !!!");
}
/*
**  Agrega un registro a la bitàcora para registrar un evento
*   @access public
**  @param string   $pTipo      Tipo de "objeto" sobre el que se registra el evento
**  @param integer  $pNume      "Objeto"
**  @param string   $pAnot      Anotacion mnemotecnica
**  @param float    $pCant      Cantidad
**  @param float    $pVal1      Valor 1
**  @param float    $pVal2      Valor2
**  @param string   $pAuto      Código del usuario que autiriza
**  @param integer  $pEsta      Estado de ejecuciòn
**  @param string   $pUsua      Usuario que genera el evento
**  @param integer  $pCodi      Código de modificacion
*/
function fRegistroBitacora($pTipo, $pNume, $pUsua=false, $pAnot = " ", $pCant = 0, $pVal1 = 0, $pVal2 = 0, $pAuto = " " , $pEsta = 0, $pCodi = 0)
{   global $DBdatos;
    if (!$pUsua) $pUsua = fUsuario("N.N");
    $slSql = "INSERT INTO " . DBNAME . ".segbitacora (
                    bit_TipoObj,
                    bit_NumeroObj,
                    bit_anotacion,
                    bit_CantRegis,
                    bit_Valor1,
                    bit_Valor2,
                    bit_autoriza,
                    bit_estado,
                    bit_IDusuario,
                    bit_ModCodigo)
                VALUES (
                    '$pTipo',
                    $pNume,
                    '$pAnot',
                    $pCant,
                    $pVal1,
                    $pVal2,
                    '$pAuto',
                    $pEsta,
                    '$pUsua',
                    $pCodi ) ";

    $DBdatos->query($slSql);
}
/*
**  Agrega un registro a la bitàcora para registrar un evento, utilizando libreria Ado
*   @access public
**  @param string   $pTipo      Tipo de "objeto" sobre el que se registra el evento
**  @param integer  $pNume      "Objeto"
**  @param string   $pAnot      Anotacion mnemotecnica
**  @param float    $pCant      Cantidad
**  @param float    $pVal1      Valor 1
**  @param float    $pVal2      Valor2
**  @param string   $pAuto      Código del usuario que autiriza
**  @param integer  $pEsta      Estado de ejecuciòn
**  @param string   $pUsua      Usuario que genera el evento
**  @param integer  $pCodi      Código de modificacion
*/
function fRegistroBitacoraAdo($db, $pTipo, $pNume, $pUsua=false, $pAnot = " ", $pCant = 0, $pVal1 = 0, $pVal2 = 0, $pAuto = " " , $pEsta = 0, $pCodi = 0)
{   
    if (!$pUsua) $pUsua = fUsuario("N.N");
//    $slSql = "INSERT INTO " . DBNAME . ".segbitacora (
    $slSql = "INSERT INTO segbitacora (    
                    bit_TipoObj,
                    bit_NumeroObj,
                    bit_anotacion,
                    bit_CantRegis,
                    bit_Valor1,
                    bit_Valor2,
                    bit_autoriza,
                    bit_estado,
                    bit_IDusuario,
                    bit_ModCodigo)
                VALUES (
                    '$pTipo',
                    $pNume,
                    '$pAnot',
                    $pCant,
                    $pVal1,
                    $pVal2,
                    '$pAuto',
                    $pEsta,
                    '$pUsua',
                    $pCodi ) ";

    $db->Execute($slSql);
}
/**
 * function fMensaje()
 *
 * Presentar una ventana con un texto (similar a un dialog box de VB).
 * emite un JS al vuelo, que el browser interpreta directamente.
 * @params      $pTexto         Texto que se desplegara
 **/
function fMensaje($ptTexto) {
	echo "<SCRIPT LANGUAGE=\"JavaScript\">";
	echo "alert(\" " . $ptTexto .  "\" );";
	echo "</SCRIPT>" ;     }
/**
 * function fUsuario()
 *
 * Presentar Devuelve el Usuario actualmente loggeado
 * @params   String   $pDefault         usuario defaul
 * @return   String
 **/
function fUsuario($pDefault = '') {	
	return isset($_SESSION["g_user"]) ? $_SESSION["g_user"] : $pDefault;
}	
/**
 * Consulta a la base de datos el nombre del usuario y lo devuelve
 * @params   String   $pUser     Usuario a consultar,
 * @return   String   Nombre del usuario o false
 **/
 function fTraeUsuario($pUser=false){
 	global $DBSeguridad;
// 	echo "----";
// 	echo $DBSeguridad->DBDatabase;
// 	print_r($DBSeguridad);
    if (!$pUser ) {
        if (!isset($_SESSION["g_user"]) ) return "??";
        $pUser= $_SESSION["g_user"];
    }
	$sSql = "SELECT usu_nombre FROM seguridad.segusuario " .
          	"where usu_login = '" . $pUser ."'" ;
	$result = $DBSeguridad->query($sSql) or die("<BR><BR><BR><CENTER>CABECERA: FALLO LA CONSULTA DE USUARIO </CENTER>");
    if ($DBSeguridad->next_record())
    {
       return $DBSeguridad->f('usu_nombre');
    }
	else return "** USUARIO NO REGISTRADO **";
}
/**
 * Devuelve  la BD que debe utilizarse
 * @params   String   $pDefault         valor default
 * @return   String
 **/
function fBdatos($pDefault = '') {	
	return isset($_SESSION["g_dbase"]) ? $_SESSION["g_dbase"] : $pDefault;
}	
/**
 * Preprocesamiento para Ha¡bilitar / deshabilitar  botones en un a pagina CCS, basado en los atributos del usuario
 * @params   String Array   $pPagina   Pagina de atributos (el Script actual por defecto)
 * @params   String Array   $pOpciones Opciones que existen en la pagina
 * @params   String Array   $pEstado   Estado default, en base a una condicion externa (puede ser el estado de una transaccion, periodo etc)
 * @return   Void
 **/
function fHabilitaBotonesCCS($pPagina=false, $pOpciones=false, $pEstado=false, $pEstSup=95 ) {	
    global $FileName;
    if (!$pPagina) $pPagina = $slModulo = substr($FileName,0,6);
    if (!is_array($pOpciones)) fHabilitaCCS($pPagina, $pOpciones, $pEstado, $pEstSup);
    else     foreach ($pOpciones as $slOpc) {
                fHabilitaCCS($pPagina, $slOpc, $pEstado, $pEstSup);
    }
}	
/**
 * Habilita o deshabilita los botones en un a pagina CCS, basado en los atributos del usuario
 * @params   String Array   $pPagina   Pagina de atributos (el Script actual por defecto)
 * @params   String Array   $pOpciones Opciones que existen en la pagina
 * @params   String Array   $pEstado   Estado default, en base a una condicion externa (puede ser el estado de una transaccion, periodo etc)
 * @params   String Array   $pEstSup   Techo de estado, entre cero y este valor se habilita la operacion, (default = 95)
 * @return   Void
 **/
function fHabilitaCCS($pPagina=false, $pOpc=false, $pEstado=false, $pEstSup=95 ) {
    global $Tpl;
//	echo "<br> $pOpc set: " . isset($_SESSION['atr'][$pPagina][$pOpc]) ;
//	echo "     valor: " . $_SESSION['atr'][$pPagina][$pOpc] . "       " ;
//	echo "     Estado: " . $pEstado;
//	echo "     estSup: " . $pEstSup;
    if($pEstado > 0 && $pEstado < $pEstSup && isset($_SESSION['atr'][$pPagina][$pOpc]) && $_SESSION['atr'][$pPagina][$pOpc] >= 1) {
         $Tpl->SetVar($pOpc . '_flag','');
//echo "<br> HABILITADO " . $pOpc.  " atributo: " . $_SESSION['atr'][$pPagina][$pOpc] ;
    }
    else {
        $Tpl->SetVar($pOpc . '_flag','disabled');
//echo "<br> DESHABILITADO " . $pOpc . " atributo: " . $_SESSION['atr'][$pPagina][$pOpc];
    }
}
/**
*  VALIDACION DEL MODULO
*
**/
$slFile=substr(basename($_SERVER["PHP_SELF"], ".php"),0,6);
if (($slFile != "Valida" &&  $slFile != "Cabece" && $slFile != "GeDual"))  { //             Sript Validador multiple.
    if (isset($gbByPass) and $gbByPass == true ) {
	$gbByPass = true;
    }
    else {
	if (!fValidAcceso($slFile,"","")) {
		    fMensaje (basename($_SERVER["PHP_SELF"]) . ":   ACCESO RESTRINGIDO: UD. NO TIENE ACCESO A ESTE MODULO " . $slFile, 1);
		die();
	}
    }
}
if (!isset($_SESSION["g_retPage"])) $_SESSION["g_retPage"] =  $_SERVER["PHP_SELF"];
if ($slFile == "GeDual" )
    $mm=fEstaLogeado();
?>
