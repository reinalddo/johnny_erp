<?php /* Smarty version 2.6.18, created on 2015-04-13 17:50:21
         compiled from InTrTr_factura.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_factura.tpl', 45, false),array('block', 'report_header', 'InTrTr_factura.tpl', 46, false),array('block', 'report_detail', 'InTrTr_factura.tpl', 98, false),array('block', 'report_footer', 'InTrTr_factura.tpl', 108, false),array('modifier', 'truncate', 'InTrTr_factura.tpl', 66, false),array('modifier', 'number_format', 'InTrTr_factura.tpl', 100, false),array('modifier', 'regex_replace', 'InTrTr_factura.tpl', 128, false),array('modifier', 'date_format', 'InTrTr_factura.tpl', 180, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para Facturas Tamaño A4 -->
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
        .espacioDer{padding-right:20px !important;}
        .espacioIzq{padding-left:20px !important;}
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
<body id="top" style="font-family:'Arial' !important; font-size:9pt; font-weight:bold " onload="window.print();">
  <div id="paginaA4" style="margin-left:0pt; height:25cm">  <!--  17cm -->
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "TIPO,COMPR",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<hr/>-->
    <?php $this->assign('numCol', 7); ?>
    
    
        <div id = "cuerpo" style="height:14.5cm; border:#FFFFFF solid 1px; margin-top:120pt;"> <!--  9cm    padding-left: 70px -->
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 150px; border:#FFFFFF solid 1px; width:800px; height:'8.5cm'" >
        <!--<tr>
            <td colspan=<?php echo $this->_tpl_vars['numCol']; ?>
 style="height:50px;font-size:16px;font-weight:bold;text-align:center;vertical-align:middle;">
                <?php echo $_SESSION['g_empr']; ?>
</td>            
        </tr>-->
        <tr>
	    <td colspan=1 class="resalta" nowrap style="width:100pt"><strong>CLIENTE:</strong></td>
            <td colspan=1 class="" nowrap  style="width:400pt"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RECEP'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 40) : smarty_modifier_truncate($_tmp, 40)); ?>
</td>
            <td colspan=1 class="resalta " nowrap style="width:60pt"><strong>FECHA:</strong></td>
	    <td colspan=1 class="" style="width:95pt"><?php echo $this->_tpl_vars['rec']['FECHA']; ?>
</td>
	    <td colspan=1 style="width:85pt">&nbsp;</td>
        </tr>
        <tr>
	  <td colspan=1 class="resalta" nowrap><strong>DIRECCION:</strong></td>
            <td colspan=1 class="" nowrap><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['direccion'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 40) : smarty_modifier_truncate($_tmp, 40)); ?>
</td>
            <td colspan=1>&nbsp;</td>
        </tr>
        <tr>
	  <td colspan=1 class="resalta" nowrap><strong>CIUDAD:</strong></td>
            <td colspan=1 class=""><?php echo $this->_tpl_vars['rec']['CIU']; ?>
</td>
            <td colspan=2 class=""><?php echo $this->_tpl_vars['rec']['telefono']; ?>
</td>
	    <td colspan=1>&nbsp;</td>
        </tr>
        <tr>
	    <td colspan=1 class="resalta" nowrap><strong>RUC:</strong></td>
            <td colspan=1 class=""><?php echo $this->_tpl_vars['rec']['ruc']; ?>
</td>
            <td colspan=2>&nbsp;</td>
	    <td colspan=1>&nbsp;</td>
        </tr>
        <tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase; padding-left: 30px; height: 70px; ">
	  <td class="headerrow cabTabla resalta" width="30" style="text-align:center">&nbsp;<strong>CANTIDAD</strong></td>
	  <td class="headerrow cabTabla resalta" width="660">&nbsp;<strong>DESCRIPCION</strong></td>
	  <td class="headerrow cabTabla resalta" width="130">&nbsp;<strong>VALOR UNITARIO</strong></td>
	  <td class="headerrow cabTabla resalta" width="170">&nbsp;<strong>TOTAL</strong></td>
	  <td colspan=1>&nbsp;</td>
	</tr>
        
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr><!-- style="border:#000000 solid 1px;">-->
        <td class="colnum der" style="padding: 0px 55px 0px 0px"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CANTI'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>  
	<td nowrap class="coldata espacioIzq"><?php echo $this->_tpl_vars['rec']['ITEM']; ?>
</td>
        <td class="colnum espacioDer der" ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['vunit'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 4) : smarty_modifier_number_format($_tmp, 4)); ?>
</td>        
        <td class="colnum espacioDer der" ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['VALOR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td colspan=1>&nbsp;</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

    <tr><!-- style="border:#000000 solid 1px;">-->
        <td class="colnum der" >&nbsp</td>  
	<td nowrap class="coldata espacioIzq">SEM.: <?php echo $this->_tpl_vars['rec']['REFOP']; ?>
</td>
        <td class="colnum espacioDer der" >&nbsp</td>        
        <td class="colnum espacioDer der" >&nbsp</td>
	<td colspan=1>&nbsp;</td>
    </tr>
  <tr>
                <td class="der bordeIzq bordeDer" >&nbsp</td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
		<td colspan=1>&nbsp;</td>
     </tr>

      	      <tr>
		  <td class="izq bordeDer"></td>
		  <td colspan=1 align="left"  class="coldata espacioIzq"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CONCEP'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/[\n]/", "<br>") : smarty_modifier_regex_replace($_tmp, "/[\n]/", "<br>")); ?>
</td> <!--Reemplaza los enter por salto de lineas-->
	      </tr>
     	
	
    </table>
</div>
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 70px; border:#FFFFFF solid 1px; width:800px; height:'8.5cm'" >
    <tr style="padding-top:20px;">
    <td colspan=2 rowspan=5 class="espacioCol1 resalta" style="vertical-align: top; width:460pt"<strong>SON: </strong><?php echo $this->_tpl_vars['letras']; ?>
</td>
	<td class ="resalta" style="width:80pt; ">&nbsp;</td>
	<td class ="resalta" style="width:110pt">&nbsp;</td>
	<td colspan=1 style="width:150pt" >&nbsp; </td>
	</tr>
	
    <tr>
        <td class ="resalta" style="text-align:right"><strong>SUBTOTAL:</strong></td>
        <td class="colnum der"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['VALOR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td colspan=1>&nbsp;</td>
    </tr>
    <tr>
        <!--<td><strong>IVA:</strong></td>-->
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class ="resalta" style="text-align:right"><strong>IVA:</strong></td>
        <td class="colnum der" style="padding-top; :0px; text-align:right"><?php echo ((is_array($_tmp=$this->_tpl_vars['iva'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
    <tr>
        <td class ="resalta" style="width:150px; text-align:right"><strong>TOTAL:</strong></td>
        <td class="colnum der" style="padding-top:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['valorTot'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
	<!-- <tr style="height: 1.5cm">
        <td style="height: 1.5cm;"> &nbsp</td>
    </tr> -->
	<tr >
        <td colspan=2 align="center">______________________&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;______________________</td>
    </tr>
	<tr style="">
        <td colspan=2 align="center"style="">&nbsp;Elaborado &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Autorizado</td>
		<td colspan=2 align="right" style="font-size:0.8em;"><?php echo $_SESSION['g_user']; ?>
, <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
 &nbsp;/ &nbsp;<?php echo $this->_tpl_vars['rec']['COMPR']; ?>
 </td>
    </tr>

   </table>
   
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</div>
</body>