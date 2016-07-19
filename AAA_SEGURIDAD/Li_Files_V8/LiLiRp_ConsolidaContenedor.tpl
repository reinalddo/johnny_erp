<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para Reporte Consolidado de Contenedores -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CONSOLIDACION DE CONTENEDORES</title>
  
</head> 

<body id="top" style="font-family:'Arial'">
{assign var=acum value=0}
{assign var=sal value=0}

{report recordset=$agData record=rec groups="GrpVapor,GrpCont"}

{report_header}
  <table style="width:29.80cm; text-align:center;">
    <tr><td>	    <p style="font-size:12pt;"><strong>{$smarty.session.g_empr}</strong></p>
		    <p style="font-size:14pt">{$subtitulo}</p>
		    <p style="font-size:10pt">{$subtitulo2}</p>
		    <hr>
    </td>
    </tr>
  </table>
{/report_header}



{report_header group="GrpVapor"}
      <table border="0" style="font-size:12pt; width:29.80cm; background-color:#CEE3F6">
      <tr style="text-align:left;background-color:#FFFFFF">
	  <td colspan=6><br><br><br></td>
      </tr>
      <tr style="text-align:left;">
	  <td style="width:3cm;font-weight:bold;">VAPOR:</td>
	  <td style="width:7cm;">{$rec.Vapor}</td>
	  <td style="width:3cm;font-weight:bold;">SEMANA:</td>
	  <td style="width:2.5cm;">{$rec.tac_Semana}</td>
	  <td style="width:3cm;font-weight:bold;">FECHA:</td>
	  <td style="width:2.5cm;">{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</td><!-- Dia de consulta del reporte -->
      </tr>
      <tr style="text-align:left;">
	  <td style="font-weight:bold;">AGENCIA:</td>
	  <td>{$rec.Agencia}</td>
	  <td style="font-weight:bold;">PUERTO:</td>
	  <td>{$rec.Puerto}</td>
	  <td style="font-weight:bold;">MODULO:</td>
	  <td>{$rec.Modulo}</td><!-- Dia de consulta del reporte -->
      </tr>
      <tr style="text-align:left;">
	  <td style="font-weight:bold;">CONSIGNATARIO:</td>
	  <td>{$rec.Consignatario}</td>
	  <td style="font-weight:bold;">DESTINO:</td>
	  <td>{$rec.PaisDestino}</td>
	  <td style="font-weight:bold;">BOOKING:</td>
	  <td>{$rec.Booking}</td><!-- Dia de consulta del reporte -->
      </tr>
      </table><br>
      <table border="1" style="width:29.80cm;">
	<tr style="text-align:center;font-weight:bold;font-size:10pt;background-color:#BDBDBD">
	  <td style="width:3cm;">CONTENEDOR NUMERO</td>
	  <td style="width:2cm;">INICIO</td>
	  <td style="width:2cm;">TERMINO</td>
	  <td style="width:2cm;">SELLO<BR>NAVIERA</td>
	  <td style="width:2cm;">SELLO<BR>EMPRESA</td>
	  <td style="width:2cm;">SELLO<BR>ANTINARCOTICO 1</td>
	  <td style="width:2cm;">SELLO<BR>ANTINARCOTICO 2</td>
	  <td style="width:2cm;">PRODUCTO</td>
	  <td style="width:1.5cm;">TIPO<BR>CARGA</td>
	  <td style="width:2cm;">TOTAL<BR>CAJAS</td>
	  <td style="width:2cm;">TIPO<BR>EMPAQUE</td>
	  <td style="width:2cm;">MARCA CAJAS</td>
	  <td style="width:1cm;">PESO</td>
	  <td style="width:2cm;">TERMOGRAFO</td>
	  <td style="width:1cm;">CODIGO<BR>PRODUCTOR</td>
	  <td style="width:3cm;">PRODUCTOR</td>
	  <td style="width:2cm;">CANTIDAD</td>
      </tr>
{/report_header}

{report_header group="GrpCont"}
      <tr style="text-align:left;font-size:10pt;">
	  <td rowspan={$rec.NVap_Con} style="vertical-align:middle;">{$rec.tac_Contenedor}</td>
	  <td rowspan={$rec.NVap_Con} style="vertical-align:middle;">{$rec.emb_FecInicio}</td>
	  <td rowspan={$rec.NVap_Con} style="vertical-align:middle;">{$rec.emb_FecTermino}</td>
	  <td rowspan={$rec.NVap_Con} style="vertical-align:middle;">{$rec.SelloNaviera}</td>
	  <td rowspan={$rec.NVap_Con} style="vertical-align:middle;">{$rec.SelloEmpresa}</td>
	  <td rowspan={$rec.NVap_Con} style="vertical-align:middle;">{$rec.SelloAntiNarc1}</td>
	  <td rowspan={$rec.NVap_Con} style="vertical-align:middle;">{$rec.SelloAntiNarc2}</td>
	  <td rowspan={$rec.NVap_Con} style="vertical-align:middle;">{$rec.DescProducto}</td>
	  <td rowspan={$rec.NVap_Con} style="vertical-align:middle;">{$rec.TipoCarga}</td>
	  <td rowspan={$rec.NVap_Con} style="vertical-align:middle;text-align:right;">{$rec.TotCjaEmbarcada}</td>
	  <td rowspan={$rec.NVap_Con} style="vertical-align:middle;text-align:center;">{$rec.AbreviaturaCaja}</td>
	  <td rowspan={$rec.NVap_Con} style="vertical-align:middle;">{$rec.Marca}</td>
	  <td rowspan={$rec.NVap_Con} style="vertical-align:middle;text-align:right;">{$rec.Peso}</td>
	  <td rowspan={$rec.NVap_Con} style="vertical-align:middle;text-align:center;">{$rec.Termografo}</td>
	  
{/report_header}



{report_detail}
      <!--<tr style="text-align:left;font-size:10pt;">-->
	  <td>{$rec.CodHacienda}</td>
	  <td>{$rec.Embarcador}</td>
	  <td style="text-align:right;">{$rec.CjaEmbarcada}</td>      
      </tr>
{/report_detail}

{report_footer}
   <!--  <tr style="font-weight:bold;vertical-align:middle; text-align:right; background-color: #BDBDBD;">
       <td colspan=3">TOTALES:</td>
           {assign var=tpPcajLiq value=$tpcajLiq/$tcajEmb}
       <td>{$tpPcajLiq|number_format:2:",":"."}</td> </tr>
     <tr>
      <td colspan="24" style="text-align:left"> {$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} </td></tr> -->
  </table>
{/report_footer}

{/report}
</body>