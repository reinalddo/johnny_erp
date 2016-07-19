<?php /* Smarty version 2.6.18, created on 2010-09-15 13:20:52
         compiled from CoTrTr_resumengasto.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_resumengasto.tpl', 33, false),array('block', 'report_header', 'CoTrTr_resumengasto.tpl', 34, false),array('block', 'report_detail', 'CoTrTr_resumengasto.tpl', 64, false),array('block', 'report_footer', 'CoTrTr_resumengasto.tpl', 79, false),array('modifier', 'date_format', 'CoTrTr_resumengasto.tpl', 38, false),array('modifier', 'upper', 'CoTrTr_resumengasto.tpl', 70, false),array('modifier', 'number_format', 'CoTrTr_resumengasto.tpl', 75, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  <?php echo '
  <style type="text/css">
        .padding10{padding:10px;}
        .empresa{height:50px;font-size:20px;font-weight:bold;text-align:center;vertical-align:middle;}
        .titulo{font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;}
        .parrafo{text-align:justify; vertical-align:middle; text-transform:uppercase;}
        .bordeCompleto {border: #000000 solid 1px;}
        .bordeDer {border-right: #000000 solid 1px;}
        .bordeIzq {border-left: #000000 solid 1px;}
        .bordeSup {border-top: #000000 solid 1px;}
        .bordeInf {border-bottom: #000000 solid 1px;}
        .izq{text-align:left;}
        .der{text-align:right;}
    </style>
  '; ?>

  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
    <title>Resumen de Gastos</title>
    
    
  
</head>
<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "ANIO, MES, TIPO, AREA, CENTRO",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    
    
    <div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>
    </br>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br>
        RESUMEN DE GASTOS<br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>

    </p>
    </div>
    <table border=1 cellspacing=0 style="border:#000000 solid 1px;">
        
        <tr class="titulo bordeCompleto">
            <td class=" bordeCompleto"><strong>Empresa</strong></td>
	  <td class=" bordeCompleto"><strong>Año</strong></td>
	  <td class=" bordeCompleto"><strong>Mes</strong></td>
	  <td class=" bordeCompleto"><strong>Tipo</strong></td>
	  <td class=" bordeCompleto"><strong>Area</strong></td>
	  <td class=" bordeCompleto"><strong>Centro</strong></td>
	  <td class=" bordeCompleto"><strong>Clase</strong></td>
	  <td class=" bordeCompleto"><strong>Cod.</strong></td>
          <td class=" bordeCompleto"><strong>Rubro</strong></td>
          <td class=" bordeCompleto"><strong>Valor</strong></td>
	</tr>
        
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr><!-- style="border:#000000 solid 1px;">headerrow-->
        <td class="izq bordeIzq bordeDer" ><?php echo $_SESSION['g_empr']; ?>
</td>  
        <td class="izq bordeIzq bordeDer" ><?php echo $this->_tpl_vars['rec']['ANIO']; ?>
</td>  
	<td nowrap class="izq bordeDer"><?php echo $this->_tpl_vars['rec']['MES']; ?>
</td>
        <td class="izq bordeDer"><?php echo $this->_tpl_vars['rec']['TIPO']; ?>
</td>        
        <td class="izq bordeDer"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['AREA'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
        <td class="izq bordeDer"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CENTRO'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
        <td class="izq bordeDer"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CLASE'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
        <td class="izq bordeDer"><?php echo $this->_tpl_vars['rec']['cod']; ?>
</td>
	<td class="izq bordeDer"><?php echo $this->_tpl_vars['rec']['RUBRO']; ?>
</td>
	<td class="der bordeDer"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['VALOR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
        
    </tr>
        </table>
    </br>
    
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>