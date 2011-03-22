<?php
if ($this->dPage['pageVars']['editor'])
{
	$item = new scaffold($this->dPage['pageVars']['editor'], $_GET['id']);
	if ($_POST['scaffoldSubmitted'] == 'true')
	{
		$item->process($_POST);
		$this->dOutput['formErrors'] = $item->invalid;
	}
	if ($_GET['delete'])
	{
		$item->delete($_GET['delete']);
	}
	$this->dOutput['form'] = $item->makeForm();
	$this->dOutput['baseurl'] = $item->urlbase;
	$this->dOutput['table'] = $item->makeTable();
} else {
	
		$item = new scaffold('user', $_GET['id']);
	if ($_POST['scaffoldSubmitted'] == 'true')
	{
		$item->process($_POST);
		$this->dOutput['formErrors'] = $item->invalid;
	}
	if ($_GET['delete'])
	{
		$item->delete($_GET['delete']);
	}
	$this->dOutput['form'] = $item->makeForm();
	$this->dOutput['baseurl'] = $item->urlbase;
	$this->dOutput['table'] = $item->makeTable();
}
?>