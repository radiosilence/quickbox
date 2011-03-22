<?php
/**
 * Standard Content
 * 
 * Make a data arrays for page with 2-level menu, default template, a block of text.
 * 
 * @package quickbox
 * @subpackage dispatcher
 * @category dispatcher
 */
# get page data associated with page

# These common replacements for {templatey} type things in the content. Feature is minimal for speed.
$needles = array (
	'{page}'
);
$haystacks = array (
	config::get('site/htmlRoot') . config::get('site/pagePrefix')
);

if(locale::get() !== 'en')
{
	$langQuery = 'AND `lang` = \''.locale::get().'\'';
}

# We are going to get the extra content from the pagevars.
if (strlen($this->dPage['pageVars']['content']) > 0)
{
	$extraContent = explode(',', $this->dPage['pageVars']['content']);
}
if (is_array($extraContent))
{
	foreach ($extraContent as $k => $v)
	{
		$t = explode('=', $extraContent[$k]);
		$extraContent[$t[0]] = $t[1];
		unset($extraContent[$k]);
		$extraQuery .= ' OR `name` = \'' . $t[1] . '\'';
	}
}
# get page data associated with page
$query = "SELECT `name`, `content` FROM `qbContent` WHERE (`qbPageId` = '" . $this->dPage['id'] . "'$extraQuery) $langQuery";
$result = $this->db->query($query);
if($this->db->numRows($result) < 1)
{
	# If the content doesn't exist in the required language, try to get it in english
	# TODO: Make this non-buggy so that it actually does whats required >.<
	$query = "SELECT `name`, `content` FROM `qbContent` WHERE (`qbPageId` = '" . $this->dPage['id'] . "'$extraQuery) AND `lang` = 'en'";
	$result = $this->db->query($query);
}
while ($content = $this->db->assoc($result))
{
	$this->dOutput['content'][$content['name']] = stripslashes(str_replace($needles, $haystacks, $content['content']));
}
foreach ($extraContent as $k => $v)
{
	$this->dOutput['content'][$k] = $this->dOutput['content'][$v];
	unset($this->dOutput['content'][$v]);
}
?>
