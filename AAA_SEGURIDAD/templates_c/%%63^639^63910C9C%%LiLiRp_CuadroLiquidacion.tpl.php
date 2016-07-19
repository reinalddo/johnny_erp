<?php /* Smarty version 2.6.18, created on 2011-08-04 12:30:53
         compiled from ../Li_Files/LiLiRp_CuadroLiquidacion.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', '../Li_Files/LiLiRp_CuadroLiquidacion.tpl', 23, false),array('block', 'report_header', '../Li_Files/LiLiRp_CuadroLiquidacion.tpl', 25, false),array('block', 'report_detail', '../Li_Files/LiLiRp_CuadroLiquidacion.tpl', 73, false),array('block', 'report_footer', '../Li_Files/LiLiRp_CuadroLiquidacion.tpl', 133, false),array('modifier', 'number_format', '../Li_Files/LiLiRp_CuadroLiquidacion.tpl', 78, false),array('modifier', 'date_format', '../Li_Files/LiLiRp_CuadroLiquidacion.tpl', 160, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Cuadro de Cuentas por Pagar -->
<!--  FRUTIBONI - Quitar columnas: No Comprobante, Cod. Proveedor, No Retencion, estado, usuario/fecha Dig -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CUADRO DE LIQUIDACION</title>
  
</head> 

<body id="top" style="font-family:'Arial'">
<?php $this->assign('acum', 0); ?>
<?php $this->assign('sal', 0); ?>




<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => 'dmarca','resort' => false)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:12pt;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>
<br>
	<?php echo $this->_tpl_vars['subtitulo2']; ?>

    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>      
    </tfoot>
    
    <tbody>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:10pt;">
      <td class="headerrow" colspan=5></td>
      <td class="headerrow" colspan=10 style="background-color: #F78181;">DESCUENTOS(-)</td>
      <td class="headerrow" colspan=5  style="background-color: #04B45F;">CREDITOS(+)</td>
      <td class="headerrow" colspan=4  style="background-color: #FE9A2E;">LIQUIDACION</td>      
    </tr>
    <tr style="font-weight:bold; text-align:justify; vertical-align:middle; font-size:8pt;">
	<td class="headerrow">COD.</td>
	<td class="headerrow"><strong>NOMBRE</strong></td> 
    	<td class="headerrow"><strong>MARCA<strong></td>
	<td class="headerrow"><strong>CAJAS<strong></td>
	<td class="headerrow"><strong>V. UNIT.<strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>TRANS.</strong></td>
        <td class="headerrow" style="background-color: #F5A9A9;"><strong>EMPAQ.</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>ADM.</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>CXC.</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>DIF.OFIC.</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>RET. FTE.</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>QLTY.</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>SPI.</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>ESTIBA</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>TOTAL</strong></td>
	<td class="headerrow" style="background-color: #A9F5A9;"><strong>BONIF.</strong></td>
	<td class="headerrow" style="background-color: #A9F5A9;"><strong>DEV. GAR.</strong></td>
	<td class="headerrow" style="background-color: #A9F5A9;"><strong>EMPAQ.</strong></td>
	<td class="headerrow" style="background-color: #A9F5A9;"><strong>PR.OFIC.</strong></td>
	<td class="headerrow" style="background-color: #A9F5A9;"><strong>TOTAL</strong></td>
	<td class="headerrow" style="background-color: #FAAC58;"><strong>Valor</strong></td>
	<td class="headerrow" style="background-color: #FAAC58;"><strong>Prom</strong></td>
	<td class="headerrow" style="background-color: #FAAC58;"><strong>ANTICIPO</strong></td>
	<td class="headerrow" style="background-color: #FAAC58;"><strong>VR A PAGAR</strong></td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <tr style="vertical-align:middle; font-size:10pt;">
      <td class="colnum "> <?php echo $this->_tpl_vars['rec']['com_CodReceptor']; ?>
</td>
	<td class="coldata" nowrap><?php echo $this->_tpl_vars['rec']['productor']; ?>
</td>
	<td class="coldata" nowrap><?php echo $this->_tpl_vars['rec']['dmarca']; ?>
</td>
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['cajEmb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0, ",", ".") : smarty_modifier_number_format($_tmp, 0, ",", ".")); ?>
</td>
        <td class="colnum ">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['pCaj'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<!-- DESCUENTOS  -->
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R6'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R7'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R7_G'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R9'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R14'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td> 
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R16'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
  	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R18'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R20'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R21'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
        <td class="colnum " style="text-align:right;background-color: #F5A9A9;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['TDesc'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<!-- BONIFICACIONES  -->
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R3'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
        <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R4'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R1'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<td class="colnum " style="text-align:right;background-color: #A9F5A9;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['TBenf'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<?php $this->assign('PPromLiq', $this->_tpl_vars['rec']['RTotal']/$this->_tpl_vars['rec']['cajEmb']); ?>
	<td class="colnum"  style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['PPromLiq'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R17'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	<td class="colnum " style="text-align:right; background-color: #FAAC58;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RTotalFin'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
	
	<!-- ACUMULADORES PARA TOTALES - SOLO LOS QUE SEAN DE TIPO=5 (LOS DE LA LIQUIDACION,NO DEL DETALLE DE LAS TARJAS) -->
	<?php if ($this->_tpl_vars['rec']['tipo'] == 5): ?>
	  <?php $this->assign('tcajEmb', $this->_tpl_vars['tcajEmb']+$this->_tpl_vars['rec']['cajEmb']); ?> <!-- Cajas embarcadas-->
	  <?php $this->assign('tvcajEmb', $this->_tpl_vars['rec']['cajEmb']*$this->_tpl_vars['rec']['pCaj']); ?> <!-- Valor de las cajas embarcadas-->
	  <?php $this->assign('tpcajEmb', $this->_tpl_vars['tpcajEmb']+$this->_tpl_vars['tvcajEmb']); ?> <!-- Precio promedio cajas embarcadas-->
	  <?php $this->assign('tR6', $this->_tpl_vars['tR6']+$this->_tpl_vars['rec']['R6']); ?>
	  <?php $this->assign('tR7', $this->_tpl_vars['tR7']+$this->_tpl_vars['rec']['R7']); ?>
	  <?php $this->assign('tR7_G', $this->_tpl_vars['tR7_G']+$this->_tpl_vars['rec']['R7_G']); ?>
	  <?php $this->assign('tR9', $this->_tpl_vars['tR9']+$this->_tpl_vars['rec']['R9']); ?>
	  <?php $this->assign('tR14', $this->_tpl_vars['tR14']+$this->_tpl_vars['rec']['R14']); ?>
	  <?php $this->assign('tR16', $this->_tpl_vars['tR16']+$this->_tpl_vars['rec']['R16']); ?>
	  <?php $this->assign('tR17', $this->_tpl_vars['tR17']+$this->_tpl_vars['rec']['R17']); ?>
	  <?php $this->assign('tR18', $this->_tpl_vars['tR18']+$this->_tpl_vars['rec']['R18']); ?>
	  <?php $this->assign('tR20', $this->_tpl_vars['tR20']+$this->_tpl_vars['rec']['R20']); ?>
	  <?php $this->assign('tR21', $this->_tpl_vars['tR21']+$this->_tpl_vars['rec']['R21']); ?>
	  <?php $this->assign('tTDesc', $this->_tpl_vars['tTDesc']+$this->_tpl_vars['rec']['TDesc']); ?>
	  <?php $this->assign('tR2', $this->_tpl_vars['tR2']+$this->_tpl_vars['rec']['R2']); ?>
	  <?php $this->assign('tR3', $this->_tpl_vars['tR3']+$this->_tpl_vars['rec']['R3']); ?>
	  <?php $this->assign('tR4', $this->_tpl_vars['tR4']+$this->_tpl_vars['rec']['R4']); ?>
	  <?php $this->assign('tR1', $this->_tpl_vars['tR1']+$this->_tpl_vars['rec']['R1']); ?>
	  <?php $this->assign('tTBenf', $this->_tpl_vars['tTBenf']+$this->_tpl_vars['rec']['TBenf']); ?>
	  <?php $this->assign('tRTotal', $this->_tpl_vars['tRTotal']+$this->_tpl_vars['rec']['RTotal']); ?>
	  <?php $this->assign('tPPromLiq', $this->_tpl_vars['tPPromLiq']+$this->_tpl_vars['PPromLiq']); ?>
	  <?php $this->assign('tvCajLiq', $this->_tpl_vars['rec']['cajEmb']*$this->_tpl_vars['PPromLiq']); ?> <!-- Precio promedio de la liquidacion X Cajas embarcadas -->
	  <?php $this->assign('tpcajLiq', $this->_tpl_vars['tpcajLiq']+$this->_tpl_vars['tvCajLiq']); ?> <!-- Precio promedio cajas liquidadas-->
	  <?php $this->assign('tRTotalFin', $this->_tpl_vars['tRTotalFin']+$this->_tpl_vars['rec']['RTotalFin']); ?>
	<?php endif; ?>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; background-color: #BDBDBD;">
      <td colspan=3">TOTALES:</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tcajEmb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0, ",", ".") : smarty_modifier_number_format($_tmp, 0, ",", ".")); ?>
</td>
       <?php $this->assign('tpPcajEmb', $this->_tpl_vars['tpcajEmb']/$this->_tpl_vars['tcajEmb']); ?>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tpPcajEmb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tR6'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tR7'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tR7_G'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tR9'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tR14'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tR16'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tR18'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tR20'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tR21'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tTDesc'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tR2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tR3'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tR4'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tR1'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tTBenf'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tRTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
      <?php $this->assign('tpPcajLiq', $this->_tpl_vars['tpcajLiq']/$this->_tpl_vars['tcajEmb']); ?>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tpPcajLiq'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tR17'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tRTotalFin'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td>
    
  <tr><td colspan="24" style="text-align:left"> <?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
 </td></tr>
  </tbody>
  </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>