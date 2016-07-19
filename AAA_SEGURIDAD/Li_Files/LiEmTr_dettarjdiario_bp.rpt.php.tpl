<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DETALLE DE TARJAS {$gsSubTitul}</title>
</head>
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />

<body align:"center" id="top" style="font-family:'Arial'; ">

{report recordset=$agData record=rec	groups="txp_PtoDescripcion, txp_NombBuque, txp_Bodega, txp_Piso" resort=true}

<table width="100%" border="1" cellpadding="0" cellspacing="0">
{report_header}
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr >
			  <td class="colhead" colspan={$gsNumCols} align="center">{$gsEmpresa}</td>
			</tr>
			<tr >
			  <td class="colhead" colspan={$gsNumCols} align="center">{$gsSubTitul}</td>
			</tr>
			<tr>
			<td>Fecha</td>
			<td>Semana</td>
			<td>Productor</td>
			<td>Empaque</td>
			<td>Tipo_Fruta</td>
			<td>Tarja #</td>
			<td>Bodega</td>
			<td>Piso</td>
			<td>Despachado</td>
			<td>Faltante</td>
			<td>Rechazo</td>
			<td>Caidas</td>
			<td>Embarcadas</td>
			<td>Codigo Empaque</td>
			<td>Calidad</td>
			</tr>
         </thead>	 
         <tbody>
{/report_header}

{report_header group="txp_PtoDescripcion"}			
  		<tr>
			<td height="40" class="colhead" colspan={$gsNumCols} align="left"> PUERTO: {$rec.txp_PtoDescripcion}</td>
       		</tr>

{/report_header}
{report_header group="txp_NombBuque"}			
  		<tr>
			<td height="30" class="colhead" colspan={$gsNumCols} align="left"> VAPOR: {$rec.txp_NombBuque}</td>
       		</tr>
{/report_header}

{report_header group="txp_Bodega"}			
  		<tr>
			<td class="colhead" colspan={$gsNumCols} align="left"> BODEGA: {$rec.txp_Bodega} - {$rec.txp_Piso}</td>
       		</tr>
{/report_header}
{report_header group="txp_Piso"}			
  		<tr>
			<td class="colhead" colspan={$gsNumCols} align="left"> PISO: {$rec.txp_Piso}</td>
       		</tr>
{/report_header}

{report_detail}
	<tr>
			<td>{$rec.txp_Fecha}</td>
			<td>{$rec.txp_Semana}</td>
			<td>{$rec.txp_Productor}</td>
			<td>{$rec.txp_CajDescrip}</td>
			<td>{$rec.txp_Producto}</td>
			<td>{$rec.txp_NumTarja}</td>
			<td>{$rec.txp_Bodega}</td>
			<td>{$rec.txp_Piso}</td>
			<td>{$rec.txp_CantDespachada|number_format:0}</td>
			<td>{$rec.txp_Faltante|number_format:0}</td>
			<td>{$rec.txp_CantRechazada|number_format:0}</td>
			<td>{$rec.txp_CantCaidas|number_format:0}</td>
			<td>{$rec.txp_CantNeta|number_format:0}</td>
			<!--<td>{$rec.txp_Observaciones|number_format:0}</td>-->
			<td>{$rec.txp_CodOrigen}</td>
			<td>{$rec.txp_ResCalidad|number_format:2}</td>
         </tr>
{/report_detail}


{report_footer group="txp_Piso"}
		  <tr> 
			<td height="21">&nbsp;</td>
			<td colspan="4">SUMATORIA:  {$rec.txp_Piso}</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{$sum.txp_CantDespachada|number_format:0}</td>
			<td>{$sum.txp_Faltante|number_format:0}</td>
			<td>{$sum.txp_CantRechazada|number_format:0}</td>
			<td>{$sum.txp_CantCaidas|number_format:0}</td>
			<td>{$sum.txp_CantNeta|number_format:0}</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
{/report_footer}

{report_footer group="txp_Bodega"}
		  <tr> 
			<td height="21">&nbsp;</td>
			<td colspan="4">SUMATORIA:  {$rec.txp_Bodega}</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{$sum.txp_CantDespachada|number_format:0}</td>
			<td>{$sum.txp_Faltante|number_format:0}</td>
			<td>{$sum.txp_CantRechazada|number_format:0}</td>
			<td>{$sum.txp_CantCaidas|number_format:0}</td>
			<td>{$sum.txp_CantNeta|number_format:0}</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
{/report_footer}



{report_footer group="txp_NombBuque"}
		  <tr> 
			<td height="21">&nbsp;</td>
			<td colspan="4">SUMATORIA:  {$rec.txp_NombBuque}</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{$sum.txp_CantDespachada|number_format:0}</td>
			<td>{$sum.txp_Faltante|number_format:0}</td>
			<td>{$sum.txp_CantRechazada|number_format:0}</td>
			<td>{$sum.txp_CantCaidas|number_format:0}</td>
			<td>{$sum.txp_CantNeta|number_format:0}</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
{/report_footer}

{report_footer group="txp_PtoDescripcion"}
		  <tr> 
			<td height="21">&nbsp;</td>
			<td colspan="4">SUMATORIA:  {$rec.txp_PtoDescripcion}</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{$sum.txp_CantDespachada|number_format:0}</td>
			<td>{$sum.txp_Faltante|number_format:0}</td>
			<td>{$sum.txp_CantRechazada|number_format:0}</td>
			<td>{$sum.txp_CantCaidas|number_format:0}</td>
			<td>{$sum.txp_CantNeta|number_format:0}</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
{/report_footer}

{report_footer}
		  <tr> 
			<td height="21">&nbsp;</td>
			<td colspan="4">T O T A L</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{$sum.txp_CantDespachada|number_format:0}</td>
			<td>{$sum.txp_Faltante|number_format:0}</td>
			<td>{$sum.txp_CantRechazada|number_format:0}</td>
			<td>{$sum.txp_CantCaidas|number_format:0}</td>
			<td>{$sum.txp_CantNeta|number_format:0}</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
{/report_footer}

</tfoot>

</table>
{/report}
</body>
</html>
