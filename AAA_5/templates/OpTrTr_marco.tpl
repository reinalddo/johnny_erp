<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Veremos</title>
</head>

<body align:"center" id="top" style="font-family:'Arial'; ">
<table width="100%" border="1" cellpadding="0" cellspacing="0">
<thead>
			<tr>
			          	<td colspan="15" align="center">{$agDat.empresa}</td>
			</tr>
			<tr>
						<td colspan="15" align="center">xxxxxxxx</td>
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
				<td>Codigo_Embarque</td>
				<td>Calidad</td>
   		    </tr>
</thead>
<tbody>
  			<tr>
        		<td>{$agDat.fecha}</td>
          		<td>{$agDat.semana}</td>
          		<td>{$agDat.prod}</td>
          		<td>{$agDat.emp}</td>
				<td>{$agDat.tip_fruta}</td>
				<td>{$agDat.tarja}</td>
          		<td>{$agDat.bod}</td>
				<td>{$agDat.piso}</td>
				<td>{$agDat.desp}</td>
				<td>{$agDat.falt}</td>
				<td>{$agDat.rech}</td>
				<td>{$agDat.caid}</td>
				<td>{$agDat.embar}</td>
				<td>{$agDat.numempa}</td>
				<td>{$agDat.calid}</td>
       		</tr>
<tbody>
<tfoot>
		  <tr> 
			<td height="21">&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{$agDat.tot_desp}</td>
			<td>{$agDat.tot_falt}</td>
			<td>{$agDat.tot_rech}</td>
			<td>{$agDat.tot_caid}</td>
			<td>{$agDat.tot_embar}</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
</tfoot>
</table>
</body>
</html>
