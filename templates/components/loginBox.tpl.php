<?php # Standard login / user status box for use on main page ?>




<?php if($this->tData['session']['user'] !== false): ?>

<p><?php echo text::get('login/loggedInAs',array($this->tData['session']['user'],$tData['pagePrefix']. 'logout'))?></p>

<?php else: ?>
<form action="<?php echo $PHP_SELF; ?>" method="post">

<h3>Login</h3>
	<?php if($tData['errors']['login']):?>
<p class="error"><?php echo $tData['errors']['login']?></p>
<?php endif;?>

	<p><input type="hidden" name="login" value="1" /> <label
	for="login_user">User</label><br />
<input type="text" id="login_user" class="text" style="width: 150px"
	name="user" /></p>
<p><label for="login_password">Password</label><br />
<input type="password" id="login_password" class="text"
	style="width: 150px" name="password" /></p>
<p>
<button type="submit">Login</button>
</p>
</form>

<?php endif; ?>