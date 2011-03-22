<?php
/**
 * Contains page class.
 *
 * @package quickbox
 * @subpackage metaclass
 * @category metaclasses
 * 
 */
/**
 * Page
 * 
 * A page!
 *
 */
class page extends metaclass
{
	/**
	 * Constructor
	 * @access protected
	 */
	/* Metaclass Property Setup */
	public static $title = "Pages";
	public static $table = "qbPages";
	public static $keyField = 'name';
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
		'qbSectionId' => array (
			'type' => 'foreign' , 
			'title' => 'Section' , 
			'requirements' => array (
				'real' => 'section'
			)
		) , 
		'name' => array (
			'ontable' => true , 
			'type' => 'string' , 
			'title' => 'Internal Name' , 
			'requirements' => array (
				'exists' => true , 
				'unique' => true
			)
		) , 
		'pageTitle' => array (
			'type' => 'string' , 
			'title' => 'Page Title' , 
			'requirements' => array (
				'exists' => true
			)
		) , 
		'qbDispatcher' => array (
			'type' => 'string' , 
			'title' => 'Dispatcher Used (blank for default)'
		) , 
		'path' => array (
			'type' => 'string' , 
			'title' => 'Template Path (blank for default)'
		) , 
		'hostpage' => array (
			'type' => 'string' , 
			'title' => 'Hosting Template (blank for default)'
		) , 
		'visible' => array (
			'type' => 'string' , 
			'title' => 'Visibility in Menus'
		) , 
		'order' => array (
			'type' => 'string' , 
			'title' => 'Order' , 
			'ontable' => true , 
			'requirements' => array (
				'exists' => true
			)
		) , 
		'accessLevel' => array (
			'type' => 'string' , 
			'title' => 'Access Level' , 
			'ontable' => true
		) , 
		'pageVars' => array (
			'type' => 'textblock' , 
			'title' => 'Page Variables'
		)
	);
}
?>