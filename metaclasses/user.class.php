<?php
/**
 * Contains user class.
 *
 * @package quickbox
 * @subpackage metaclass
 * @category metaclasses
 * 
 */
/**
 * User
 * 
 * A user!
 *
 */
class user extends metaclass
{
	/**
	 * Constructor
	 * @access protected
	 */
	/* Metaclass Property Setup */
	public static $title = "User";
	public static $table = "userUsers";
	public static $propertyDefinitions = array (
		'username' => array (
			'ontable' => true , 
			'linkfield' => true , 
			'type' => 'alphanumeric' , 
			'title' => 'Username' , 
			'requirements' => array (
				'exists' => true , 
				'unique' => true
			)
		) , 
		'displayname' => array (
			'type' => 'string' , 
			'title' => 'Display Name' , 
			'ontable' => true , 
			'requirements' => array (
				'exists' => true
			)
		) , 
		'registrationDate' => array (
			'type' => 'date' , 
			'internal' => 'true' , 
			'ontable' => 'true' , 
			'title' => 'Registration Date'
		) , 
		'password' => array (
			'type' => 'password' , 
			'title' => 'Password' , 
			'requirements' => array (
				'exists' => true
			)
		) , 
		'salt' => array (
			'type' => 'string' , 
			'internal' => true
		) , 
		'password_check' => array (
			'internal' => true , 
			'type' => 'string'
		) , 
		'email' => array (
			'type' => 'email' , 
			'title' => 'E-mail' , 
			'requirements' => array (
				'exists' => true , 
				'unique' => true
			)
		) , 
		'group' => array (
			'type' => 'foreign' , 
			'title' => 'Group' , 
			'requirements' => array (
				'real' => 'group'
			)
		) , 
		'userVars' => array (
			'type' => 'textblock' , 
			'title' => 'User Variables'
		)
	);

	public function save ()
	{
		# Set a registration date if one doesn't exist (ie, a new user).
		if ($this->getProperty('registrationDate', true) == null)
		{
			$this->setProperty('registrationDate', date('Y-m-d'));
		} else
		{
			$this->setProperty('registrationDate', $this->getProperty('registrationDate', true));
		}
		# Do not change password if password boxes are empty.
		if ($this->getProperty('password') == null)
		{
			$this->setProperty('password', $this->getProperty('password', true));
			$this->setProperty('password_check', $this->getProperty('password', true));
			$this->setProperty('salt', $this->getProperty('salt', true));
		}
		# Only change password if different from old password.
		if ($this->originalProperties['password'] != $this->properties['password'])
		{
			$newPass = janitor::passwd($this->getProperty('password'));
			$newPassCheck = janitor::passwd($this->getProperty('password_check'), $newPass['salt']);
			$this->setProperty('password', $newPass['passwd']);
			$this->setProperty('password_check', $newPassCheck['passwd']);
			$this->setProperty('salt', $newPass['salt']);
		}
		$this->saveObject();
	}
}
?>