<?php /* Smarty version 2.6.18, created on 2015-06-23 17:50:22
         compiled from InTrTr_contenedorexcel_saldos.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_contenedorexcel_saldos.tpl', 22, false),array('block', 'report_header', 'InTrTr_contenedorexcel_saldos.tpl', 25, false),array('block', 'report_detail', 'InTrTr_contenedorexcel_saldos.tpl', 67, false),array('modifier', 'date_format', 'InTrTr_contenedorexcel_saldos.tpl', 29, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Reporte de Repuestos en Garantia
		Desarrollo para al compa�ia Farbem S.A.
		Presenta los Items en STOCK de la Bodega de Garantia, siempre y cuando su fecha de Ingreso a la bodega
		este dentro del periodo de dias de CUSTODIA -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="Johnny Valencia" content="smart" />
  <?php echo '
  <style type="text/css">
        .num{text-align:right;}
	.subtitulo{font-size:10px;}
	.subtotal{font-style:italic; font-weight:bold;}
	.total{font-size:12px; font-weight:bold;margin-top:20px; padding-top:20px;}
    </style>
  '; ?>
    
  <title>REPORTE DE REPUESTOS EN GARANTIA</title> 
</head>

<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>


<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>-->
    <hr/>
    <!--<div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>-->
    </br>   
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong></strong><br>
        <strong>REPORTE DE REPUESTOS EN GARANTIA<strong><br>
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
    <table border=1 cellspacing=0 >
	

  
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">#.</td>
            <td class="headerrow">Fecha Transaccion</td>
            <td class="headerrow">Nombre Empresa</td>
            <td class="headerrow">Pais</td>
            <td class="headerrow">Tipo</td>
            <td class="headerrow">Comprobante</td>
            <td class="headerrow">Orden de trabajo</td>
            <td class="headerrow">Ref. Interna</td>
            <td class="headerrow">Etiqueta</td>
            <td class="headerrow">Contenedor</td>
            <td class="headerrow">Item</td>
            <td class="headerrow">Descripcion</td>
            <td class="headerrow">Serie</td>
            <td class="headerrow">Serie Nueva</td>
            <td class="headerrow">Garantia</td>      
        </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>



<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['SEC']; ?>
</td>
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['FTR']; ?>
</td>
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['SHO']; ?>
</td>
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['COU']; ?>
</td>
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['TDC']; ?>
</td>
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['NDC']; ?>
</td>
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['WOR']; ?>
</td>        
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['INR']; ?>
</td>                
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['TAG']; ?>
</td>                
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['CON']; ?>
</td>               
		  <td class="coldata"><?php echo $this->_tpl_vars['rec']['ITE']; ?>
</td>                
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['NAM']; ?>
</td>                
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['SER']; ?>
</td>
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['DSN']; ?>
</td>
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>  
    </tr>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
 </table>
</center>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
  
</body>