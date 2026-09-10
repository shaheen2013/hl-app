<?php
$allUrl = parse_url( $_SERVER["REQUEST_URI"] );
$urlSinParams = $allUrl['path'];
?>
<ul class="pagination pull-right mt0">
	<?php if( $_SESSION['primeraPagina'] == 1 ){ ?>
		<li class="disabled"><a href="<?php echo $urlSinParams; ?>?pag=<?php echo $pagina - 1;?>">&laquo;</a></li>
	<?php }else{ ?>
		<li><a href="<?php echo $urlSinParams; ?>?pag=<?php echo $pagina - 1;?>">&laquo;</a></li>
	<?php } ?>
	<?php for ($i=0; $i < $_SESSION['paginas']; $i++) { ?>
		<li
			<?php if ($pagina == $i+1){
				echo 'class="active"';
			}?>
		><a href="<?php echo $urlSinParams; ?>?pag=<?php echo $i + 1;?>"><?php echo $i +1; ?></a></li>
	<?php } ?>
	<?php if( $_SESSION['ultimaPagina'] == 1){ ?>
		<li class="disabled"><a href="<?php echo $urlSinParams; ?>?pag=<?php echo $pagina + 1;?>">&raquo;</a></li>
	<?php }else{ ?>
		<li><a href="<?php echo $urlSinParams; ?>?pag=<?php echo $pagina + 1;?>">&raquo;</a></li>
	<?php } ?>
</ul>