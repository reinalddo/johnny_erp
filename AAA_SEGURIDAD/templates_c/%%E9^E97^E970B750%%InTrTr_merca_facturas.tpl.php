<?php /* Smarty version 2.6.18, created on 2015-09-16 12:02:45
         compiled from InTrTr_merca_facturas.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_merca_facturas.tpl', 79, false),array('block', 'report_header', 'InTrTr_merca_facturas.tpl', 82, false),array('block', 'report_detail', 'InTrTr_merca_facturas.tpl', 131, false),array('modifier', 'date_format', 'InTrTr_merca_facturas.tpl', 94, false),)), $this); ?>
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
    <?php echo '
    <script type="text/javascript">
      
       $(document).ready(function() {
            $( "#pDesde" ).datepicker({ dateFormat : "yy-mm-dd" });
             $( "#pHasta" ).datepicker({ dateFormat : "yy-mm-dd" });
	$(\'#example\').DataTable({"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === \'string\' ?
                    i.replace(/[\\$,]/g, \'\')*1 :
                    typeof i === \'number\' ?
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
                .column( 4, { page: \'current\'} )
                .data()
                .reduce( function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0 );
 
            // Update footer
            $( api.column( 3 ).footer() ).html(
                \'$\'+pageTotal.toFixed(2) 
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
    '; ?>

</head>

<body id="top">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>


<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
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
    <!--<div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>-->
    </br>   
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong></strong><br>
        <strong>REPORTE DE FACTURA<strong><br>
        <p style="line-height:0.5em;"><strong>Usuario: </strong><?php echo $_SESSION['g_user']; ?>
</p>
        <p style="line-height:0.5em;"><strong>Fecha Imp.: </strong><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</p>
	<p style="line-height:0.5em;"><?php echo $this->_tpl_vars['agArchivo']; ?>
</p>
    </p>
    </div>
    <?php $this->assign('cols', 10); ?>
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
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<tbody>


<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
        <td><?php echo $this->_tpl_vars['rec']['SEC']; ?>
</td>
        <td><?php echo $this->_tpl_vars['rec']['FTR']; ?>
</td>
        <td><?php echo $this->_tpl_vars['rec']['SHO']; ?>
</td>
        <td><?php echo $this->_tpl_vars['rec']['COU']; ?>
</td>
        <td><?php echo $this->_tpl_vars['rec']['TDC']; ?>
</td>        
    </tr>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</tbody>
    </table>
</center>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        
</body>