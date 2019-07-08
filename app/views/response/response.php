<div class="alert alert-success">
<?=$response?>
<?php
	if(isset($links)){
		?>
		<ul>
		<?php
		foreach($links as $link => $disp){
			if($link <> ""){
			?>
			<li><a href="<?=$link?>"><?=$disp?></a><li>
			
			<?php
			}
		}?>
		<ul>
	<?php
	}
?>
</div>

