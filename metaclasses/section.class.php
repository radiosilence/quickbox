<?php

/**
 * Contains section class.
 *
 * @package quickbox
 * @subpackage metaclass
 * @category metaclasses
 * 
 */
/**
 * Section
 * 
 * A Section!
 *
 */
class section extends metaclass
{
	/**
	 * Constructor
	 * @access protected
	 */
	/* Metaclass Property Setup */
	public static $title = "Sections";
	public static $table = "qbSections";
	public static $propertyDefinitions = array (
		'name' => array (
			'ontable' => true , 
			'type' => 'string' , 
			'title' => 'Internal Name' , 
			'requirements' => array (
				'exists' => true , 
				'unique' => true
			)
		) , 
		'title' => array (
			'type' => 'string' , 
			'title' => 'Display Name' , 
			'linkfield' => true , 
			'ontable' => true , 
			'requirements' => array (
				'exists' => true
			)
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
		'defaultpage' => array (
			'type' => 'string' , 
			'title' => 'Default Page Location' , 
			'requirements' => array (
				'exists' => true
			)
		) , 
		'cssId' => array (
			'type' => 'string' , 
			'title' => 'CSS Body ID'
		)
	);
}
?>