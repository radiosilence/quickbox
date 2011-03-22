<?php

/**
 * Contains article class.
 *
 * @package quickbox
 * @subpackage metaclass
 * @category metaclasses
 * 
 */
/**
 * Generic Article
 * 
 * Stop press.
 *
 */
class article extends metaclass
{
	/**
	 * Constructor
	 * @access protected
	 */
	/* Metaclass Property Setup */
	public static $title = "Article";
	public static $table = "articles";
	public static $propertyDefinitions = array (
		'title' => array (
			'type' => 'string' , 
			'title' => 'Title' , 
			'ontable' => true , 
			'linkfield' => true , 
			'requirements' => array (
				'exists' => true
			)
		) , 
		'name' => array (
			'type' => 'string' , 
			'title' => 'Simple Name (for URL)' , 
			'requirements' => array (
				'exists' => true , 
				'unique' => true
			)
		) , 
		'author' => array (
			'type' => 'string' , 
			'title' => 'Author'
		) , 
		'date' => array (
			'type' => 'string' , 
			'ontable' => true , 
			'internal' => true
		) , 
		'fulltext' => array (
			'type' => 'textblock' , 
			'title' => 'Content'
		) , 
		'preview' => array (
			'type' => 'textblock' , 
			'title' => 'Preview'
		) , 
		'tags' => array (
			'type' => 'string' , 
			'title' => 'Tags'
		) , 
		'machinetags' => array (
			'type' => 'string' , 
			'title' => 'Machine Tags'
		)
	);

	/* Start of Functions */
	public function save ()
	{
		# Set a registration date if one doesn't exist (ie, a new user).
		if ($this->getProperty('date', true) == null)
		{
			$this->setProperty('date', date('Y-m-d H:i:s'));
		} else
		{
			$this->setProperty('date', $this->getProperty('date', true));
		}
		$this->saveObject();
	}
}
?>