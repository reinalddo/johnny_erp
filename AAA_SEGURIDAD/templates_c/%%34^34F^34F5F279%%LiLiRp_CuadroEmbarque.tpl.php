<?php /* Smarty version 2.6.18, created on 2012-06-01 18:39:07
         compiled from ../Li_Files/LiLiRp_CuadroEmbarque.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', '../Li_Files/LiLiRp_CuadroEmbarque.tpl', 20, false),array('block', 'report_header', '../Li_Files/LiLiRp_CuadroEmbarque.tpl', 22, false),array('block', 'report_detail', '../Li_Files/LiLiRp_CuadroEmbarque.tpl', 63, false),array('block', 'report_footer', '../Li_Files/LiLiRp_CuadroEmbarque.tpl', 82, false),array('modifier', 'number_format', '../Li_Files/LiLiRp_CuadroEmbarque.tpl', 70, false),array('modifier', 'date_format', '../Li_Files/LiLiRp_CuadroEmbarque.tpl', 185, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para el CUADRO DE EMBARQUE (formato de Aplesa) -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CUADRO DE EMBARQUE</title>
  <?php $this->assign('nGrp', 1); ?>
</head> 

<body id="top" style="font-family:'Arial'">
<?php $this->assign('tExp', 0); ?>
<?php $this->assign('tLoc', 0); ?>


<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => 'venta','resort' => false)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>      
    </tfoot>
    
  <tbody>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:12pt;">
      <td class="headerrow" colspan=13><?php echo $this->_tpl_vars['subtitulo']; ?>
 <?php echo $this->_tpl_vars['rec']['EGNombre']; ?>
</td>   
    </tr>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:8pt;">
      <td class="headerrow" colspan=13><?php echo $this->_tpl_vars['subtitulo2']; ?>
</td>   
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'venta')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <?php $this->assign('nGrp', $this->_tpl_vars['rec']['venta']); ?>
    <tr style="font-weight:bold; text-align: left; vertical-align:middle; font-size:12pt; background-color: #BDBDBD;">
      <td class="headerrow" colspan=13><?php echo $this->_tpl_vars['rec']['venta']; ?>
</td>
      
    </tr>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:10pt;">
      <td class="headerrow" style="background-color: #FE9A2E;">WK</td>
      <td class="headerrow" style="background-color: #FE9A2E;">ID EMBARQUE</td>
      <td class="headerrow" style="background-color: #FE9A2E;">CLIENTE</td>
      <td class="headerrow" style="background-color: #FE9A2E;">VAPOR</td>
      <td class="headerrow" style="background-color: #FE9A2E;">TIPO</td>
      <td class="headerrow" style="background-color: #FE9A2E;">CAJAS</td>
      <td class="headerrow" style="background-color: #FE9A2E;">DESTINO</td>
      <td class="headerrow" style="background-color: #FE9A2E;">FACTURACION</td>
      <td class="headerrow" style="background-color: #FE9A2E;">PRECIO FOB</td>
      <td class="headerrow" style="background-color: #FE9A2E;">PRECIO CIF</td>
      <td class="headerrow" style="background-color: #FE9A2E;">COSTO FOB/CIF</td>
      <td class="headerrow" style="background-color: #FE9A2E;">FRUTA</td>
      <td class="headerrow" style="background-color: #FE9A2E;">TOTAL</td>
    </tr>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <tr style="vertical-align:middle; font-size:10pt;">
	<td class="colnum"  nowrap style="width:2cm; text-align:center"><?php echo $this->_tpl_vars['rec']['tac_Semana']; ?>
</td>
	<td class="colnum"  nowrap style="width:2cm; text-align:center"><?php echo $this->_tpl_vars['rec']['emb_RefOperativa']; ?>
</td>
	<td class="coldata" nowrap style="width:2cm;"><?php echo $this->_tpl_vars['rec']['cliente']; ?>
</td>
        <td class="coldata" nowrap style="width:4cm;"><?php echo $this->_tpl_vars['rec']['buq_Descripcion']; ?>
</td>
        <td class="coldata" nowrap style="width:3cm;"><?php echo $this->_tpl_vars['rec']['paletizado']; ?>
</td>
	<td class="colnum " nowrap style="width:3cm;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['Embarcadas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
        <td class="coldata" nowrap style="width:4cm;"><?php echo $this->_tpl_vars['rec']['pai_Descripcion']; ?>
</td>
	<td class="coldata" nowrap style="width:3cm;"><?php echo $this->_tpl_vars['rec']['FACTURA_EMB']; ?>
</td>
	<td class="colnum " nowrap style="width:3cm;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PRECIO_FOB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td class="colnum " nowrap style="width:3cm;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PRECIO_CIF'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td class="colnum " nowrap style="width:3cm;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['COSTO_FOB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td class="colnum " nowrap style="width:3cm;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PRECIO_FRUTA'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td class="colnum " nowrap style="width:3cm;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['TOTAL_EMBARQUE'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array('group' => 'venta')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; ">
	<td colspan="5">TOTAL <?php echo $this->_tpl_vars['rec']['venta']; ?>
:</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['Embarcadas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
<br><br></td>
	<td colspan="5">PROMEDIO VENTA <?php echo $this->_tpl_vars['rec']['venta']; ?>
 ==></td>
	<td ><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['TOTAL_EMBARQUE']/$this->_tpl_vars['sum']['Embarcadas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td ><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['TOTAL_EMBARQUE'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
    
	
	<?php if ($this->_tpl_vars['rec']['venta'] == 'VENTAS AL EXTERIOR'): ?>
	  <?php $this->assign('tExp', $this->_tpl_vars['sum']['Embarcadas']); ?>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['rec']['venta'] == 'VENTAS LOCALES'): ?>
	  <?php $this->assign('tLoc', $this->_tpl_vars['sum']['Embarcadas']); ?>
	<?php endif; ?>
	
    </tr>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; background-color: #BDBDBD;">
	<td colspan="5">TOTAL GENERAL:</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['Embarcadas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td colspan="5">PROMEDIO VENTA TOTAL ==></td>
	<td ><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['TOTAL_EMBARQUE']/$this->_tpl_vars['sum']['Embarcadas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	<td ><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['TOTAL_EMBARQUE'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
    </tr>
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; ">
	<td colspan="13"></td>
    </tr>
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; ">
	<td colspan="13"></td>
    </tr>
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; ">
	<td colspan="13"></td>
    </tr>
  </tbody>
  </table>
   
   
   <TABLE>
    <TR>
      <TD>
	    <!--  TABLA DE RESUMEN DE LO EMBARCADO -->
	    <table>
	     <tr style="font-weight:bold;vertical-align:middle; text-align:right; background-color: #FE9A2E;">
		 <td colspan="2">RESUMEN DE EMBARQUE WK#<?php echo $this->_tpl_vars['rec']['tac_Semana']; ?>
</td>
	    </tr>
	    <tr style="vertical-align:middle; text-align:right; ">
		 <td style="width:7cm;">EXPORTACION</td>
		 <td style="width:3cm;"><?php if ($this->_tpl_vars['tExp'] == 0): ?> 0.00 <?php else: ?> <?php echo ((is_array($_tmp=$this->_tpl_vars['tExp'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
 <?php endif; ?></td>
	    </tr>
	    <tr style="vertical-align:middle; text-align:right; ">
		 <td >VENTA LOCAL</td>
		 <td><?php if ($this->_tpl_vars['tLoc'] == 0): ?> 0.00 <?php else: ?> <?php echo ((is_array($_tmp=$this->_tpl_vars['tLoc'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
 <?php endif; ?></td>
	    </tr>
	    <tr style="font-weight:bold; vertical-align:middle; text-align:right; ">
		 <td >TOTAL EXPORTACION Y VENTA LOCAL</td>
		 <td><?php if ($this->_tpl_vars['tLoc']+$this->_tpl_vars['tExp'] == 0): ?> 0.00 <?php else: ?> <?php echo ((is_array($_tmp=$this->_tpl_vars['tLoc']+$this->_tpl_vars['tExp'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
 <?php endif; ?></td>
	    </tr>
	    <tr style="vertical-align:middle; text-align:right; ">
		 <td >TOTAL LIQUIDADO A PRODUCTORES</td>
		 <td><?php if ($this->_tpl_vars['LiqProduc'] == 0): ?> 0.00 <?php else: ?> <?php echo ((is_array($_tmp=$this->_tpl_vars['LiqProduc'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
 <?php endif; ?></td>
	    </tr>
	    <tr style="font-weight:bold; vertical-align:middle; text-align:right; ">
		 <td >DIFERENCIA</td>
		 <td><?php if ($this->_tpl_vars['tLoc']+$this->_tpl_vars['tExp']-$this->_tpl_vars['LiqProduc'] == 0): ?> 0.00 <?php else: ?> <?php echo ((is_array($_tmp=$this->_tpl_vars['tLoc']+$this->_tpl_vars['tExp']-$this->_tpl_vars['LiqProduc'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
 <?php endif; ?></td>
	    </tr>
	  </table>
   </TD>
   <TD style="width:50%"> </TD>
   <TD>
   
	  <!--  TABLA DE RESUMEN DE LA RENTABILIDAD -->
	   <table>
	       <tr style="vertical-align:middle; text-align:right; ">
		    <td style="width:7cm;">PROMEDIO DE VENTA TOTAL</td>
		    <?php $this->assign('promEmb', $this->_tpl_vars['sum']['TOTAL_EMBARQUE']/$this->_tpl_vars['sum']['Embarcadas']); ?>
		    <td style="width:3cm;"><?php echo ((is_array($_tmp=$this->_tpl_vars['promEmb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	       </tr>
	       <tr style="vertical-align:middle; text-align:right; ">
		    <td >PRECIO PROMEDIO DEL TOTAL GENERAL</td>
		    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['PromGeneral'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	       </tr>
	       <tr style="font-weight:bold; vertical-align:middle; text-align:right; ">
		    <td >RENTABILIDAD POR CAJA</td>
		    <?php $this->assign('rentabilidad', $this->_tpl_vars['promEmb']-$this->_tpl_vars['PromGeneral']); ?>
		    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['rentabilidad'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	       </tr>
	       <tr style="vertical-align:middle; text-align:right; ">
		    <td >EXPORTACION + VENTA LOCAL</td>
		    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['Embarcadas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	       </tr>
	       <tr style="font-weight:bold; vertical-align:middle; text-align:right; ">
		    <td >UTILIDAD BRUTA WK#<?php echo $this->_tpl_vars['rec']['tac_Semana']; ?>
</td>
		    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['Embarcadas']*$this->_tpl_vars['rentabilidad'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : smarty_modifier_number_format($_tmp, 2, ".", ",")); ?>
</td>
	       </tr>
	    </table>
	  
    </TD>
   </TR>
   <TR>
      <TD colspan="3"><?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
 </TD>
   </TR>
    </TABLE>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>