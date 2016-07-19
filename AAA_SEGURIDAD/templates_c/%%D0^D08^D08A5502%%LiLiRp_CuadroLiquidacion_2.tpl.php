<?php /* Smarty version 2.6.18, created on 2015-06-17 16:51:10
         compiled from ../Li_Files/LiLiRp_CuadroLiquidacion_2.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', '../Li_Files/LiLiRp_CuadroLiquidacion_2.tpl', 23, false),array('block', 'report_header', '../Li_Files/LiLiRp_CuadroLiquidacion_2.tpl', 25, false),array('block', 'report_detail', '../Li_Files/LiLiRp_CuadroLiquidacion_2.tpl', 71, false),array('block', 'report_footer', '../Li_Files/LiLiRp_CuadroLiquidacion_2.tpl', 118, false),array('modifier', 'number_format', '../Li_Files/LiLiRp_CuadroLiquidacion_2.tpl', 76, false),array('modifier', 'date_format', '../Li_Files/LiLiRp_CuadroLiquidacion_2.tpl', 147, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Cuadro de Cuentas por Pagar -->
<!--  FRUTIBONI - Quitar columnas: No Comprobante, Cod. Proveedor, No Retencion, estado, usuario/fecha Dig -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="smart" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CUADRO DE LIQUIDACION</title>
  
</head> 

<body id="top" style="font-family:'Arial'">
<?php $this->assign('acum', 0); ?>
<?php $this->assign('sal', 0); ?>




<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => false)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>      
    </tfoot>
    
    <tbody>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:6pt;">
      <td class="headerrow" colspan=18 style="background-color: #BDBDBD;"><?php echo $this->_tpl_vars['subtitulo']; ?>
 <?php echo $this->_tpl_vars['rec']['EGNombre']; ?>
</td>   
    </tr>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:6pt;">
      <td class="headerrow" colspan=18 style="background-color: #BDBDBD;"><?php echo $this->_tpl_vars['subtitulo2']; ?>
</td>   
    </tr>
    
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:6pt;">
      <td class="headerrow" colspan=2 style="background-color: #CEF6E3;"></td>
      <td class="headerrow" colspan=5 style="background-color: #81F781;">INGRESOS</td>
      <td class="headerrow" colspan=10 style="background-color: #F78181;">DESCUENTOS</td>
      <td class="headerrow" colspan=1 style="background-color: #FE9A2E;"></td> 
    </tr>
    <tr style="font-weight:bold; text-align:justify; vertical-align:middle; font-size:6pt;"> 
	<td class="headerrow" style="background-color: #CEF6E3;">Zona</td>
	<td class="headerrow" style="background-color: #CEF6E3;">Productor</td> 
	<td class="headerrow" style="background-color: #CEF6E3;">#LIQ</td> 
    	<td class="headerrow" style="background-color: #81F781;">Embarcadas</td>
	<td class="headerrow" style="background-color: #81F781;">Precio Real</td>
	<td class="headerrow" style="background-color: #81F781;">Total Fruta</td>
	<td class="headerrow" style="background-color: #81F781;">Empaque Pagado</td>
	<td class="headerrow" style="background-color: #81F781;">Total Ingresos</td>
	
	<td class="headerrow" style="background-color: #F5A9A9;">Rte. 1.00%</td>
	<td class="headerrow" style="background-color: #F5A9A9;">Rte. 1.25%</td>
	<td class="headerrow" style="background-color: #F5A9A9;">Rte. 1.50%</td>
	<td class="headerrow" style="background-color: #F5A9A9;">Rte. 2.00%</td>
        <td class="headerrow" style="background-color: #F5A9A9;">Empaque Cobrado</td>
	<td class="headerrow" style="background-color: #F5A9A9;">Gasto Administrativo</td>
	<td class="headerrow" style="background-color: #F5A9A9;">Anticipo</td>
	<td class="headerrow" style="background-color: #F5A9A9;">Evaluadores TCI</td>
	<td class="headerrow" style="background-color: #F5A9A9;">Otros Descuentos</td>    
	<td class="headerrow" style="background-color: #F5A9A9;">Total Descuentos</td>
	<td class="headerrow" style="background-color: #FAAC58;">NETO A PAGAR</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <tr style="vertical-align:middle; font-size:6pt;">
	<td class="coldata" nowrap><?php if ($this->_tpl_vars['rec']['tipo'] == 1): ?><?php echo $this->_tpl_vars['rec']['txzona']; ?>
<?php endif; ?></td>
	<td class="coldata" nowrap><?php if ($this->_tpl_vars['rec']['tipo'] == 5): ?><strong><?php echo $this->_tpl_vars['rec']['productor']; ?>
</strong><?php else: ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['rec']['productor']; ?>
<?php endif; ?></td>
	<td class="coldata" nowrap><?php if ($this->_tpl_vars['rec']['tipo'] == 5): ?><strong><?php echo $this->_tpl_vars['rec']['liq']; ?>
</strong><?php else: ?> &nbsp;<?php echo $this->_tpl_vars['rec']['liq']; ?>
<?php endif; ?></td>
        <td class="colnum "><?php if ($this->_tpl_vars['rec']['tipo'] == 5): ?><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['cajEmb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0, ".", ",") : smarty_modifier_number_format($_tmp, 0, ".", ",")); ?>
</strong><?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['cajEmb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0, ".", ",") : smarty_modifier_number_format($_tmp, 0, ".", ",")); ?>
<?php endif; ?></td>
        <td class="colnum "><?php if ($this->_tpl_vars['rec']['tipo'] == 5): ?><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['pCaj'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</strong><?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['pCaj'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
<?php endif; ?></td>
	<td class="colnum "><?php if ($this->_tpl_vars['rec']['tipo'] == 5): ?><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['tFruta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</strong><?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['tFruta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
<?php endif; ?></td>
	<td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R4'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
        	<td class="colnum "><?php if ($this->_tpl_vars['rec']['tipo'] == 5): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['tFruta']+$this->_tpl_vars['rec']['R4'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
<?php endif; ?></td>
	
	<!-- DESCUENTOS  -->
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R16'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R23'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R24'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R22'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R7'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R21'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R17'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
  	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R18'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['R14'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
        <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['TDesc'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<!-- BONIFICACIONES  -->
			<!-- TOTALES DE CAJAS EMBARCADAS, PRECIOS -->
	<?php if ($this->_tpl_vars['rec']['tipo'] == 5): ?>
	  <?php $this->assign('vPagar', $this->_tpl_vars['rec']['tFruta']+$this->_tpl_vars['rec']['R4']+$this->_tpl_vars['rec']['TDesc']); ?>
	  <?php $this->assign('TotvPagar', $this->_tpl_vars['TotvPagar']+$this->_tpl_vars['vPagar']); ?>
	<?php endif; ?>
	
	<td class="colnum " style="text-align:right;"><?php if ($this->_tpl_vars['rec']['tipo'] == 5): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['vPagar'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
<?php endif; ?></td>
  
  
  
	<!-- TOTALES DE CAJAS EMBARCADAS, PRECIOS -->
	<?php if ($this->_tpl_vars['rec']['tipo'] == 5): ?>
	  <?php $this->assign('TotcajEmb', $this->_tpl_vars['TotcajEmb']+$this->_tpl_vars['rec']['cajEmb']); ?>
	  <?php $this->assign('TottFruta', $this->_tpl_vars['TottFruta']+$this->_tpl_vars['rec']['tFruta']); ?>
	<?php endif; ?>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; background-color: #BDBDBD;">
      <td colspan=2">TOTALES:</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['TotcajEmb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0, ".", ",") : smarty_modifier_number_format($_tmp, 0, ".", ",")); ?>
</td>
    
    
       <?php $this->assign('tpPcajEmb', $this->_tpl_vars['TottFruta']/$this->_tpl_vars['TotcajEmb']); ?>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tpPcajEmb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
    
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['TottFruta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['R4'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
              <td><?php echo ((is_array($_tmp=$this->_tpl_vars['TottFruta']+$this->_tpl_vars['sum']['R4'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
    
    
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['R16'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['R23'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['R24'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['R22'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['R7'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['R21'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['R17'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['R18'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['R14'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['TDesc'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['TotvPagar'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
       
       
    
  <tr><td colspan="17" style="text-align:left"> <?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
 </td></tr>
  </tbody>
  </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>