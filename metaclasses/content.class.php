<?php
/**
 * Contains content class.
 *
 * @package quickbox
 * @subpackage metaclass
 * @category metaclasses
 * 
 */
/**
 * Content
 * 
 * Content to be stuck inside a page.
 *
 */
class content extends metaclass
{
	/**
	 * Constructor
	 * @access protected
	 */
	/* Metaclass Property Setup */
	public static $title = "Page Contents";
	public static $table = "qbContent";
	public static $keyField = "title";
	public static $propertyDefinitions = array (
		'title' => array (
			'type' => 'string' , 
			'title' => 'Identification Title' , 
			'linkfield' => true , 
			'ontable' => true , 
			'requirements' => array (
				'exists' => true
			)
		) , 
		'name' => array (
			'ontable' => true , 
			'type' => 'string' , 
			'title' => 'Internal Name' , 
			'requirements' => array (
				'exists' => true , 
			)
		) , 
		'qbPageId' => array (
			'type' => 'foreign' , 
			'title' => 'Page' , 
			'requirements' => array (
				'real' => 'page'
			)
		) , 
		'content' => array (
			'type' => 'wysiwyg' , 
			'title' => 'Content'
		) , 
		'lang' => array (
			'type' => 'string' ,  
			'ontable' => true , 
			'title' => 'Language'
		) , 
	);
}
?>