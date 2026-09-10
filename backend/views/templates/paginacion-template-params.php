<?php 
include_once LIB.'/paramsUrl.php';

?>

<ul class="pagination pull-right mt0">
	<?php if( $_SESSION['primeraPagina'] == 1 ){ ?>
		<li class="disabled"><a href="<?php echo addToURL($pagina-1, 'pag');?>">&laquo;</a></li>
	<?php }else{ ?>
		<li><a href="<?php echo addToURL($pagina-1, 'pag');?>">&laquo;</a></li>
	<?php } ?>
	<?php for ($i=0; $i < $_SESSION['paginas']; $i++) { ?>
		<li
			<?php if ($pagina == $i+1){
				echo 'class="active"';
			}?>
		><a href="<?php echo addToURL($i +1, 'pag');?>"><?php echo $i +1; ?></a></li>
	<?php } ?>
	<?php if( $_SESSION['ultimaPagina'] == 1){ ?>
		<li class="disabled"><a href="<?php echo addToURL($pagina+1, 'pag');?>">&raquo;</a></li>
	<?php }else{ ?>
		<li><a href="<?php echo addToURL($pagina+1, 'pag');?>">&raquo;</a></li>
	<?php } ?>
</ul>