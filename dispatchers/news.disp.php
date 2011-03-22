<?php
/**
 * news.disp.php
 *
 * @version $Id$
 * @copyright 2008
 */
if (isset($safeGet['name']))
{
	# This code will break in the year 2100 (due to their choice of date format) but we will probably have been
	# eaten by robots by then anyway so it doesn't matter.
	$name = $safeGet['name'];
	$query = new query();
	$query->select('id')->from('newsArticles')->where('name', $name)->limit('1');
	$result = $this->db->query($query);
	$row = $this->db->assoc($result);
	$article = metaclass::load('newsArticle', $row['id']);
	$this->dOutput['news']['header'] = $article->getProperty('title');
	$this->dOutput['news']['content'] = $article->getProperty('text');
} else
{
	$query = new query();
	$query->select(array (
		'id' , 
		'name' , 
		'date' , 
		'title' , 
		'textshort'
	))->from('newsArticles')->order('date', 'desc');
	$result = $this->db->query($query);
	$i = 0;
	while ($row = $this->db->assoc($result))
	{
		$this->dOutput['news']['listing'] = true;
		$row['date'] = explode('-', $row['date']);
		$row['date'][0] = substr($row['date'][0], 2, 2);
		$row['date'] = implode('/', array_reverse($row['date']));
		$row['dateLink'] = $row['name'];
		$this->dOutput['news']['articles'][$i] = $row;
		$i ++;
	}
}
?>