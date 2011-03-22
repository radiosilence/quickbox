<?php
/**
 * Standard Page
 * 
 * This is to include the basic components that most pages will use.
 * 
 * @package quickbox
 * @subpackage dispatcher
 * @category dispatcher
 */
# Menu
$result0 = $this->db->query("SELECT qbSectionId FROM qbPages WHERE name = '" . $this->dPage['name'] . "' LIMIT 1");
$thispage = $this->db->assoc($result0);
$result = $this->db->query("SELECT * FROM qbSections WHERE visible = '1' ORDER BY `order` ASC");
if(!config::get('site/noDefaultMenu'))
{while ($row = $this->db->assoc($result))
{
	$j = 0;
	$i = 0;
	$this->dOutput['menu']['mainMenu'][$row['id']]['name'] = stripslashes($row['name']);
	$this->dOutput['menu']['mainMenu'][$row['id']]['title'] = stripslashes($row['title']);
	$this->dOutput['menu']['mainMenu'][$row['id']]['defaultpage'] = $row['defaultpage'];
	if ($row['id'] == $thispage['qbSectionId'])
	{
		$query2 = "SELECT * FROM
                  qbSectionsPages
                LEFT JOIN
                  qbSections
                ON
                  qbSectionsPages.qbSectionId = qbSections.id
                LEFT JOIN
                  qbPages
                ON
                  qbSectionsPages.qbPageId = qbPages.id
                WHERE
                  qbSectionsPages.qbSectionId = '" . $row['id'] . "'
                AND
                  qbPages.accessLevel <= '" . $_SESSION['userdata']['accessLevel'] .
				 "'
                ORDER BY
                  qbPages.`order`
                    ASC";
		$result2 = $this->db->query($query2);
		if ($this->db->numRows($result2) < 1)
		{
			continue;
		}
		$z = 1;
		while ($row2 = $this->db->assoc($result2))
		{
			$i = ($row2['name'] == $this->dPage['name'] ? 1 : $i);
			if ($row2['visible'] == 1)
			{
				$j = (strpos($this->dPage['name'], $row2['name']) !== false ? 1 : 0);
				$this->dOutput['menu']['secMenu'][$z] = $row2;
				$this->dOutput['menu']['secMenu'][$z]['current'] = ($j > 0 ? true : false);
				$z ++;
			}
		}
		$this->dOutput['menu']['mainMenu'][$row['id']]['selected'] = true;
	}
}}
$this->dOutput['session']['user'] = (isset($_SESSION['user']) ? $_SESSION['user'] : false);
?>