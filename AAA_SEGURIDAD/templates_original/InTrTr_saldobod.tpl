<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
 Listado de Toma Fisica del Inventario
*   @access public
*   @author Marco Valle Sanchez
*   modificado 08/21/09
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Toma Fisica de Inventario {$gsSubTitul}</title>
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/report.css" title="CSS para pantalla" />
<body align:"center" id="top" style="font-family:'Arial'; ">

{report recordset=$agData groups="bodeg,grupo" record=rec}
<table width="98%" border="1" cellpadding="0" cellspacing="0">
{report_header}
	  
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  
	  <thead>
	    
	    <tr>
	      <td style="text-align:center" colspan=9>LISTADO PARA TOMA FISICA DE INVENTARIO</td>
	    </tr>
			<tr>
			<td>COD</td>
			<td>ITEM</td>
			<td>UNIDAD</td>
			<td>SALDO PREVIO</td>
			<td>CANT INGRESOS</td>
			<td>CANT EGRESOS</td>
			<td>SALDO FINAL</td>
			<td>PRECIO UNI REF</td>
			<td>TOMA FISICA</td>	
			</tr>
         </thead>	 
{/report_header}
{report_header group="bodeg"}			
  		<tr>
			<td style="text-align:center" colspan=9> {$rec.bodeg}</td>
       		</tr>

{/report_header}
{report_header group="grupo"}			
  		<tr>
			<td></td>
			<td style="text-align:left" colspan=9>{$rec.GRU} {$rec.grupo}</td>
       		</tr>
{/report_header}
{report_detail}
	<tr style="white-space:nowrap">
			<td>{$rec.ITE}</td>
			<td>{$rec.DES}</td>
			<td>{$rec.UNI}</td>
			<td>{$rec.SAN|number_format:2}</td>
			<td>{$rec.CIN|number_format:2}</td>
			<td>{$rec.CEG|number_format:2}</td>		
			<td class="colnum">{$rec.SAC|number_format:2}</td>
			<td class="colnum">{$rec.PUN|number_format:5}</td>
			<td>&nbsp;</td>
			
         </tr>
{/report_detail}
{report_footer group="grupo"}
		  <tr> 
			<td colspan="3" style="text-align:right">{$rec.grupo} Subtotal: {$sum.PUN|number_format:5}</td>
			<td colspan="6">&nbsp;</td>
			
		  </tr>
{/report_footer}
{report_footer}
		  <tr> 
			<td colspan="8" style="text-align:right">TOTAL : {$sum.PUN|number_format:5}</td>
		  
		  </tr>
{/report_footer}


</table>
{/report}
</body>
</html>
