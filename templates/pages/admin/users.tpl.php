<?php // users.tpl.php
////////////////////////
// Template for user management ?>
<script type="text/javascript">
	<!--
	function deleteuser(user){
		var answer = confirm ("Are you sure you wish to delete the user \"" + user + "\"")
		if (answer){
			window.location="<?php echo $this->tData['pagePrefix']?>admin/users<?php echo janitor::getUrlString("&delete=")?>" + user
		}
	}
	// -->
</script>
<div id="admin_content">
  <?php include $this->tplPathComponent('adminNav')?>

  <h2>User management.</h2>
  <?php $form = $this->tData['form']['data']; ?>
  <?php if($form == $this->tData['form']['submitting'] && isset($this->tData['form']['validation']['existant']['invalid'])): ?>
    <?php foreach($this->tData['form']['validation']['existant']['invalid'] as $k): ?>
  <p class="warningtext"><?php echo $k?> is invalid.</p>
    <?php endforeach; ?>
  <?php endif; ?>
  <?php if($form == $this->tData['form']['submitting'] && isset($this->tData['form']['validation']['untaken']['invalid'])): ?>
    <?php foreach($this->tData['form']['validation']['untaken']['invalid'] as $k => $v): ?>
  <p class="warningtext"><?php echo $k?> &#8216;<?php echo $v?>&#8217; already exists.</p>
    <?php endforeach; ?>
  <?php endif; ?>
  <?php if($this->tData['form']['validation']['passed']): ?>
  <p class="infotext">Database Updated.</p>
  <?php endif; ?>
  <form
	action="<?php echo $this->tData['pagePrefix']?>admin/users<?php echo janitor::getUrlString("&user=".$_GET['user']."&commit=true")?>"
	method="POST">
<div id="pagesForm">
<p><label for="user"> User: </label> <input type="text" name="user"
	value="<?php echo $form['user']?>" /></p>
<p><label for="email"> E-Mail: </label> <input type="text" name="email"
	value="<?php echo $form['email']?>" /></p>
<p><label for="fullname"> Fullname: </label> <input type="text"
	name="fullname" value="<?php echo $form['fullname']?>" /></p>
<p><label for="password"> Password: </label> <input type="text"
	name="password" value="<?php echo $form['password']?>" /></p>
<p><label for="accessLevel"> Access Level: </label> <select
	name="accessLevel">
          <?php foreach($this->tData['form']['accessLevels'] as $a => $b): ?>
            <option value="<?php echo $a?>"
		<?php echo ($a == $form['accessLevel'] ? ' selected' : '')?>><?php echo $b?></option>
          <?php endforeach; ?>
        </select></p>
<p style="text-align: center;"><input type="submit"
	value="<?php echo ($tData['form']['editing'] ? 'Edit' : 'Add')?>"
	style="width: 100px"></p>
</div>
</form>
<div>
<h4><a href="<?php echo $this->tData['pagePrefix']?>admin/users">Create
New...</a></h4>
</div>
<table style="width: 98%; margin: 10px;">
	<th>Table of Users</th>
	<th>E-Mail</th>
	<th>Full Name</th>
	<th>Access Level</th>
	<th></th>
    <?php foreach($this->tData['form']['userlist'] as $k => $v): ?>
    <tr>
		<td><a
			href="<?php echo $this->tData['pagePrefix']?>admin/users<?php echo janitor::getUrlString("&user=$k")?>"><?php echo $k?></a>
		</td>
		<td>
        <?php echo $v['email'] ?>
      </td>
		<td>
        <?php echo $v['fullname'] ?>
      </td>
		<td style="width: 40px">
        <?php echo $v['accessLevel'] ?>
      </td>
		<td style="width: 50px"><a
			href="javascript:deleteuser('<?php echo $k?>')">[x]</a></td>
	</tr>
    <?php endforeach; ?>
  </table>
</div>