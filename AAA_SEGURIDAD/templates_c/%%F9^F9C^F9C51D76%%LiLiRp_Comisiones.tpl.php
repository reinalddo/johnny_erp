<?php /* Smarty version 2.6.18, created on 2012-06-01 16:04:25
         compiled from ../Li_Files/LiLiRp_Comisiones.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', '../Li_Files/LiLiRp_Comisiones.tpl', 16, false),array('block', 'report_header', '../Li_Files/LiLiRp_Comisiones.tpl', 18, false),array('block', 'report_detail', '../Li_Files/LiLiRp_Comisiones.tpl', 44, false),array('block', 'report_footer', '../Li_Files/LiLiRp_Comisiones.tpl', 53, false),array('modifier', 'number_format', '../Li_Files/LiLiRp_Comisiones.tpl', 47, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para el REPORTE COSTOS: COMISIONES-TRANSPORTE (formato de Aplesa) -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>COSTOS</title>
  <?php $this->assign('nGrp', 1); ?>
</head> 

<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => 'TxtipoVariable','resort' => false)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <table border=1 cellspacing=0 >   
  <tbody>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:10pt;">
      <td class="headerrow" colspan=4><?php echo $this->_tpl_vars['subtitulo']; ?>
 <?php echo $this->_tpl_vars['rec']['EGNombre']; ?>
</td>   
    </tr>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:8pt;">
      <td class="headerrow" colspan=4><?php echo $this->_tpl_vars['subtitulo2']; ?>
</td>   
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'TxtipoVariable')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <?php $this->assign('nGrp', $this->_tpl_vars['rec']['TxtipoVariable']); ?>
    <tr style="font-weight:bold; text-align: left; vertical-align:middle; font-size:8pt;">
      <td colspan=4 style="background-color: #CED8F6; font-size:10;"><?php echo $this->_tpl_vars['rec']['TxtipoVariable']; ?>
 WK <?php echo $this->_tpl_vars['rec']['lde_semana']; ?>
</td>
    </tr>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:8pt;">
      <td class="headerrow" style="background-color: #BDBDBD;">Comisionista</td>
      <td class="headerrow" style="background-color: #BDBDBD;">Cajas</td>
      <td class="headerrow" style="background-color: #BDBDBD;">Precio</td>
      <td class="headerrow" style="background-color: #BDBDBD;">Total</td>
    </tr>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <tr style="vertical-align:middle; font-size:8pt;">
	<td class="coldata" nowrap style="width:2cm;"><?php echo $this->_tpl_vars['rec']['Txauxiliar']; ?>
</td>
        <td class="colnum" nowrap style="width:4cm;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['lde_cajas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
        <td class="colnum" nowrap style="width:3cm;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['lde_precio'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td class="colnum " nowrap style="width:3cm;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PrecioTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array('group' => 'TxtipoVariable')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; ">
	<td colspan="1" style="text-align:left;">Total <?php echo $this->_tpl_vars['rec']['TxtipoVariable']; ?>
:</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['lde_cajas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
<br><br></td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['PrecioTotal']/$this->_tpl_vars['sum']['lde_cajas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
<br><br></td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['PrecioTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
<br><br></td>
    </tr>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; background-color: #BDBDBD;">
	<td colspan="1">TOTAL COSTOS:</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['lde_cajas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['PrecioTotal']/$this->_tpl_vars['sum']['lde_cajas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['PrecioTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
    </tr>
  </tbody>
  </table>
      
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>