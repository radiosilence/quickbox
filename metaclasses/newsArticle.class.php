<?php

/**
 * Contains newsArticle class.
 *
 * @package quickbox
 * @subpackage metaclass
 * @category metaclasses
 * 
 */
/**
 * News Article
 * 
 * Stop press.
 *
 */
class newsArticle extends metaclass
{
	/**
	 * Constructor
	 * @access protected
	 */
	/* Metaclass Property Setup */
	public static $title = "News Article";
	public static $table = "newsArticles";
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
			'type' => 'alphanumeric' , 
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
			'type' => 'date' , 
			'ontable' => true , 
			'title' => 'Date'
		) , 
		'text' => array (
			'type' => 'wysiwyg' , 
			'title' => 'Content'
		) , 
		'textshort' => array (
			'type' => 'textblock' , 
			'title' => 'Preview'
		) , 
		'category' => array (
			'type' => 'foreign' , 
			'title' => 'Category' , 
			'requirements' => array (
				'real' => 'category'
			)
		)
	);
	/* Start of Functions */
}
?>