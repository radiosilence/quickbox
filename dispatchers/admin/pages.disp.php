<?php
/**
 * User Editing
 * @deprecated
 */
# Getting page data
$name = $this->db->escape($_GET['name']);
# Deciding whether we are editing or selecting
if (strlen($name) > 0)
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
# Get a list of sections for use in dropdowns
$result = $this->db->query("SELECT * FROM qbSections");
while ($row = $this->db->assoc($result))
{
	$this->dOutput['form']['sections'][$row['id']] = $row['title'];
}
# Get a list of subpages for use in dropdowns
$result = $this->db->query("SELECT * FROM qbSubPages");
while ($row = $this->db->assoc($result))
{
	$this->dOutput['form']['subPages'][$row['title']] = $row['title'];
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
	$this->db->query("DELETE FROM qbPages WHERE name = '$delete' LIMIT 1");
}
# Database editing bit
if ($_GET['commit'] == true)
{
	$this->dOutput['form']['submitting'] = true;
	# -- validation -- 
	$necessaryItems = array (
		'title' => 'Title' , 
		'pageTitle' => 'Page Title' , 
		'qbDispatcher' => 'Dispatcher' , 
		'path' => 'Path'
	);
	if (! $this->dOutput['form']['editing'])
	{
		$uniqueItems = array (
			'name' => 'Internal Name'
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
		# Sanitizing our input
		foreach ($this->dOutput['form']['data'] as $k => $v)
		{
			$cleanData[$k] = $this->db->escape($v);
		}
		$cleanData['visible'] = ($cleanData['visible'] == 'on' ? 1 : 0);
		# Queries are here.
		if ($this->dOutput['form']['editing'])
		{
			# Telling template all went okay
			$this->dOutput['form']['validation']['passed'] = true;
			$query = "UPDATE `qbPages` SET
								`name` = '" . $cleanData['name'] . "',
								`qbDispatcher` = '" . $cleanData['qbDispatcher'] . "',
								`qbSectionId` = '" . $cleanData['qbSectionId'] . "',
								`title` = '" . $cleanData['title'] . "',
								`path` = '" . $cleanData['path'] . "',
								`visible` = '" . $cleanData['visible'] . "',
								`order` = '" . $cleanData['order'] . "',
								`pageTitle` = '" . $cleanData['pageTitle'] . "',
								`pageVars` = '" . $cleanData['pageVars'] . "',
								`qbSubPageTitle` = '" . $cleanData['qbSubPageTitle'] . "'
								WHERE `qbPages`.`name` = '" . $safeGet['name'] . "' LIMIT 1 ;";
			if ($safeGet['name'] !== $cleanData['name'])
			{
				$this->dOutput['redirect'] = 'admin/pages&name=' . $cleanData['name'];
			}
		} else
		{
			$query = "INSERT INTO `qbPages`
								( `id` ,
								`name` ,
								`qbDispatcher` ,
								`qbSectionId` ,
								`path` ,
								`hostpage` ,
								`title` , 
								`description` , 
								`visible` , 
								`order` , 
								`pageTitle` , 
								`accessLevel`, 
								`pageVars`,
                `qbSubPageTitle`)
							VALUES (
								NULL , 
								'" . $cleanData['name'] . "', 
								'" . $cleanData['qbDispatcher'] . "', 
								'" . $cleanData['qbSectionId'] . "', 
								'" . $cleanData['path'] . "', 
								'', 
								'" . $cleanData['title'] . "', 
								'', 
								'" . $cleanData['visible'] . "', 
								'" . $cleanData['order'] . "',
								'" . $cleanData['pageTitle'] . "', 
								'0',
								'" . $cleanData['pageVars'] . "', 
								'" . $cleanData['qbSubPageTitle'] . "'
							);";
		}
		$this->db->query($query);
	}
}
# Output, based on editing mode or selecting mode
if ($this->dOutput['form']['editing'])
{
	# -- if we are editing --
	$result = $this->db->query("SELECT * FROM qbPages WHERE name = '$name' LIMIT 1");
	while ($row = $this->db->assoc($result))
	{
		foreach ($row as $k => $v)
		{
			$this->dOutput['form']['data'][$k] = stripslashes($v);
		}
	}
} else
{
	# -- if we are listing/making a new page --
	# These are some defaults:
	$this->dOutput['form']['data']['qbDispatcher'] = 'standardContent';
	$this->dOutput['form']['data']['path'] = 'generic';
	$this->dOutput['form']['data']['visible'] = '1';
}
# SQL to get a lost of things sorted by section
$result = $this->db->query(
"SELECT
                             `qbSections`.`title` as `sTitle`,
                             `qbPages`.`name` as `pName`,
                             `qbPages`.`title` as `pTitle`
                            FROM `qbPages`
                            LEFT JOIN `qbSections`
                            ON `qbPages`.`qbSectionId` = `qbSections`.`Id`
                            WHERE `qbSections`.`name` != 'admin'
                            ORDER BY `qbSections`.`order`,`qbPages`.`order` ASC                            
                             ");
while ($row = $this->db->assoc($result))
{
	$this->dOutput['form']['pageList'][$row['sTitle']]['pages'][$row['pName']]['name'] = stripslashes($row['pTitle']);
	if ($name == $row['pName'])
	{
		$this->dOutput['form']['pageList'][$row['sTitle']]['selected'] = true;
		$this->dOutput['form']['pageList'][$row['sTitle']]['pages'][$row['pName']]['selected'] = true;
	}
}
?>