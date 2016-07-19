<?php require_once('../../../Connections/c2.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ingreso")) {
  $insertSQL = sprintf("INSERT INTO propresupuesto (pro_periodo, pro_cuenta, pro_auxiliar, pro_nivel, pro_cantidad, pro_valor) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['periodo'], "int"),
                       GetSQLValueString($_POST['cuenta'], "int"),
                       GetSQLValueString($_POST['aux'], "int"),
                       GetSQLValueString($_POST['nivel'], "int"),
                       GetSQLValueString($_POST['cantidad'], "int"),
                       GetSQLValueString($_POST['valor'], "int"));

  mysql_select_db($database_c2, $c2);
  $Result1 = mysql_query($insertSQL, $c2) or die(mysql_error());

  $insertGoTo = "testing.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_c2, $c2);
$query_c2 = "SELECT * FROM propresupuesto";
$c2 = mysql_query($query_c2, $c2) or die(mysql_error());
$row_c2 = mysql_fetch_assoc($c2);
$totalRows_c2 = mysql_num_rows($c2);
?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base target="_self">
<link rel="stylesheet" type="text/css" href="../Themes/StormyWeather/Style.css">
<link rel="stylesheet" type="text/css" href="../Themes/Menu/Style.css">
<title>presupuesto</title>
<link rel="stylesheet" type="text/css" href="../Themes/Cobalt/Style.css">
</head>

<body>
<p><span class="CobaltPageBODY" style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; magin: 0; spacing: 0">
  <script language="JavaScript" src="../LibJava/menu_func.js"></script>
</span></p>
<p>&nbsp;</p>
<table align="center" style:"border-colapse:collapse">
  <tr style="FONT-SIZE: x-small">
    <td style="FONT-SIZE: x-small" align="middle" valign="top"><form method="POST" action="<?php echo $editFormAction; ?>" name="ingreso" id="ingreso">
      <!-- BEGIN Grid SeGeUs_qry -->
      <font class="CobaltFormHeaderFont">PRESUPUESTO</font>
      <table width="464" border="0" cellpadding="1" cellspacing="1" style="WIDTH: 419px; HEIGHT: 159px">
        <tr align="middle">
          <td width="103" nowrap class="CobaltColumnTD"><!-- BEGIN Sorter Sorter_usu_IDusuario -->PERIODO</td>
          <td width="95" nowrap class="CobaltColumnTD"><!-- BEGIN Sorter Sorter_usu_login -->
               &nbsp;CUENTA</td>
          <td width="99" nowrap class="CobaltColumnTD">AUXILIAR</td>
          <td width="69" nowrap class="CobaltColumnTD">NIVEL</td>
          <td width="31" nowrap class="CobaltColumnTD">CANTIDAD</td>
          <td width="3" nowrap class="CobaltColumnTD">VALOR</td>
        </tr>
        <!-- BEGIN Row -->
        <tr align="middle" valign="top">
          <td class="CobaltDataTD"><label>
            <input name="periodo" type="text" id="periodo" size="5" maxlength="5">
          </label></td>
          <td class="CobaltDataTD"><label>
            <input name="cuenta" type="text" id="cuenta" size="20" maxlength="20">
          </label></td>
          <td class="CobaltDataTD" nowrap><label>
            <input name="aux" type="text" id="aux" size="10" maxlength="10">
          </label></td>
          <td class="CobaltDataTD"><label>
            <input name="nivel" type="text" id="nivel">
          </label></td>
          <td class="CobaltDataTD"><label>
            <input name="cantidad" type="text" id="cantidad" size="3" maxlength="3">
          </label></td>
          <td class="CobaltDataTD"><label>
            <input name="valor" type="text" id="valor" size="12" maxlength="12">
          </label></td>
        </tr>
<!-- END Row -->
        <!-- BEGIN NoRecords -->
        <tr>
          <td colspan="6" class="CobaltDataTD"><label>
            <input name="Agregar" type="submit" id="Agregar" value="Agregar">
          </label></td>
        </tr>
        <!-- END NoRecords -->
        <tr>
          <td aling="center" colspan="6" nowrap class="CobaltFooterTD"><!-- BEGIN Navigator Navigator -->
                <!-- BEGIN Prev_On -->
            <a class="StormyWeatherNavigatorLink" href="{Prev_URL}"></a>
            <!-- END Prev_On -->
            &nbsp;
                <!-- BEGIN Next_On -->
            <a class="StormyWeatherNavigatorLink" href="{Next_URL}"></a>
            <!-- END Next_On -->
            <!-- END Navigator Navigator -->
            &nbsp; </td>
        </tr>
      </table>

      <input type="hidden" name="MM_insert" value="ingreso">
    </form></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($c2);
?>
