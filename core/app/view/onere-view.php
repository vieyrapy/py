
<style type="text/css">
	
	.tabla {
  margin: 0 auto;
}
.tabla thead {
  cursor: pointer;
  background: rgba(0, 0, 0, .2);
}
.tabla thead tr th { 
  font-weight: bold;
  padding: 10px 20px;
}
.tabla thead tr th span { 
  padding-right: 20px;
  background-repeat: no-repeat;
  background-position: 100% 55%;
}
.tabla thead tr th.headerSortUp,
.tabla thead tr th.headerSortDown {
  background: rgba(0, 0, 0, .2);
}
.tabla thead tr th.headerSortUp span {
  background-image: url('http://tablesorter.com/themes/blue/asc.gif');
}
.tabla thead tr th.headerSortDown span {
  background-image: url('http://tablesorter.com/themes/blue/desc.gif');
}
.tabla tbody tr td {
  text-align: center;
  padding: 10px 20px;
}
.tabla tbody tr td.align-left {
  text-align: left;
}
</style>
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="report/onere-word.php?id=<?php echo $_GET["id"];?>">Word 2007 (.docx)</a></li>
  </ul>
</div>
<h1>Resumen de Reabastecimiento</h1>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$total = 0;
?>
<?php
if(isset($_COOKIE["selled"])){
	foreach ($operations as $operation) {
//		print_r($operation);
		$qx = OperationData::getQYesF($operation->product_id);
		// print "qx=$qx";
			$p = $operation->getProduct();
		if($qx==0){
			echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> no tiene existencias en inventario.</p>";			
		}else if($qx<=$p->inventary_min/2){
			echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene muy pocas existencias en inventario.</p>";
		}else if($qx<=$p->inventary_min){
			echo "<p class='alert alert-warning'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene pocas existencias en inventario.</p>";
		}
	}
	setcookie("selled","",time()-18600);
}

?>
<table class="table table-bordered">
<?php if($sell->person_id!=""):
$client = $sell->getPerson();
?>
<tr>
	<td style="width:150px;">Proveedor</td>
	<td><?php echo $client->company;?></td>
</tr>

<?php endif; ?>
<?php if($sell->user_id!=""):
$user = $sell->getUser();
?>
<tr>
	<td>Atendido por</td>
	<td><?php echo $user->name." ".$user->lastname;?></td>
</tr>
<?php endif; ?>
</table>
<br>

<script type="text/javascript" src="plugins/tablesorter/jquery.tablesorter.js"></script> 

<script>
	$(function(){
	  $('#mi-tabla').tablesorter(); 
	});

</script>
<table cellspacing="0" cellpadding="0" id="mi-tabla" class="tabla">
	<thead>
		<th>Proveedor</th>
		<th>Codigo</th>
		<th>Cantidad</th>
		<th>Nombre del Producto</th>
		<th>Precio Unitario</th>
		<th>Total</th>

	</thead>
<?php
	foreach($operations as $operation){
		$product  = $operation->getProduct();
?>
<tr>
	<td><?php if($product->person_id!=null){echo $product->getPerson()->company;}else{ echo "<center>----</center>"; }  ?></td>

	<td><?php echo $product->barcode ;?></td>
	<td><?php echo $operation->q ;?></td>
	<td><?php echo $product->name ;?></td>
	<td>Gs. <?php echo number_format($product->price_in,0,",",".") ;?></td>
	<td><b>Gs. <?php echo number_format($operation->q*$product->price_in,0,",",".");$total+=$operation->q*$product->price_in;?></b></td>
</tr>
<?php
	}
	?>
</table>
<br><br><h1>Total: Gs. <?php echo number_format($total,0,',','.'); ?></h1>
	<?php

?>	
<?php else:?>
	501 Internal Error
<?php endif; ?>