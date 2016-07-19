<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para Reporte de Cajas Embarcadas por Semana y Cliente -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8;" />
  <meta name="author" content="Gina Franco" />
  <!-- link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" /--> 
   <title>REPORT </title>
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec resort=true}
{report_header}
    <!-- <table cellspacing=0 style="font-size:0.8em; width:19cm; border:solid;border-width:1px;">
	<tr style="vertical-align:middle; text-align: center;">
            <td style="border:solid; border-width:1px; width:7cm;"><img src="../Images/InformeCliente/Logo_140x120.jpg"></td>
	    <td style="border:thin;  border-width:1px; width:7cm; background-color:#A7E0F9"><strong>{$rec.EDir}</strong><br>
											    <strong>{$rec.ECiu},{$rec.EPai}</strong><br>
											    <strong>Phone {$rec.ETel}</strong></td> 
    </table>-->
      <table cellspacing=0 style="font-size:1.0em; width:19cm;height:auto;">
	<tr style="vertical-align:middle; text-align: center;">
            <td style=" width:7cm; vertical-align:middle;"><img src="../Images/InformeCliente/Logo_84x72.jpg"></td>
	    <td valign="middle" style="width:7cm; vertical-align:middle; background-color:#A7E0F9;">{$rec.EDir}<br>{$rec.ECiu}, {$rec.EPai}<br>Phone {$rec.ETel}</td>
	
	</tr>
      </table>
      <br><br>      
      <table  style="background-color:#A7E0F9;font-size:1.0em;">
	<tr>
	  <td style="width:6cm; vertical-align:middle;"> REF NO. FRT EC-{$rec.Semana}-001</td>
	  <td style="width:6cm; vertical-align:middle;"> LOADING SURVEY REPORT </td>
	  <td style="width:4cm; vertical-align:middle;"> {$rec.FechaImp}</td>
	</tr>
      </table>
      <br><br>      
      <table style="font-size:0.8em;">
	<tr>
	  <td style="width:6cm; vertical-align:middle;" colspan=2>MAIN PARTICULARS OF THE CARGO</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> TO: </td>
	  <td style="width:6cm; vertical-align:middle;">{$rec.Cliente}</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> SHIPPER: </td>
	  <td style="width:6cm; vertical-align:middle;">{$rec.Shipper}</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> DESCRIBED AS: </td>
	  <td style="width:6cm; vertical-align:middle;">{$rec.Producto}</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> QUANTITY: </td>
	  <td style="width:6cm; vertical-align:middle;">{$sum.CajasTotales}</td>
	</tr>
      </table>
      <br><br>      
      <table style="font-size:0.8em;">
	<tr>
	  <td style="width:6cm; vertical-align:middle;" colspan=2>CARGO DESCRIPTION</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Weight according to the Exporter: </td>
	  <td style="width:6cm; vertical-align:middle;"> </td>
	</tr>
	<!-- <tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Type of cartons: </td>
	  <td style="width:6cm; vertical-align:middle;"> </td>
	</tr> -->
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Brands: </td>
	  <td style="width:6cm; vertical-align:middle;">{$rec.TodasMarcas}</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Vessel: </td>
	  <td style="width:6cm; vertical-align:middle;">{$rec.Vapor}</td>
	</tr>
      <tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Shipping Line: </td>
	  <td style="width:6cm; vertical-align:middle;">{$rec.AgenciaNaviera}</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Port of loading: </td>
	  <td style="width:6cm; vertical-align:middle;">{$rec.PtoEmbarque}</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Port of destination: </td>
	  <td style="width:6cm; vertical-align:middle;">{$rec.PtoDestino}</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Date of sailing: </td>
	  <td style="width:6cm; vertical-align:middle;">{$rec.FechaSalida}</td>
	</tr>
	<tr>
	  <td style="width:6cm; vertical-align:middle;background-color:#A7E0F9;"> Our reference: </td>
	  <td style="width:6cm; vertical-align:middle;">FRT EC-{$rec.Semana}-001</td>
	</tr>
      </table>
      <br><br>      
      <table style="font-size:0.8em;">
	<tr>
	  <td style="width:2cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Container Number</td>
	  <td style="width:3cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Producer </td>
	  <td style="width:1cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Code</td>
	  <td style="width:2cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Brand</td>
	  <td style="width:2cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Quantity of Boxes </td>
	  <td style="width:2cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Kind of Package</td>
	  <td style="width:1cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Net Weight (kg)</td>
	  <td style="width:1cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Bag Weight(kg) </td>
	  <td style="width:2cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Quality</td>
	  <td style="width:1cm; text-align:center; font-weight:bold; vertical-align:middle;background-color:#A7E0F9;"> Pod Length (cm)</td>
	</tr>
     <!-- <td style="width:7cm;">&nbsp;</td> -->
{/report_header}

{report_detail}
	<tr>
	  <td style="vertical-align:middle; text-align:center;">{$rec.Contenedor}</td>
	  <td style="vertical-align:middle; text-align:left;">{$rec.Productor}</td>
	  <td style="vertical-align:middle; text-align:center;">{$rec.Hacienda}</td>
	  <td style="vertical-align:middle; text-align:left;">{$rec.Marca}</td>
	  <td style="vertical-align:middle; text-align:right;">{$rec.Embarcadas}</td>
	  <td style="vertical-align:middle; text-align:center;">{$rec.Empaque}</td>
	  <td style="vertical-align:middle; text-align:center;"> </td>
	  <td style="vertical-align:middle; text-align:center;"> </td>
	  <td style="vertical-align:middle; text-align:right;">{$rec.Calidad}</td>
	  <td style="vertical-align:middle; text-align:center;"> </td>
	</tr>
{/report_detail}

{report_footer}
 </table>
{/report_footer}

{/report}
</body>
