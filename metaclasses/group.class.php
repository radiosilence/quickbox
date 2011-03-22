<?php
/**
 * Contains group class.
 *
 * @package quickbox
 * @subpackage metaclass
 * @category metaclasses
 * 
 */
/**
 * Group
 * 
 * A usergroup!
 *
 */
class group extends metaclass
{
	/* Metaclass Property Setup */
	public static $title = "User Groups";
	public static $table = "userGroups";
	public static $location = "quickbox";
	public static $propertyDefinitions = array (
		'title' => array (
			'ontable' => true , 
			'linkfield' => true , 
			'type' => 'string' , 
			'title' => 'Display Name'
		) , 
		'name' => array (
			'type' => 'alphanumeric' , 
			'title' => 'Group Name' , 
			'requirements' => array (
				'exists' => true , 
				'unique' => true
			)
		) , 
		'accessLevel' => array (
			'ontable' => true , 
			'type' => 'string' , 
			'title' => 'Privilages' , 
			'requirements' => array (
				'exists' => true
			)
		)
	);
}
?>