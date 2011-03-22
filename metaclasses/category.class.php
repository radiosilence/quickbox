<?php

/**
 * Contains category class.
 * 
 * @package quickbox
 * @subpackage metaclass
 * @category metaclasses
 */
/**
 * Article Category
 * 
 * An article category of sorts.
 * 
 */
class category extends metaclass
{
	/**
	 * Constructor
	 * @access protected
	 */
	/* Metaclass Property Setup */
	public static $title = "Categories";
	public static $table = "categories";
	public static $propertyDefinitions = array (
		'title' => array (
			'type' => 'string' , 
			'title' => 'Title' , 
			'linkfield' => true , 
			'ontable' => true , 
			'requirements' => array (
				'exists' => true
			)
		) , 
		'name' => array (
			'type' => 'alphanumeric' , 
			'title' => 'Name' , 
			'requirements' => array (
				'exists' => true , 
				'unique' => true
			)
		)
	);
	/* Start of Functions */
}
?>