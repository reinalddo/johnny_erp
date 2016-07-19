<?php
/*
    $session =session_id();
    IF($session)session_id(session_id());
    session_start(); //                                 INICIO DE SESION
    session_register("g_empr") = "GG";
    $_SESSION["g_empr"] = "hhh";
*/
    session_start();
    $_SESSION['g_user']=NULL;
    $_SESSION['g_empr']=NULL;
    $_SESSION['g_dbase']=NULL;
    $_SESSION['g_pass']='';
    $_SESSION['p']='PRUEBA  AAAA ';

?>
<html>
<head>
<?php
 	setcookie("coSelect", "", time()+1800,"/");  			//Query aejecutar
	setcookie("coWhere", "", time()+1800,"/");  				//Query aejecutar
	setcookie("coDestino", "", time()+1800,"/");			//Arreglo con nombres de los campos de destino
	setcookie("coOrigen", "", time()+1800,"/");			//Arreglo con los nombres de campos de origen ( en el dataset)
	setcookie("coCompon", "", time()+1800,"/");				    //Nombre del contenedor en la pagina de destino
	setcookie("coSearchPage", "", time()+1800,"/");				//Nombre del Pagina de Busqueda
	setcookie("coErrMensaje", "", time()+1800,"/");				//Mensaje de error si no existe coincidencia
	setcookie("coSufijo", "", time()+1800,"/");				//Sufijo de los componentes en la pagina origen
	setcookie("coMensj", "", time()+1800,"/");				//Mensaje si existe multiple coincidencia (pantalla de seleccion)
	setcookie("coEstado", 	"", time()+1800,"/");

?>
<base target="_self">
<link rel="stylesheet" type="text/css" href="./Themes/StormyWeather/Style.css">
<link rel="stylesheet" type="text/css" href="./Themes/Menu/Style.css">
<title>- A A A   PAGINA INICIAL-</title>
<script language="JavaScript" src="./LibJava/menu_func.js"></script>
<script language="JavaScript" src="./LibJava/reloj.js"></script>
</head>
<body  bgcolor="#fffff7" link="#000099" alink="#ff0000" vlink="#9999cc" text="#000000" class="CobaltPageBODY"
        style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; magin: 0; spacing: 0"
        bottommargin="0" nowrap leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0">
<table height="90%" width="99%" border="1" cellpadding="0" cellspacing="1"
                style="border-collapse:collapse; font-size:x-small">>
  <tr height="90%">
    <td colspan="2">  </td>
  </tr>

  <tr height="10%">
    <td><img bgcolor="#fffff7" src="Images/apache_pb2_ani.gif"></img></td>
    <td><img src="Images/apache-con.gif"></img></td>
    <td><img src="Images/dbdesigner-logo.png"></img></td>
    <td><img src="Images/logo-php-bug.gif"></img></td>
    <td><img bgcolor="#fffff7" src="Images/pandalogo.gif"></img></td>
    <td><img bgcolor="#fffff7" src="Images/logo-js.gif"></img></td>
    <td><img src="Images/logo-linux.png"></img></td>
  </tr>
</table>

</body>
</html>
