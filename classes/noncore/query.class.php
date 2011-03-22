<?php
/**
 * This contains the query class.
 * 
 * @author James Cleveland
 * @package quickbox
 * @subpackage database
 * @category classes
 * @copyright 2008
 */
/**
 * Query Maker
 *
 * SQL qeneration through fluent interfaces.
 * 
 * @todo Adding CREATE TABLE functionality.
 * @todo Adding DELETE functionality.
 * @todo (Long Term) Adding nested selections.
 */
class query
{
	private $whereClauses;
	private $insertValues;
	private $setStatements;
	private $queryType;
	private $header;
	private $footer;
	public $string;
	public $prefix;

	public function __toString ()
	{
		$this->string = $this->header;
		switch ($this->queryType)
		{
			case 'select':
				if (is_array($this->whereClauses))
				{
					foreach ($this->whereClauses as $k => $v)
					{
						$this->string .= ($v['method'] ? ' ' . strtoupper($v['method']) : ' WHERE ') . $this->backtickify(
						$v['field']) . $v['type'] . ' \'' . $v['value'] . '\'';
					}
				}
				break;
			case 'insert':
				foreach ($this->insertValues as $k => $v)
				{
					$values[] = '\'' . implode('\', \'', $v) . '\'';
				}
				$this->string .= ' VALUES (' . implode('),(', $values);
				$this->string .= ');';
				break;
			case 'update':
				foreach ($this->setStatements as $k => $v)
				{
					$statements[] = '`' . $k . '` = \'' . $v . '\'';
				}
				$this->string .= implode(', ', $statements);
				foreach ($this->whereClauses as $k => $v)
				{
					$this->string .= ($v['method'] ? ' ' . strtoupper($v['method']) : ' WHERE') . ' `' . $v['field'] .
					 '` ' . $v['type'] . ' \'' . $v['value'] . '\'';
				}
				break;
		}
		$this->string .= $this->footer;
		return $this->string;
	}

	/**
	 * database::select()
	 *
	 * @return mixed SELECT
	 */
	public function select ($input = false)
	{
		$this->queryType = 'select';
		if (! $input)
		{
			$input = array (
				'*'
			);
		} else
		{
			if (! is_array($input))
			{
				$input = array (
					$input
				);
			}
			foreach ($input as $k => $v)
			{
				if (! is_int($k))
				{
					$input[$k] = $this->backtickify($k) . ' as ' . '`' . $v . '`';
				} else
				{
					if (strpos($v, '*') !== false)
					{
						$tmp = explode('.', $input[$k]);
						$input[$k] = '`' . $tmp[0] . '`.*';
					} else
					{
						$input[$k] = $this->backtickify($v);
					}
				}
			}
		}
		$this->header .= 'SELECT ' . implode(', ', $input);
		return $this;
	}

	public function insert ($table, $fields)
	{
		$this->queryType = 'insert';
		$this->header .= 'INSERT INTO `' . $table . '` (`' . implode('`, `', $fields) . '`)';
		return $this;
	}

	public function update ($table)
	{
		$this->queryType = 'update';
		$this->header = 'UPDATE ' . $this->backtickify($table) . ' SET  ';
		return $this;
	}

	public function limit ($limit, $start = false)
	{
		$this->footer .= ' LIMIT ' . ($start ? $start . ', ' : null) . $limit;
		return $this;
	}

	public function order ($input, $order = 'ASC')
	{
		if ($input)
		{
			if (! is_array($input))
			{
				$input = array (
					$input
				);
			}
			$this->footer .= ' ORDER BY `' . implode('`, `', $input) . '` ' . strtoupper($order);
		}
		return $this;
	}

	public function set ($statements)
	{
		$this->setStatements = $statements;
		return $this;
	}

	public function values ($values)
	{
		$this->insertValues[] = $values;
		return $this;
	}

	public function from ($input)
	{
		if (! is_array($input))
		{
			$input = array (
				$input
			);
		}
		foreach ($input as $k => $v)
		{
			$input[$k] = $this->backtickify($v);
		}
		$this->header .= ' FROM ' . implode(',', $input);
		return $this;
	}

	public function where ($field, $value, $type = '=', $method = false)
	{
		$this->addWhereClause($field, $value, $type, $method);
		return $this;
	}

	public function joinLeft ($table, $first, $second)
	{
		$this->header .= 'LEFT JOIN `' . $table . '` ON ' . $this->backtickify($first) . ' = `' . $table . '`.`' . $second .
		 '` ';
		return $this;
	}

	private function backtickify ($string)
	{
		return '`' . str_replace('.', '`.`', $string) . '`';
	}

	private function addWhereClause ($field, $value, $type, $method)
	{
		$this->whereClauses[] = array (
			'field' => $field , 
			'value' => $value , 
			'type' => $type , 
			'method' => $method
		);
	}
}
?>