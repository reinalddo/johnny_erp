<?php /* Smarty version 2.6.18, created on 2009-03-10 12:21:57
         compiled from LiEmTr_dettarjdiario.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'LiEmTr_dettarjdiario.tpl', 11, false),array('block', 'report_header', 'LiEmTr_dettarjdiario.tpl', 13, false),array('block', 'report_detail', 'LiEmTr_dettarjdiario.tpl', 61, false),array('block', 'report_footer', 'LiEmTr_dettarjdiario.tpl', 82, false),array('modifier', 'number_format', 'LiEmTr_dettarjdiario.tpl', 71, false),)), $this); ?>
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Veremos</title>
</head>
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />

<body align:"center" id="top" style="font-family:'Arial'; ">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "txp_PtoDescripcion, txp_NombBuque, txp_Producto",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<table width="100%" border="1" cellpadding="0" cellspacing="0">
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <caption><?php echo $this->_tpl_vars['gsEmpresa']; ?>
</caption>
	  <thead>
			<tr class="colhead">
			<td class="colhead" colspan=<?php echo $this->_tpl_vars['gsNumCols']; ?>
 align="center"><?php echo $this->_tpl_vars['gsSUbTitulo']; ?>
</td>
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
			<td>Codigo Empaque</td>
			<td>Calidad</td>
			</tr>
         </thead>	 
         <tbody>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'txp_PtoDescripcion')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>			
  		<tr>
			<td class="colhead" colspan=<?php echo $this->_tpl_vars['gsNumCols']; ?>
 align="left"> PUERTO: <?php echo $this->_tpl_vars['rec']['txp_PtoDescripcion']; ?>
</td>
       		</tr>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_header', array('group' => 'txp_NombBuque')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>			
  		<tr>
			<td class="colhead" colspan=<?php echo $this->_tpl_vars['gsNumCols']; ?>
 align="left"> VAPOR: <?php echo $this->_tpl_vars['rec']['txp_NombBuque']; ?>
</td>
       		</tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_header', array('group' => 'txp_Producto')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>			
  		<tr>
			<td class="colhead" colspan=<?php echo $this->_tpl_vars['gsNumCols']; ?>
 align="left"> PRODUCTO: <?php echo $this->_tpl_vars['rec']['txp_Producto']; ?>
</td>
       		</tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<tr>
			<td><?php echo $this->_tpl_vars['rec']['txp_Fecha']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Semana']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Productor']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_CajDescrip']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Producto']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_NumTarja']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Bodega']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Piso']; ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_CantDespachada'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_Faltante'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_CantRechazada'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_CantCaidas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_CantNeta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_Observaciones'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_ResCalidad'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
         </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array('group' => 'txp_Producto')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		  <tr> 
			<td height="40">&nbsp;</td>
			<td colspan="4">SUMATORIA:  <?php echo $this->_tpl_vars['rec']['txp_Producto']; ?>
</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantDespachada'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_Faltante'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantRechazada'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantCaidas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantNeta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array('group' => 'txp_NombBuque')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		  <tr> 
			<td height="21">&nbsp;</td>
			<td colspan="4">SUMATORIA:  <?php echo $this->_tpl_vars['rec']['txp_NombBuque']; ?>
</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantDespachada'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_Faltante'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantRechazada'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantCaidas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantNeta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array('group' => 'txp_PtoDescripcion')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		  <tr> 
			<td height="21">&nbsp;</td>
			<td colspan="4">SUMATORIA:  <?php echo $this->_tpl_vars['rec']['txp_PtoDescripcion']; ?>
</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantDespachada'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_Faltante'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantRechazada'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantCaidas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantNeta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		  <tr> 
			<td height="21">&nbsp;</td>
			<td colspan="4">T O T A L</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantDespachada'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_Faltante'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantRechazada'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantCaidas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['txp_CantNeta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

</tfoot>

</table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>
</html>