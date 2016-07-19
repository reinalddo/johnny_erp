<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script>
function imprimirPagina() {
  if (window.print)
  {
	document.all.imprimir.style.visibility='hidden'  
    window.print();
	}
  else
  {
    alert("Lo siento, pero a tu navegador no se le puede ordenar imprimir" +
      " desde la web. Actualizate o hazlo desde los menús");
  }
}
</script>
<style type="text/css">
<!--
-->
</style></head>

<body>
<form name="all">
<?php
	$variable1="Triviño Viteri Gilberto";
	$variable2="Jose Mascote entre sedalana";
	$variable3="Guayaquil";
	$variable4="1234567890001";
	$variable5="18 de febrero 2009";
	$variable6="0010521";
	$variable7="1234567";
	$variable8="Efectivo";
	$variable9="1";
	$variable10="LT BACTER PLUS";
	$variable11="2.35";
	$variable12="2.35";
	$variable13="2.35";
	$variable14="0.00";
	$variable15="0.00";
	$variable16="0.00";
	$variable17="0.00";
	$variable18="2.35";
							
?>
  
  <DIV STYLE="POSITION:absolute; LEFT:75px; TOP:125px;;"><?php echo $variable1?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:115px; TOP:150px;"><?php echo $variable2?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:115px; TOP:175px;"><?php echo $variable3?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:115px; TOP:200px;"><?php echo $variable4?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:800px; TOP:125px; "><?php echo $variable5?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:900px; TOP:150px; "><?php echo $variable6?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:900px; TOP:175px; "><?php echo $variable7?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:900px; TOP:200px; "><?php echo $variable8?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:25px; TOP:300px;"><?php echo $variable9?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:115px; TOP:300px;"><?php echo $variable10?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:820px; TOP:300px;"><?php echo $variable11?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:1020px; TOP:300px;"><?php echo $variable12?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:1020px; TOP:828px;"><?php echo $variable13?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:1020px; TOP:853px;"><?php echo $variable14?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:1020px; TOP:878px;"><?php echo $variable15?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:1020px; TOP:903px;"><?php echo $variable16?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:1020px; TOP:928px;"><?php echo $variable17?></DIV>
  <DIV STYLE="POSITION:absolute; LEFT:1020px; TOP:953px;"><?php echo $variable18?></DIV>
  <table align="left" width="1665" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable--> 
    <tr>
      <td height="370" colspan="3" align="left" valign="top" ><!--DWLayoutEmptyCell-->&nbsp;</td>
      <td width="5" >&nbsp;</td>
    </tr>
    <tr>
      <td width="598" height="24" >&nbsp;</td>
      <td width="65" valign="top" ><input name="imprimir" id="noprint" type="button" onClick="imprimirPagina();" value="Imprimir"></td>
      <td width="997" >&nbsp;</td>
      <td >&nbsp;</td>
    </tr>
    <tr>
      <td height="1993" >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
    </tr>
  </table>
  </div>
</form>
</body>
</html>
