<?php 
/**
 * @deprecated
 */
# Getting page data
$id = $this->db->escape($_GET['id']);
# Deciding whether we are editing or selecting
if (strlen($id) > 0)
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
# Get a list of pagess for use in dropdowns
$result = $this->db->query("SELECT * FROM qbSections ORDER BY `order` ASC");
while ($sect = $this->db->assoc($result))
{
	$this->dOutput['form']['pages']['sect' . $sect['id']] = '' . stripslashes($sect['title']) . '';
	$result2 = $this->db->query(
	"SELECT id,name,title FROM qbPages WHERE qbSectionId = '" . $sect['id'] . "' ORDER BY `order` ASC");
	while ($row = $this->db->assoc($result2))
	{
		$this->dOutput['form']['pages'][$row['name']] = '-- ' . stripslashes($row['title']);
	}
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
	$this->db->query("DELETE FROM qbSubPages WHERE id = '$delete' LIMIT 1");
}
# Database editing bit
if ($_GET['commit'] == true)
{
	$this->dOutput['form']['submitting'] = true;
	# -- validation -- 
	$necessaryItems = array (
		'title' => 'Title' , 
		'qbPageNameLinked' => 'Linked to Page'
	);
	if (! $this->dOutput['form']['editing'])
	{
		$uniqueItems = array (
		);
	}
	$dValidator = new validator($this->db);
	$this->dOutput['form']['validation']['existant'] = $dValidator->arrayItems($this->dOutput['form']['data'], 
	$necessaryItems);
	$this->dOutput['form']['validation']['untaken'] = $dValidator->isTaken($this->dOutput['form']['data'], $uniqueItems, 
	'qbPages');
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
		# Queries are here.
		if ($this->dOutput['form']['editing'])
		{
			$query = "UPDATE `qbSubPages` SET
								`title` = '" . $cleanData['title'] . "',
								`qbPageNameLinked` = '" . $cleanData['qbPageNameLinked'] . "',
								`order` = '" . $cleanData['order'] . "'
								WHERE `qbSubPages`.`id` = '" . $cleanData['id'] . "' LIMIT 1 ;";
		} else
		{
			$query = "INSERT INTO `qbSubPages`
								( `id` ,
								`title`,
								`qbPageNameLinked` ,
								`order`)
							VALUES (
								NULL , 
								'" . $cleanData['title'] . "',
								'" . $cleanData['qbPageNameLinked'] . "', 
								'" . $cleanData['order'] . "'
							);";
		}
		$this->db->query($query);
	}
}
# Output, based on editing mode or selecting mode
if ($this->dOutput['form']['editing'])
{
	# -- if we are editing --
	$result = $this->db->query("SELECT * FROM qbSubPages WHERE id = '$id' LIMIT 1");
	while ($row = $this->db->assoc($result))
	{
		foreach ($row as $k => $v)
		{
			$this->dOutput['form']['data'][$k] = stripslashes($v);
		}
	}
}
# -- if we are listing/making a new page --
# SQL to get a lost of things sorted by section/oage
$result3 = $this->db->query("SELECT id,title,qbPageNameLinked FROM qbSubPages ORDER BY `order` ASC");
while ($row2 = $this->db->assoc($result3))
{
	$this->dOutput['form']['subPageList'][$row2['title']][$row2['id']] = $row2['qbPageNameLinked'];
}
?>