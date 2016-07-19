<?php /* Smarty version 2.6.18, created on 2015-04-13 17:55:55
         compiled from InTrTr_facturaBalsh.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_facturaBalsh.tpl', 43, false),array('block', 'report_header', 'InTrTr_facturaBalsh.tpl', 44, false),array('block', 'report_detail', 'InTrTr_facturaBalsh.tpl', 87, false),array('block', 'report_footer', 'InTrTr_facturaBalsh.tpl', 98, false),array('modifier', 'truncate', 'InTrTr_facturaBalsh.tpl', 52, false),array('modifier', 'number_format', 'InTrTr_facturaBalsh.tpl', 90, false),array('modifier', 'regex_replace', 'InTrTr_facturaBalsh.tpl', 101, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla de Impresion de Factura para Baloschi
  @rev esl  28/03/2012  AGREGAR CALCULOS DEL IVA DEPENDIENDO DE act_IvaFlag , separar el valor de la factura en base 0 y base iva
-->
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
        .cabTabla{height:30px;}
        .espacioDer{padding-right:20px !important;}
        .espacioIzq{padding-left:20px !important;}
		.resalta {font-weight:900; font-style:italic}
        @media print {
            body { font-size: 9pt; }

          }
	@page factura {size:21cm 28cm;}
	pagina {page: factura;}

    </style>
  '; ?>

  
    <title>Factura Comercial</title>
  
</head>
<body id="top" style="font-family:sans-serif !important; font-size:9pt; font-weight:bold " onload="window.print();">
  <div id="pagina" style="margin-left:0pt; height:23cm"> 
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "TIPO,COMPR",'resort' => false)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<hr/>-->
    <?php $this->assign('numCol', 7); ?>
 			 <!--  18.5cm -->   			
    <div id = "cuerpo" style="height:18cm; border:#FFFFFF solid 1px; margin-top:111pt;">
    <table border=0 cellpadding=2pt; style="padding-left: 50pt; border:#FFFFFF solid 1px;" >
        <tr>
	 <td style="width:110pt;"><!--<strong>CLIENTE:</strong>--></td>
               <td style="width:500pt;" nowrap><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RECEP'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 80) : smarty_modifier_truncate($_tmp, 80)); ?>
</td>	 
	 <!--<td><?php echo $this->_tpl_vars['rec']['telefono']; ?>
</td>-->
	 <td style="width:80pt;"><!--<strong>GUIA REMISION:</strong>--></td>
	 <!--<td style="width:100pt;">&nbsp;</td>-->
        </tr>
        <tr>
	 <td colspan=1 class="resalta"><!--<strong>REFERENCIA:</strong>--></td>
                <td colspan=3>&nbsp;</td>
        </tr>
        <tr>
	      <td><!--<strong>DIRECCION:</strong>--></td>
               <td colspan="3" nowrap><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['direccion'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 40) : smarty_modifier_truncate($_tmp, 40)); ?>
</td>
               <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['rec']['telefono']; ?>
</td>
               <!--<td><strong>TELEFONO:</strong></td>-->
	 <!--<td><?php echo $this->_tpl_vars['rec']['telefono']; ?>
</td>-->
        </tr>
        <tr>
	<td><!-- <strong>FECHA:</strong>--></td>
              <td><?php echo $this->_tpl_vars['rec']['FECHALetra']; ?>
</td>
	<td><!--<strong>RUC:</strong>--></td>
              <td colspan="2"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['ruc'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 40) : smarty_modifier_truncate($_tmp, 40)); ?>
</td>
        </tr>
  </table>
  <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 50pt; border:#FFFFFF solid 1px; width:'21cm' ; height:'16.5cm'" > 
	<tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow cabTabla resalta" style="width:60pt;text-align:center;"><!--&nbsp;<strong>N0</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="width:70pt;text-align:center;"><!--&nbsp;<strong>CANTIDAD</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="width:80pt;text-align:center;"><!--&nbsp;<strong>Unidad</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="width:400pt" ><!--&nbsp;<strong>DESCRIPCION</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="width:100pt"><!--&nbsp;<strong>V. UNITARIO</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="width:100pt"><!--&nbsp;<strong>TOTAL</strong>--></td>
	</tr>
        
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<br />
<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
      <tr style="height:0.6cm;"><!-- style="border:#000000 solid 1px;">-->
	  <td class="colnum der" style="text-align:center;"></td>
	  <td class="colnum der" style="text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CANTI'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>  
	  <td class="coldata der" style="text-align:center;"></td>  
	  <td nowrap class="coldata espacioIzq" ><?php echo $this->_tpl_vars['rec']['ITEM']; ?>
</td>
	  <td class="colnum espacioDer der" ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['vunit'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>        
	  <td class="colnum espacioDer der" ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['VALOR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
      </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
      <tr style="height:0.5cm;">
	<td colspan=3>&nbsp;</td>
	<!--<td align="left" class="coldata espacioIzq" colspan=3 nowrap><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CONCEP'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/[\n]/", "<br>") : smarty_modifier_regex_replace($_tmp, "/[\n]/", "<br>")); ?>
</td>--><!--Reemplaza los enter por salto de lineas-->
	<td align="left" class="coldata espacioIzq" nowrap><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CONCEP'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/[\n]/", "</td><tr style='height:0.5cm;'><td colspan=3>&nbsp;</td>
	<td align='left' class='coldata espacioIzq'>") : smarty_modifier_regex_replace($_tmp, "/[\n]/", "</td><tr style='height:0.5cm;'><td colspan=3>&nbsp;</td>
	<td align='left' class='coldata espacioIzq'>")); ?>
</td>
      </tr>
    </table>
</div>
	<!--  padding-bottom:40px; -->
    <table border=0 cellspacing=1 style=" padding-left: 50pt; table-layout:fixed; padding-top:10px; margin-bottom:167px; border:#FFFFFF solid 1px; width:'21cm'; height:'3.5cm'" > 
    <tr style="height:0.7cm;"> 
        <td rowspan=9  class="resalta" style="vertical-align: top; width:580pt; padding-top:35pt; padding-left:70pt;"><?php echo $this->_tpl_vars['letras']; ?>
</td>
        <td class ="resalta"       style="width:71pt"><!--<strong>SUBTOTAL:</strong>--></td>
        <td class="espacioDer der" style="width:71pt ; padding-top:25pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['BASEIMP'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
&nbsp;</td>
    </tr>
    <tr style="height:0.7cm;"> <!-- Descuento -->
        <td><strong>&nbsp;</strong></td>
        <td class="colnum espacioDer der" style="padding-top:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['VALDscto'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
&nbsp;</td>
    </tr>
    <tr style="height:0.7cm;"> <!-- Subtotal 0 -->
        <td><strong>&nbsp;</strong></td>
        <td class="colnum espacioDer der" style="padding-top:9px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['BASE0'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
&nbsp;</td>
    </tr>
    <tr style="height:0.7cm;">
        <td class ="resalta"><!--<strong>IVA:</strong>--></td>
        <td class="colnum espacioDer der" style="padding-top:9px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['iva'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
&nbsp;</td>
    </tr>
    <tr style="height:0.7cm;">
        <td class ="resalta" style="width:100px;"><!--<strong>TOTAL :</strong>--></td>
        <td class="colnum espacioDer der" style="padding-top:9px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['valorTot'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
   </table> 
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</div>
</body>