<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para el reporte de comprobantes (PC - Dinaser) -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <!--link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" /-->
  <!--link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" /-->
    <title>COMPROBANTE</title>
  
</head>

<body id="top" style="font-size:11px; font-family: sans-serif;">
{report recordset=$agData record=rec}

{report_header}
    <table cellspacing=0 style=" margin-top:20px; width:100%;">
        <tr>
            <td style="width:60%; text-align: center; font-size:14px;"><strong>{$smarty.session.g_empr}</strong></td>
            <td style="width:10%; text-align: left;"><strong>RUC:</strong></td>
            <td style="width:30%; text-align: left;">{$rec.RUC}</td>
        </tr>
        <tr>
            <td collspan = 3 style="width:100%; text-align: center; font-size:12px;"><strong>{$rec.TXT}</strong></td>
        </tr>
    </table>
    
    
    <table cellspacing=0 style=" margin-top:20px; width:100%;">
            <tr>
               <td style="width:10%;"><strong>COMPROBANTE:</strong></td>
               <td style="width:60%;">{$rec.TIP}-{$rec.COM}</td>
               <td style="width:10%;"><strong>FECHA</strong></td>
               <td style="width:10%;">{$rec.FCO}</td>
            </tr>
            <tr>
               <td style="width:10%;"><strong>PROVEEDOR</strong></td>
               <td style="width:60%;">{$rec.NRE}</td>
               <td style="width:10%;"><strong>EMISION</strong></td>
               <td style="width:10%;">{$rec.FTR}</td>
            </tr>
            <tr>
               <td style="width:10%;"><strong>CONCEPTO</strong></td>
               <td style="width:60%;">{$rec.CON}</td>
               <td style="width:10%;"><strong>VENCIMIENTO</strong></td>
               <td style="width:10%;">{$rec.FVE}</td>
            </tr>
    </table>
            
   <table cellspacing=0 style=" margin-top:20px;width:100%;">
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td style="width:10%;">CTA/AUX</td>
            <td style="width:65%;"><strong>DESCRIPCION</strong></td>
            <td style="width:5%;"># DOC</td>
            <td style="width:10%;"><strong>DEBITO</strong></td>
            <td style="width:10%;"><strong>CREDITO</strong></td>
        </tr>
        <tr><td style="width:100%;"colspan=5><hr style="height:1px; border-bottom: 1px solid;"></td></tr>
    </table>
{/report_header}
{report_detail}
    <table cellspacing=0 style=" margin-top:0px;width:100%;">
    <tr style="text-align:left; vertical-align:middle;">
              <td style="width:10%">{$rec.CCU}<br>&nbsp;&nbsp;&nbsp;&nbsp;{$rec.CAU}</td>
              <td style="width:65%">{$rec.CUE2}<br>{$rec.DES}</td> <!-- PARA DINASER -->
              <td style="width:5%; text-align: center;">{$rec.CHE}</td>
              <td style="width:10%; text-align: right;">{$rec.VDB|number_format:2}</td>
              <td style="width:10%; text-align: right;">{$rec.VCR|number_format:2}</td>
              
              {assign var=VDB value=$VDB+$rec.VDB}
              {assign var=VCR value=$VCR+$rec.VCR}
              
        </tr>
{/report_detail}

{report_footer}
    <tr style="font-weight:bold;text-align:left; vertical-align:middle;">
       <td style="width:10%;"></td>
       <td style="width:65%;">SUMAN&nbsp;&nbsp;&nbsp;U.S.&nbsp;&nbsp;Dolares: </td>
       <td style="width:5%;"></td>
       <td style="width:10%; text-align: right;">{$VDB|number_format:2}</td>
       <td style="width:10%; text-align: right;">{$VCR|number_format:2}</td>
    </tr>
   </table>
  
  <table cellspacing=0 style=" margin-top:5px;width:100%;">
        <tr><td style="width:100%;"colspan=4><hr style="height:1px; border-bottom: 1px solid;"></td></tr>
        <tr>
            <td style="width:25%; height:2cm; vertical-align:bottom; text-align:center;">_____________________ <br> Emitido Por</td>
            <td style="width:25%; height:2cm; vertical-align:bottom; text-align:center;">_____________________ <br> Contabilidad</td>
            <td style="width:25%; height:2cm; vertical-align:bottom; text-align:center;">_____________________ <br> Gerencia</td>
            <td style="width:25%; height:2cm; vertical-align:bottom; text-align:center;">_____________________ <br> Recibi Conforme</td>
        </tr>
        <tr>
            <td colspan="4"><br><b>Usuario:</b> {$smarty.session.g_user} </td>
        </tr>
        <tr>
            <td colspan="4"><b>Fecha de Impresion:</b> {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</td>
        </tr>
    </table>
{/report_footer}

{/report}
</body>