<?php
/**
 * User Editing
 * @deprecated
 */
# This is an array of the access levels
$this->dOutput['form']['accessLevels'] = array (
	'1' => 'User' , 
	'2' => 'Editor' , 
	'3' => 'Administrator'
);
# Sanitize
$user = $this->db->escape($_GET['user']);
# Deciding whether we are editing or selecting
if (strlen($user) > 0)
{
	$this->dOutput['form']['editing'] = true;
} else
{
	$this->dOutput['form']['editing'] = false;
}
# Deciding if we are deleting
if (strlen($_GET['delete']) > 0)
{
	$this->dOutput['form']['deleting'] = $_GET['delete'];
}
if ($this->dOutput['form']['editing'])
{
	# -- if we are editing --
	$result = $this->db->query("SELECT * FROM qbUsers WHERE user = '$user' LIMIT 1");
	while ($row = $this->db->assoc($result))
	{
		foreach ($row as $k => $v)
		{
			$this->dOutput['form']['data'][$k] = stripslashes($v);
		}
	}
	# Ignore the database field password
	$this->dOutput['form']['data']['password'] = '(no change)';
}
# For each set field in post, change our array
foreach ($_POST as $k => $v)
{
	$this->dOutput['form']['data'][$k] = $v;
}
# If we are deleting something, do this. Confirmation handled by template.
if ($this->dOutput['form']['deleting'] !== false)
{
	$delete = $this->db->escape($this->dOutput['form']['deleting']);
	$this->db->query("DELETE FROM qbUsers WHERE user = '$delete' LIMIT 1");
}
# Database editing bit
if ($_GET['commit'] == true)
{
	$this->dOutput['form']['submitting'] = true;
	# -- validation --
	$necessaryItems = array (
		'user' => 'User' , 
		'email' => 'E-Mail' , 
		'fullname' => 'Fullname' , 
		'password' => 'Password'
	);
	if (! $this->dOutput['form']['editing'])
	{
		$uniqueItems = array (
			'user' => 'User'
		);
	} else
	{
		$uniqueItems = array (
		);
	}
	$dValidator = new validator($this->db);
	$this->dOutput['form']['validation']['existant'] = $dValidator->arrayItems($this->dOutput['form']['data'], 
	$necessaryItems);
	$this->dOutput['form']['validation']['untaken'] = $dValidator->isTaken($this->dOutput['form']['data'], $uniqueItems, 
	'qbUsers');
	if (! isset($this->dOutput['form']['validation']['existant']['invalid']) && ! isset(
	$this->dOutput['form']['validation']['untaken']['invalid']))
	{
		# Telling template all went okay
		$this->dOutput['form']['validation']['passed'] = true;
		# Sanitizing our input
		foreach ($this->dOutput['form']['data'] as $k => $v)
		{
			$cleanData[$k] = $this->db->escape($v);
		}
		
		$passwd = janitor::passwd($cleanData['password']);
		# Queries are here.
		if ($this->dOutput['form']['editing'])
		{
			$query = "UPDATE `qbUsers` SET
								`user` = '" . $cleanData['user'] . "',
								`email` = '" . $cleanData['email'] . "',
								`fullname` = '" . $cleanData['fullname'] . "',
								" . ($cleanData['password'] == "(no change)" ? '' : "`password` = '" .
			 					$passwd['passwd'] . "',") . "
								" . ($cleanData['password'] == "(no change)" ? '' : "`salt` = '" .
			 					$passwd['salt'] . "',") . "
								`accessLevel` = '" . $cleanData['accessLevel'] . "'
								WHERE `qbUsers`.`user` = '" . $cleanData['user'] . "' LIMIT 1 ;";
		} else
		{
			$query = "INSERT INTO `qbUsers`
								( `id` ,
								`user` ,
								`email` ,
								`fullname` ,
								`password` ,
								`salt` ,
								`accessLevel`)
							VALUES (
								NULL ,
								'" . $cleanData['user'] . "',
								'" . $cleanData['email'] . "',
								'" . $cleanData['fullname'] . "',
								'" . $passwd['passwd'] . "',
								'" . $passwd['salt'] . "',
								'" . $cleanData['accessLevel'] . "'
							);";
		}
		$this->db->query($query);
	}
}
# -- if we are listing/making a new page --
# These are some defaults:
# SQL to get a list of things
$result = $this->db->query("SELECT * FROM qbUsers");
while ($row = $this->db->assoc($result))
{
	$this->dOutput['form']['userlist'][$row['user']]['user'] = stripslashes($row['user']);
	$this->dOutput['form']['userlist'][$row['user']]['email'] = stripslashes($row['email']);
	$this->dOutput['form']['userlist'][$row['user']]['fullname'] = stripslashes($row['fullname']);
	$this->dOutput['form']['userlist'][$row['user']]['accessLevel'] = stripslashes($row['accessLevel']);
}
?>