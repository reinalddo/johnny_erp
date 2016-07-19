<?php /* Smarty version 2.6.18, created on 2010-07-14 11:08:26
         compiled from InTrTr_facturas.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_facturas.tpl', 11, false),array('block', 'report_header', 'InTrTr_facturas.tpl', 13, false),array('block', 'report_detail', 'InTrTr_facturas.tpl', 51, false),array('block', 'report_footer', 'InTrTr_facturas.tpl', 71, false),array('modifier', 'number_format', 'InTrTr_facturas.tpl', 61, false),array('modifier', 'date_format', 'InTrTr_facturas.tpl', 76, false),)), $this); ?>
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $this->_tpl_vars['gsSubTitul']; ?>
</title>
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/report.css" title="CSS para pantalla" />
</head>
<body align:"center" id="top" style="font-family:'Arial'; ">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "bodega, productor",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<table width="98%" border="1" cellpadding="0" cellspacing="0">
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr >
			  <td class="colhead" colspan=11 align="center" style="text-align: center; font-weight:bold; ">REPORTE DE FACTURACION DE INSUMOS</td>
			</tr>
			<tr>
			  <td class="colhead" colspan=11 align="center" style="text-align: center; font-weight:bold; ">MODULO INVENTARIO</td>
			</tr>
			<tr>
			  <td class="colhead"  colspan=11 align="center" style="text-align: center; font-weight:bold; ">Rango: <?php echo $this->_tpl_vars['dh']; ?>
 </td>
			</tr>
	      		    
			<tr>
			<td>Semana</td>
			<td>Bodega</td>
			<td>Productor</td>
			<td>Libro</td>
			<td>Emisión</td>
			<td>Factura</td>
			<td colspan=2 align="left">Item</td>
			<td>Cantidad</td>
			<td>Valor_Uni</td>
			<td>Base_Impo</td>
			<td>%_IVA</td>
			<td>IVA_Grab</td>
			<td>Total</td>
			
			</tr>
         </thead>	 
         <tbody>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<tr style="white-space:nowrap;">
			<td><?php echo $this->_tpl_vars['rec']['semana']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['bodega']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['productor']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['Libro']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['fecContab']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['factura']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['coditem']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['item']; ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['cantidad'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>	
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['valor_unitario'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['BASE_IMPONIBLE'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['PORCENTAJE_IVA']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['IVA']; ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['TOTAL'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
         </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		  <tr> 
			<td colspan="14" style="text-align:right">TOTAL : <?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['TOTAL'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
		  </tr>
		  <tr> 
			<td colspan="13" style="text-align:left"><?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</td>
			<td><?php echo $this->_tpl_vars['PiePagina']; ?>
</td>
		  </tr>
  
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

</table>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>
</html>