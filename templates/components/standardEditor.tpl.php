 <script type="text/javascript">
	<!--
	function deleteitem(id,name,url){
		var answer = confirm ("Are you sure you wish to delete \"" + name + "\"")
		if (answer){
			window.location= url + "<?php
			echo janitor::getUrlString("&delete=")?>" + id
		}
	}
	// -->
</script>


<?php if($_GET['mode'] != 'single' && !$_GET['id']): ?>

<h5><button type="button" onclick="window.location= '<?php
echo $tData['baseurl']?>?mode=single'" class="button neutral"><img src="<?php
		 echo config::get('site/htmlRoot')?>qbres/images/new.png"/> <?php echo text::get('scaffold/createnew') ?></button></h5>
<hr />

<?php
echo $tData['table']?>


<?php else: ?>
<h5><button type="button" onClick="window.location = '<?php
echo $tData['baseurl']?>?mode='" class="button neutral"> <img src="<?php
		 echo config::get('site/htmlRoot')?>qbres/images/back.png"/><?php echo text::get('scaffold/returntoitems') ?></button></h5>

<hr />

<?php
echo $tData['form']?>

<?php endif; ?>
