<?php
/**
 * news.disp.php
 * 
 * @version $Id$
 * @copyright 2008
 */
$md = new markdown();
if (isset($safeGet['name']))
{
	# This code will break in the year 2100 (due to their choice of date format) but we will probably have been
	# eaten by robots by then anyway so it doesn't matter.
	$name = $safeGet['name'];
	$query = new query();
	$query->select('id')->from('articles')->where('name', $name)->limit('1');
	$result = $this->db->query($query);
	$row = $this->db->assoc($result);
	$article = metaclass::load('article', $row['id']);
	$this->dOutput['article']['header'] = $article->getProperty('title');
	$this->dOutput['article']['date'] = janitor::formatMysqlDateTime($article->getProperty('date'), 'l jS F, Y');
	$this->dOutput['article']['fulltext'] = $md->process($article->getProperty('fulltext'));
	$this->dOutput['pageTitle'] = $article->getProperty('title');
} else
{
	$query = new query();
	$query->select(array (
		'id' , 
		'name' , 
		'date' , 
		'title' , 
		'preview'
	))->from('articles')->order('date', 'desc');
	$result = $this->db->query($query);
	$i = 0;
	while ($row = $this->db->assoc($result))
	{
		$this->dOutput['articles']['listing'] = true;
		$row['dateLink'] = $row['name'];
		$this->dOutput['articles']['articles'][$i] = $row;
		$this->dOutput['articles']['articles'][$i]['preview'] = $md->process($row['preview']);
		$this->dOutput['articles']['articles'][$i]['date'] = janitor::formatMysqlDateTime($row['date'], 'jS M, Y');
		$i ++;
	}
}
?>