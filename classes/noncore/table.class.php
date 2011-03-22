<?php
/**
 * table.class.php
 *
 * Make this better
 *
 * @version $Id$
 * @copyright 2008
 */
class table
{
	private $columns;
	private $header;
	private $footer;
	private $rowNum;
	private $rows;

	public function __construct ($columns, $args)
	{
		$this->columns = $columns;
		$this->rowNum = 0;
		$this->header .= '<table' . ($args['class'] ? ' class="' . $args['class'] . '"' : null) . '><tr>';
		foreach ($columns as $k => $v)
		{
			$this->header .= '<th>' . $v . '</th>';
		}
		$this->header .= '</tr>' . "\n";
		$this->footer .= '</table>';
	}

	public function output ()
	{
		return $this->header . $this->rows . $this->footer;
	}

	public function addRow ($fields, $args = false)
	{
		$this->rows .= '<tr>';
		$this->rows .= "\n";
		foreach ($this->columns as $k => $v)
		{
			$this->rows .= '<td>';
			switch ($args[$k]['type'])
			{
				case 'link':
					$this->rows .= '<a href="' . $args[$k]['href'] . '">' . $fields[$k] . '</a>';
					break;
				default:
					$this->rows .= $fields[$k];
					break;
			}
			$this->rows .= '</td>';
		}
		$this->rows .= "\n";
		$this->rows .= '</tr>';
		$this->rows .= "\n";
		$this->rowNum ++;
	}
}
?>