<?php /* Smarty version 2.6.18, created on 2012-06-05 17:05:47
         compiled from ../Li_Files/LiLiRp_CuadroPrecios.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', '../Li_Files/LiLiRp_CuadroPrecios.tpl', 16, false),array('block', 'report_header', '../Li_Files/LiLiRp_CuadroPrecios.tpl', 18, false),array('block', 'report_detail', '../Li_Files/LiLiRp_CuadroPrecios.tpl', 43, false),array('block', 'report_footer', '../Li_Files/LiLiRp_CuadroPrecios.tpl', 54, false),array('modifier', 'number_format', '../Li_Files/LiLiRp_CuadroPrecios.tpl', 48, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para el REPORTE CUADRO DE PRECIOS (formato de Aplesa) -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CUADRO DE PRECIOS</title>
  <?php $this->assign('nGrp', 1); ?>
</head> 

<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => 'txTipoCja','resort' => false)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <table border=1 cellspacing=0 >   
  <tbody>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:10pt;">
      <td class="headerrow" colspan=6><?php echo $this->_tpl_vars['subtitulo']; ?>
 <?php echo $this->_tpl_vars['rec']['EGNombre']; ?>
</td>   
    </tr>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:8pt;">
      <td class="headerrow" colspan=6><?php echo $this->_tpl_vars['subtitulo2']; ?>
</td>   
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'txTipoCja')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <?php $this->assign('nGrp', $this->_tpl_vars['rec']['txTipoCja']); ?>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:8pt;">
      <td class="headerrow" style="background-color: #BDBDBD;">Zona</td>
      <td class="headerrow" style="background-color: #BDBDBD; text-align:left;">Productores <?php echo $this->_tpl_vars['rec']['txTipoCja']; ?>
</td>
      <td class="headerrow" style="background-color: #BDBDBD;">Producto</td>
      <td class="headerrow" style="background-color: #BDBDBD;">Cajas</td>
      <td class="headerrow" style="background-color: #BDBDBD;">Precio</td>
      <td class="headerrow" style="background-color: #BDBDBD;">Total</td>
    </tr>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <tr style="vertical-align:middle; font-size:8pt;">
	<td class="coldata"  nowrap style="width:2cm;"><?php echo $this->_tpl_vars['rec']['txzona']; ?>
</td>
	<td class="coldata" nowrap style="width:2cm;"><?php echo $this->_tpl_vars['rec']['productor']; ?>
</td>
	<td class="coldata" nowrap style="width:2cm;"><?php echo $this->_tpl_vars['rec']['Producto']; ?>
</td>
        <td class="colnum" nowrap style="width:4cm;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CajEmb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
        <td class="colnum" nowrap style="width:3cm;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['pCaj'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td class="colnum " nowrap style="width:3cm;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['tFruta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array('group' => 'txTipoCja')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; ">
	<td colspan="3" style="text-align:left;">TOTAL <?php echo $this->_tpl_vars['rec']['txTipoCja']; ?>
:</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['CajEmb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
<br><br></td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['tFruta']/$this->_tpl_vars['sum']['CajEmb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
<br><br></td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['tFruta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
<br><br></td>
    </tr>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; background-color: #BDBDBD;">
	<td colspan="3" style="text-align:left;">TOTAL GENERAL:</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['CajEmb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['tFruta']/$this->_tpl_vars['sum']['CajEmb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['tFruta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
    </tr>
  </tbody>
  </table>
      
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>