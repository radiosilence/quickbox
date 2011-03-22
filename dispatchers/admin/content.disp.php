<?php 
/**
 * @deprecated
 */
# Quick addition - disable pita TinyMCE in session!
if ($_GET['tinymce_disable'] == 'true')
{
	$_SESSION['tinymce_disable'] = true;
} else if ($_GET['tinymce_disable'] == 'false')
{
	$_SESSION['tinymce_disable'] = false;
}
$this->dOutput['tinymce_disable'] = $_SESSION['tinymce_disable'];
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
$result = $this->db->query(
"SELECT
                              qbSections.id as sId,
                              qbSections.title as sTitle,
                              qbPages.id as pId,
                              qbPages.name as pTitle
                            FROM qbPages
                            LEFT JOIN qbSections
                            ON qbPages.qbSectionId = qbSections.Id
                            ORDER BY
                              qbSections.`order`,
                              qbPages.`order` ASC");
while ($row = $this->db->assoc($result))
{
	if ($row['sId'] !== $prevSect)
		$this->dOutput['form']['pages']['sect' . $row['sId']] = '' . stripslashes($row['sTitle']) . '';
	$this->dOutput['form']['pages'][$row['pId']] = '-- ' . stripslashes($row['pTitle']);
	$prevSect = $row['sId'];
}
# For each set field in post, change our array
foreach ($_POST as $k => $v)
{
	$this->dOutput['form']['data'][$k] = $v;
}
# If we are deleting something, do this. Confirmation handled by template.
if (strlen($this->dOutput['form']['deleting']) > 0)
{
	$delete = $this->db->escape($this->dOutput['form']['deleting']);
	$this->db->query("DELETE FROM qbContent WHERE id = '$delete' LIMIT 1");
}
# Database editing bit
if ($_GET['commit'] == true)
{
	$this->dOutput['form']['submitting'] = true;
	# -- validation -- 
	$necessaryItems = array (
		'name' => 'Name' , 
		'content' => 'Content' , 
		'qbPageId' => 'Page'
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
			$query = "UPDATE `qbContent` SET
								`name` = '" . $cleanData['name'] . "',
								`title` = '" . $cleanData['title'] . "',
								`qbPageId` = '" . $cleanData['qbPageId'] . "',
								`content` = '" . $cleanData['content'] . "'
								WHERE `qbContent`.`id` = '" . $cleanData['id'] . "' LIMIT 1 ;";
		} else
		{
			$query = "INSERT INTO `qbContent`
                  ( `id` ,
                  `name` ,
                  `title` ,
                  `qbPageId` ,
                  `content`)
                VALUES (
                  NULL , 
                  '" . $cleanData['name'] . "', 
                  '" . $cleanData['title'] . "', 
                  '" . $cleanData['qbPageId'] . "', 
                  '" . $cleanData['content'] . "'
                );";
		}
		$this->db->query($query);
	}
}
# Output, based on editing mode or selecting mode
if ($this->dOutput['form']['editing'])
{
	# -- if we are editing --
	$result = $this->db->query("SELECT * FROM qbContent WHERE id = '$id' LIMIT 1");
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
	# Defaults
	$this->dOutput['form']['data']['name'] = 'default';
	$this->dOutput['form']['data']['title'] = '<Page Name> default page';
	$this->dOutput['form']['data']['content'] = '';
	$this->dOutput['form']['data']['qbPageId'] = '';
}
# SQL to get a lost of things sorted by section/oage
$result2 = $this->db->query(
"SELECT
																qbPages.title as pTitle,
																qbContent.id,
																qbContent.name,
																qbSections.title as sTitle
															 FROM qbContent
															 LEFT JOIN
															 qbPages
															 ON qbContent.qbPageId = qbPages.id
															 LEFT JOIN
															 qbSections
															 ON qbSections.id = qbPages.qbSectionId
															 ORDER BY `qbSections`.`order`,`qbPages`.`order` ASC");
while ($row = $this->db->assoc($result2))
{
	$this->dOutput['form']['pageList'][$row['sTitle']]['pages'][$row['pTitle']][$row['id']]['name'] = $row['name'];
	if ($id == $row['id'])
	{
		$this->dOutput['form']['pageList'][$row['sTitle']]['selected'] = true;
		$this->dOutput['form']['pageList'][$row['sTitle']]['pages'][$row['pTitle']][$row['id']]['selected'] = true;
	}
}
?>