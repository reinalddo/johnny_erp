<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Reporte de Repuestos en Garantia
		Desarrollo para al compañia Farbem S.A.
		Presenta los Items en STOCK de la Bodega de Garantia, siempre y cuando su fecha de Ingreso a la bodega
		este dentro del periodo de dias de CUSTODIA -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Smart" />    
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" href="../LibJs/DataTables/media/css/jquery.dataTables.css">
      <link rel="stylesheet" type="text/css" href="../LibJs/DataTables/media/css/jquery-ui.min.css">
  <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/jquery.js"></script>
  <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/jquery.dataTables.js"></script>
  <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/jquery-ui.min.js"></script>
    <title>REPORTE DE FACTURAS</title>
    {literal}
    <script type="text/javascript">
      
       $(document).ready(function() {
            $( "#pDesde" ).datepicker({ dateFormat : "yy-mm-dd" });
             $( "#pHasta" ).datepicker({ dateFormat : "yy-mm-dd" });
	$('#example').DataTable({"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                } );
 
            // Total over this page
            pageTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0 );
 
            // Update footer
            $( api.column( 3 ).footer() ).html(
                '$'+pageTotal.toFixed(2) 
            );
        },paging: false, "language": {
                    "url": "../LibJs/DataTables/media/dataTables.spanish.lang"
                    }, "columnDefs": [
                    {
                        "targets": [ 4 ],
                        "visible": false,
                        "searchable": false
                    }]
});
} );
      
      function fGenExcel(){          
        window.open("InTrTr_merca_facturasexcel.rpt.php?pDesde=" + document.getElementById("pDesde").value + "&pHasta="  + document.getElementById("pHasta").value);
      }
      
      function fActualiza(){
        window.open("InTrTr_merca_facturas.rpt.php?pDesde=" + document.getElementById("pDesde").value + "&pHasta="  + document.getElementById("pHasta").value,"_self");
      }
      
  </script>
    {/literal}
</head>

<body id="top">
{report recordset=$agData record=rec resort=true}


{report_header}
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>-->
    <div align="center">
        <table>
            <tr>
    <td>Fecha Inicio</td><td><input type="text" name="pDesde" id="pDesde"></td>
       <td>Fecha Fin </td><td><input type="text" name="pHasta" id="pHasta"></td>
       <td><input type ="button"  onClick="fActualiza()" value="Filtrar"> <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/></td>
    </tr>
    </table>
    </div>
    <hr/>
    <!--<div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>-->
    </br>   
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{*$smarty.session.g_empr*}</strong><br>
        <strong>REPORTE DE FACTURA<strong><br>
        <p style="line-height:0.5em;"><strong>Usuario: </strong>{$smarty.session.g_user}</p>
        <p style="line-height:0.5em;"><strong>Fecha Imp.: </strong>{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</p>
	<p style="line-height:0.5em;">{$agArchivo}</p>
    </p>
    </div>
    {assign var=cols value=10}
    <center>
        
  
    <table id="example" >
	

  <thead>
    <tr>            
            <td>Fecha Factura</td>
            <td>Numero de Factura</td>
            <td>Orden</td>
            <td>Semana</td>
            <td>Valor</td>            
        </tr>
      </thead>
        <tfoot>
						<tr>
							<th colspan="4" style="text-align:right">Total:</th>
							<th></th>
						</tr>
					</tfoot>
{/report_header}
<tbody>


{report_detail}
    <tr>
        <td>{$rec.SEC}</td>
        <td>{$rec.FTR}</td>
        <td>{$rec.SHO}</td>
        <td>{$rec.COU}</td>
        <td>{$rec.TDC}</td>        
    </tr>
    
{/report_detail}
</tbody>
    </table>
</center>
{/report}
        
</body>