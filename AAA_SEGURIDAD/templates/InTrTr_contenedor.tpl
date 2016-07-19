<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Antonio Rodriguez" />    
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" href="../LibJs/DataTables/media/css/jquery.dataTables.css">
  <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/jquery.js"></script>
  <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/jquery.dataTables.js"></script>
    <title>REPORTE DE CONTENEDORES</title>
    {literal}
    <script type="text/javascript">
      
       $(document).ready(function() {
	$('#example').DataTable({ "language": {
                    "url": "../LibJs/DataTables/media/dataTables.spanish.lang"
                }});
} );
      
      function fGenExcel(){
        window.open("InTrTr_contenedorexcel.rpt.php?pDias=" + document.getElementById("pDias").value);
      }
      
      function fActualiza(){
        window.open("InTrTr_contenedor.rpt.php?pDias=" + document.getElementById("pDias").value,"_self");
      }
      
  </script>
    {/literal}
</head>

<body id="top">
{report recordset=$agData record=rec resort=true}


{report_header}
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>-->
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>
    
    <hr/>
    <!--<div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>-->
    </br>   
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{*$smarty.session.g_empr*}</strong><br>
        <strong>REPORTE DE CONTENEDORES<strong><br>
        <p style="line-height:0.5em;"><strong>Usuario: </strong>{$smarty.session.g_user}</p>
        <p style="line-height:0.5em;"><strong>Fecha Imp.: </strong>{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</p>
	<p style="line-height:0.5em;">{$agArchivo}</p>
    </p>
    </div>
    {assign var=cols value=10}
    <center>
        <select id="pDias" name=pDias onchange="fActualiza()"> 
   {html_options options=$day_options selected=$day_id}
</select>
    <table id="example" >
	

  <thead>
    <tr>
            <td>#.</td>
            <td>Nombre Empresa</td>
            <td>Pais</td>
            <td>WorkOrder</td>
            <td>Ref. Interna</td>
            <td>Tag</td>
            <td>Contenedor</td>
            <td>Item</td>
            <td>Nombre</td>
            <td>Serie</td>       
            <td>Periodo</td>       
        </tr>
      </thead>
{/report_header}
<tbody>


{report_detail}
    <tr>
        <td>{$rec.SEC}</td>
        <td>{$rec.SHO}</td>
        <td>{$rec.COU}</td>
        <td>{$rec.WOR}</td>        
        <td>{$rec.INR}</td>                
        <td>{$rec.TAG}</td>                
        <td>{$rec.CON}</td>               
	<td>{$rec.ITE}</td>                
        <td>{$rec.NAM}</td>                
        <td>{$rec.SER}</td>
        <td>{$rec.PER}</td>
    </tr>
    
{/report_detail}
</tbody>
    </table>
</center>
{/report}
        
</body>