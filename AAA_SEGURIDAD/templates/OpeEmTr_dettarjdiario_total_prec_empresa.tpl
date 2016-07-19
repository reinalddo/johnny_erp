<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>{$gsSubTitul}</title>
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/report.css" title="CSS para pantalla" />
</head>
<body align:"center" id="top" style="font-family:'Arial'; ">
{report recordset=$agData groups="txp_Producto, txp_Shipper, txp_NombZona " record=rec}
<table width="98%" border="1" cellpadding="0" cellspacing="0">
{report_header}
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0">
			<tr>
			  <td class="colhead" colspan={$gsNumCols} style="text-align: center; font-weight:bold; ">APROBACION DE PRECIOS DETALLADO</td>
			  
			</tr>
			<tr>
			  <td class="colhead" colspan={$gsNumCols} style="text-align: left; font-weight:bold; ">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</td>
			   
			</tr>
			<tr>
			  <td class="colhead" colspan={$gsNumCols} style="text-align: left;font-weight:bold; ">{$gsSubTitul}</td>
			</tr>
			<tr style="height: 20px; vertical-align:bottom; font-size:10; font-weight:bold; text-align:left">
			<td style="height: 20px; vertical-align:bottom; font-size:10; text-align:center; font-weight:bold;">PRODUCTOR</td>
			<td>TARJA #</td>
			<td>CJS EMBARC.</td>
			<td>BONIFIC</td>
			<td>PRECIO PACTADO</td>
			<td>VALOR</td>	
			</tr>
         	 
{/report_header}
{report_header group ="txp_Producto" }
<tr>
  <td colspan=6 style="height: 35px; vertical-align:bottom; font-size:10; font-weight:bold; text-align:center">PRODUCTO: {$rec.txp_Producto}</td>
</tr>
{/report_header}

{report_header group ="txp_Shipper" }
<tr>
  <td colspan=6 style="height: 25px; vertical-align:bottom; font-size:10; font-weight:bold; text-align:center">{$rec.txp_Shipper}                         </td>
</tr>
{/report_header}
{report_header group ="txp_NombZona" }
<tr>
  <td colspan=6 style="height: 20px; vertical-align:bottom; font-size:10; font-weight:bold; text-align:left">ZONA: {$rec.txp_NombZona}</td>
</tr>
{/report_header}

{report_detail}
	<tr style="white-space:nowrap;">
			<td style="text-align:left;">{$rec.txp_Productor}</td>
			<td>{$rec.txp_NumTarja}</td>
			<td class="colnum">{$rec.txp_CantNeta|number_format:0}</td>
			<td class="colnum">{$rec.txp_Bono|number_format:4}</td>
			<td class="colnum">{$rec.txp_PrecUnit|number_format:4}</td>
			<td class="colnum">{$rec.txp_ValTotal|number_format:2}</td>
			
         </tr>
{/report_detail}

{report_footer group ="txp_NombZona" }
  {assign var=flProm value = $sum.txp_ValTotal/$sum.txp_CantPagar }
  <tr style="white-space:nowrap">
      <td colspan="2">SUMA {$rec.txp_NombZona} </td>
      <td class="colnum">{$sum.txp_CantNeta|number_format:0}</td>
      <td class="colnum"> </td>
      <td class="colnum">{$sum.txp_PrecUnit|number_format:4}</td>
      <td class="colnum">{$sum.txp_ValTotal|number_format:2}</td>
   </tr>
{/report_footer}


{report_footer group ="txp_Shipper" }
  {assign var=flProm value = $sum.txp_ValTotal/$sum.txp_CantPagar }
  <tr style="white-space:nowrap">
      <td>SUMA {$rec.txp_Shipper} </td>
      <td></td>
      <td class="colnum">{$sum.txp_CantNeta|number_format:2}</td>
      <td class="colnum"> </td>
      <td class="colnum"> </td>
      <td class="colnum">{$sum.txp_ValTotal|number_format:2}</td>
   </tr>
  <tr>
    <td colspan=2>&nbsp</td>
    <td colspan=4>&nbsp</td>
  </tr>
  <tr>
    <td colspan=2 align=center>_______________________</td>
    <td colspan=4 align=center>_______________________</td>
  </tr>
  <tr>
    <td colspan=2 align=center>Elaborado por {$smarty.session.g_user}</td>
    <td colspan=4 align=center>Autorizado por </td>
  </tr>

{/report_footer}

{report_footer group ="txp_Producto" }
  {assign var=flProm value = $sum.txp_ValTotal/$sum.txp_CantPagar }
  <tr style="white-space:nowrap">
      <td>SUMA {$rec.txp_Producto} </td>
      <td></td>
      <td class="colnum">{$sum.txp_CantNeta|number_format:0}</td>
      <td class="colnum"> </td>
      <td class="colnum"> </td>
      <td class="colnum">{$sum.txp_ValTotal|number_format:2}</td>
   </tr>

{/report_footer}


</table>
{/report}
</body>
</html>
