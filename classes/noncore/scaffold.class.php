<?php
/**
 * This file contains the scaffold class.
 * 
 * @package quickbox
 * @subpackage metaclass
 * @category classes
 * @version $Id$
 * @copyright 2008
 */
/**
 * Scaffold
 * 
 * Logical user interaction with metaclasses.
 * 
 * @uses metaclass to interact with metaobjects.
 * @uses form to provide a user interface.
 */
class scaffold
{
	/**
	 * Property definitions. Properties of properties.
	 *
	 * @var array
	 */
	private $definitions;
	/**
	 * Properties. Values of the current object's properties.
	 *
	 * @var array
	 */
	private $values;
	/**
	 * Stores whethere we're updating an existing object or adding a new one..
	 *
	 * @var string
	 */
	private $action;
	/**
	 * The database Id of the current object.
	 *
	 * @var string
	 */
	private $id;
	/**
	 * The class of the current object.
	 *
	 * @var string
	 */
	public $type;
	/**
	 * The key field of the current object.
	 *
	 * @var string
	 */
	public $keyField;
	/**
	 * Invalid fields and their reasons.
	 *
	 * @var array
	 */
	public $invalid;
	/**
	 * The base URL for wherever we are.(no query string)
	 *
	 * @var string
	 */
	public $urlbase;

	public function __construct ($class, $id = false)
	{
		metaclass::loadClass($class);
		$classVars = metaclass::getClassVars($class);
		$this->definitions = $classVars['propertyDefinitions'];
		$this->table = $classVars['table'];
		$this->type = $class;
		$this->title = $classVars['title'];
		$this->keyField = $classVars['keyField'];
		if (strlen(id) > 0)
		{
			$this->populate($id);
			$this->id = $id;
			$this->action = 'updating';
		} else
		{
			$this->action = 'creating';
		}
		$this->urlbase = explode('?', $_SERVER['REQUEST_URI']);
		$this->urlbase = $this->urlbase[0];
	}

	public function makeForm ()
	{
		$form = new form($_SERVER['REQUEST_URI'], 'post', 'scaffold', $this->invalid, $this->title, 
				($_POST['scaffoldSubmitted'] ? true : false));
		$form->addField('scaffoldSubmitted', 'hidden', 'true');
		foreach ($this->definitions as $k => $v)
		{
			if (! $v['internal'])
			{
				$form->addField($k, $v['type'], $this->values[$k], $v['title'], $this->definitions[$k]['linkfield'], 
						$this->definitions[$k]);
			}
		}
		return $form->create();
	}

	public function populate ($input)
	{
		if (! is_array($input))
		{
			$object = metaclass::load($this->type, $input);
			$input = $object->properties;
		}
		else
		{
			$input = janitor::unCleanData($input);
		}
		foreach ($this->definitions as $k => $v)
		{
			$this->values[$k] = $input[$k];
		}
	}

	public function delete ($id)
	{
		$object = metaclass::load($this->type, $id);
		$object->delete();
	}

	public function process ($input)
	{
		$this->populate(janitor::unCleanData($input));
		switch ($this->action)
		{
			case 'creating':
				$object = metaclass::create($this->type);
				break;
			case 'updating':
				$object = metaclass::load($this->type, $this->id);
				break;
		}
		foreach ($this->definitions as $k => $v)
		{
			$object->setProperty($k, $input[$k]);
		}
		if ($object->save())
		{
			return true;
		} else
		{
			foreach ($object->invalid as $k => $v)
			{
				foreach ($v as $y => $z)
				{
					$obVars = metaclass::getClassVars($this->type);
					$this->invalid[] = text::get('validation/error_' . $y, 
							array (
								$obVars['propertyDefinitions'][$k]['title']
							));
				}
			}
			return false;
		}
	}

	public function makeTable ($fields = false)
	{
		if (! $fields)
		{
			$fields[] = 'id';
			foreach ($this->definitions as $k => $v)
			{
				if ($v['ontable'])
				{
					$fields[] = $k;
				}
				if ($v['linkfield'])
				{
					$linkfield = $k;
				}
			}
		}
		$fields2['id'] = "ID";
		foreach ($fields as $v)
		{
			$fields2[$v] = $this->definitions[$v]['title'];
		}
		$fields2['delete'] = "";
		$table = new table($fields2, array (
			'class' => 'span-16'
		));
		$fieldsList = metaclass::getItems($this->table, $fields, $this->keyField);
		foreach ($fieldsList as $k => $v)
		{
			$v['delete'] = text::get('form/delete');
			$table->addRow($v, 
					array (
						$linkfield => array (
							'type' => 'link' , 
							'href' => $this->urlbase . '?id=' . $v['id']
						) , 
						'delete' => array (
							'type' => 'link' , 
							'href' => 'javascript:deleteitem(\'' . $v['id'] . '\',\'' . janitor::cleanData(
									$v['title']) . '\',\'' . $this->urlbase . '\')'
						)
					));
		}
		return $table->output();
	}

	public function getBaseUrl ()
	{
		return $this->baseurl;
	}
}
?>