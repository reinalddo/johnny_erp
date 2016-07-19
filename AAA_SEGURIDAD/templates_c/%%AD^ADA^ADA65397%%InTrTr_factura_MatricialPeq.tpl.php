<?php /* Smarty version 2.6.18, created on 2011-12-14 15:54:22
         compiled from InTrTr_factura_MatricialPeq.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_factura_MatricialPeq.tpl', 46, false),array('block', 'report_header', 'InTrTr_factura_MatricialPeq.tpl', 47, false),array('block', 'report_detail', 'InTrTr_factura_MatricialPeq.tpl', 102, false),array('block', 'report_footer', 'InTrTr_factura_MatricialPeq.tpl', 112, false),array('modifier', 'truncate', 'InTrTr_factura_MatricialPeq.tpl', 74, false),array('modifier', 'number_format', 'InTrTr_factura_MatricialPeq.tpl', 104, false),array('modifier', 'regex_replace', 'InTrTr_factura_MatricialPeq.tpl', 130, false),array('modifier', 'date_format', 'InTrTr_factura_MatricialPeq.tpl', 189, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Erika Suarez" />
  <?php echo '
  <style type="text/css">
        .padding10{padding:10px;}
        .empresa{height:50px;font-size:20px;font-weight:bold;text-align:center;vertical-align:middle;}
        .titulo{font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;}
        .parrafo{text-align:justify; vertical-align:middle; text-transform:uppercase;}
        .bordeCompleto {border: #FFFFFF solid 1px;}
        .bordeDer {border-right: #FFFFFF solid 1px;}
        .bordeIzq {border-left: #FFFFFF solid 1px;}
        .bordeSup {border-top: #FFFFFF solid 1px;}
        .bordeInf {border-bottom: #FFFFFF solid 1px;}
        .izq{text-align:left;}
        .der{text-align:right;}
        .cen{text-align:center;}
        .espacioCol1{padding-left:70px;}
        .cabTabla{height:40px;}
        .espacioDer{padding-right:24px !important;}
        .espacioIzq{padding-left:10px !important;}
		.resalta {font-weight:900; font-style:italic}
        @media print {
            body { font-size: 9pt; }

          }
	@page factura {size:18cm 22cm;}
	pagina {page: factura;}

    </style>
  '; ?>

  
  <!--<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
    <title>Factura Comercial</title>
  
</head>
<body id="top" style="font-family:'Arial' !important; font-size:9pt; font-weight:bold " onload="window.print()">
  <div id="pagina" style="margin-left:0pt; height:17cm">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "TIPO,COMPR",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<hr/>-->
    <?php $this->assign('numCol', 7); ?>
    
    
        <div id = "cuerpo" style="height:12.5cm; border:#FFFFFF solid 1px; margin-top:108pt;">
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'8.5cm'" >
        <!--<tr>
            <td colspan=<?php echo $this->_tpl_vars['numCol']; ?>
 style="height:50px;font-size:16px;font-weight:bold;text-align:center;vertical-align:middle;">
                <?php echo $_SESSION['g_empr']; ?>
</td>            
        </tr>-->
	<tr>
	    <td colspan=1 class="" nowrap  style="width:370pt">&nbsp;</td>
	    <td colspan=1 class="" style="width:200pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['rec']['FECHA']; ?>
</td>
	    <td colspan=1 class="" >&nbsp;</td>
        </tr>
    </table>
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'8.5cm'" >
        <tr>
	    <td colspan=1 class="resalta" nowrap style="width:80pt;"><strong></strong></td>
            <td colspan=1 class="" nowrap  style="width:470pt"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RECEP'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 40) : smarty_modifier_truncate($_tmp, 40)); ?>
</td>
	    <td colspan=1>&nbsp;</td>
        </tr>
    </table>
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'8.5cm'" >
        <tr>
	    <td colspan=1 class="resalta" nowrap style="width:80pt;"><strong></strong></td>
            <td colspan=1 class="" nowrap style="width:300pt;" ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['direccion'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 40) : smarty_modifier_truncate($_tmp, 40)); ?>
</td>
	    <td colspan=1 class="resalta" nowrap style="width:100pt"><strong></strong></td>
            <td colspan=1 class="" style="vertical-align:middle;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['rec']['telefono']; ?>
</td>
        </tr>
        <tr>
	    <td colspan=1 class="resalta" nowrap style="width:80pt;"><strong></strong></td>
            <td colspan=1 class="" style="width:150pt;"><?php echo $this->_tpl_vars['rec']['ruc']; ?>
</td>
            <td colspan=1 class="resalta" nowrap style="width:100pt"><strong></strong></td>
	    <td colspan=1>&nbsp;</td>
        </tr>
	</table>
	<table border=0  cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'1cm'" >
        <tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:80pt;">&nbsp;<!--<strong>CANTIDAD</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:370pt;">&nbsp;<!--<strong>DESCRIPCION</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:90pt;">&nbsp;<!--<strong>VALOR UNITARIO</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:110pt;">&nbsp;<!--<strong>TOTAL</strong>--></td>
	</tr>
        
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr><!-- style="border:#000000 solid 1px;">-->
        <td class="colnum der" style="text-align:right; padding: 0px 30px 0px 0px; width:80pt;" ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CANTI'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>  
	<td nowrap class="coldata espacioIzq" style="text-align:left; width:370pt;"><?php echo $this->_tpl_vars['rec']['ITEM']; ?>
</td>
        <td class="colnum espacioDer der" style="text-align:right; width:90pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['vunit'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 4) : smarty_modifier_number_format($_tmp, 4)); ?>
</td>        
        <td class="colnum espacioDer der" style="text-align:right; width:110pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['VALOR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<!-- <td colspan=1>&nbsp;</td> -->
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!-- <tr>
        <td class="colnum der" style="text-align:right; padding: 0px 25px 0px 0px; width:80pt;" >&nbsp;</td>  
	<td nowrap class="coldata espacioIzq" style="text-align:left; width:370pt;"></td>
        <td class="colnum espacioDer der" style="text-align:right; width:90pt;">&nbsp;</td>        
        <td class="colnum espacioDer der" style="text-align:right; width:110pt;">&nbsp;</td>
	
    </tr>
     <tr>
                <td class="der bordeIzq bordeDer" >&nbsp</td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
    </tr> -->

      	      <tr>
		  <td class="izq bordeDer"></td>
		  <td class="coldata espacioIzq" style="text-align:left; width:370pt;" nowrap><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CONCEP'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/[\n]/", "<br>") : smarty_modifier_regex_replace($_tmp, "/[\n]/", "<br>")); ?>
</td> <!--Reemplaza los enter por salto de lineas-->
		  <td class="izq bordeDer"></td>
		  <td class="izq bordeDer"></td>
	      </tr>
     	
	
    </table>
</div>
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'8.5cm'" >
    <tr style="padding-top:20px;">
        <td colspan=2 rowspan=9 class="espacioCol1 resalta" style=" vertical-align: top; width:400pt">&nbsp;&nbsp;<?php echo $this->_tpl_vars['letras']; ?>
</td>
	<td class ="resalta" style="width:90pt">&nbsp;</td>
	<td class ="resalta" style="width:110pt">&nbsp;</td>
    </tr>
    <tr>
        <td class ="resalta" style="width:90pt"><strong></strong></td>
        <td class="colnum espacioDer der" style="width:110pt"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['VALOR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
    <tr>
        <td><!--<strong>IVA:</strong>--></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class ="resalta"><strong>&nbsp;</strong></td>
        <td class="colnum espacioDer der" style="padding-top:0px;">&nbsp;</td>
    </tr>
    <tr>
        <td class ="resalta"><!--<strong>IVA:</strong>--></td>
        <td class="colnum espacioDer der" style="padding-top:0px;">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['iva'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
    <tr>
        <td class ="resalta" style="width:150px"><!--<strong>TOTAL :</strong>--></td>
        <td class="colnum espacioDer der" style="padding-top:5px;">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['valorTot'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
	<tr style="height: 1.5cm">
        <td style="height: 1.5cm;"> &nbsp</td>
    </tr>
	
    <!--	
    <tr >
        <td colspan=2>_______________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________</td>
    </tr>
	<tr style="">
        <td colspan=3 style="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Elaborado &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Autorizado</td>
    </tr>
    <tr style="font-size:0.8em; text-align:left;">
	<td colspan=4><?php echo $_SESSION['g_user']; ?>
, <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
 &nbsp;/ &nbsp;<?php echo $this->_tpl_vars['rec']['COMPR']; ?>
 </td>
    </tr>
    -->
    
   </table>
   
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</div>
</body>