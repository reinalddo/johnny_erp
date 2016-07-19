<?php /* Smarty version 2.6.18, created on 2014-12-30 08:20:34
         compiled from InTrTr_contenedor.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_contenedor.tpl', 36, false),array('block', 'report_header', 'InTrTr_contenedor.tpl', 39, false),array('block', 'report_detail', 'InTrTr_contenedor.tpl', 82, false),array('modifier', 'date_format', 'InTrTr_contenedor.tpl', 44, false),array('function', 'html_options', 'InTrTr_contenedor.tpl', 58, false),)), $this); ?>
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
    <?php echo '
    <script type="text/javascript">
      
       $(document).ready(function() {
	$(\'#example\').DataTable({ "language": {
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
    '; ?>

</head>

<body id="top">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>


<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>-->
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>
    
    <hr/>
    <!--<div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>-->
    </br>   
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong></strong><br>
        <strong>REPORTE DE CONTENEDORES<strong><br>
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
        <select id="pDias" name=pDias onchange="fActualiza()"> 
   <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['day_options'],'selected' => $this->_tpl_vars['day_id']), $this);?>

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
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<tbody>


<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
        <td><?php echo $this->_tpl_vars['rec']['SEC']; ?>
</td>
        <td><?php echo $this->_tpl_vars['rec']['SHO']; ?>
</td>
        <td><?php echo $this->_tpl_vars['rec']['COU']; ?>
</td>
        <td><?php echo $this->_tpl_vars['rec']['WOR']; ?>
</td>        
        <td><?php echo $this->_tpl_vars['rec']['INR']; ?>
</td>                
        <td><?php echo $this->_tpl_vars['rec']['TAG']; ?>
</td>                
        <td><?php echo $this->_tpl_vars['rec']['CON']; ?>
</td>               
	<td><?php echo $this->_tpl_vars['rec']['ITE']; ?>
</td>                
        <td><?php echo $this->_tpl_vars['rec']['NAM']; ?>
</td>                
        <td><?php echo $this->_tpl_vars['rec']['SER']; ?>
</td>
        <td><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
    </tr>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</tbody>
    </table>
</center>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        
</body>