<?php
/**
 * SuperMySQLi
 *
 * @copyright none
 * @license freely distributable
 */
class SuperMySQLi extends mysqli
{
	/** @var array */
	protected $separators = array(
		'SELECT' => ',',
		'FROM' => ',',
		'WHERE' => 'AND',
		'GROUP BY' => ',',
		'HAVING' => ',',
		'ORDER BY' => ',',
		'SET' => ',',
	);

	/** @var array */
	protected $command = array();

        public $ankit = 'demo test';

	/**
	 * Defines the columns to be returned.
	 *
	 * <code>
	 *  select(); // SELECT *
	 *  select("id", "name", "birthday"); // SELECT id , name , birthday
	 *  select(array("id", "user" => "name", "birthday")); // SELECT id , name AS user , birthday
	 * </code>
	 *
	 * @param string|array $fields
	 *
	 * @return SuperMySQLi
	 */
	public function select($fields = '*')
	{
		if (is_array($fields)) {
			$fields = $this->alias($fields);
		} else {
			$fields = func_get_args();
		}

		$this->addCommand('SELECT', $fields);

		return $this;
	}


	/**
	 * Updates data.
	 *
	 * @param string $table
	 * @param array $data array("column" => "value")
	 *
	 * @return SuperMySQLi
	 * @throws InvalidArgumentException
	 */
	public function update($table, $data)
	{
		if (!isset($table) || strlen($table) == 0) {
			throw new InvalidArgumentException('Enter the table.');
		}
		if (!isset($data) && count($data) == 0) {
			throw new InvalidArgumentException('Enter the data.');
		}

		$this->command['UPDATE'] = $table;
		$this->command['SET'] = $this->set($data);

		return $this;
	}


	/**
	 * Inserts data.
	 *
	 * @param string $table
	 * @param array $data array("column" => "value")
	 *
	 * @return SuperMySQLi
	 * @throws InvalidArgumentException
	 */
	public function insert($table, $data)
	{
            
		if (!isset($table) || strlen($table) == 0) {
			throw new InvalidArgumentException('Enter the table.');
		}
		if (!isset($data) || count($data) == 0) {
			throw new InvalidArgumentException('Enter the data.');
		}

		$this->command['INSERT INTO'] = $table;
		$res = $this->command['SET'] = $this->set($data);
		return $this;
	}


	/**
	 * Deletes data.
	 *
	 * @param string|array $table
	 *
	 * @return SuperMySQLi
	 * @throws InvalidArgumentException
	 */
	public function delete($table)
	{
		if (!isset($table[0])) {
			throw new InvalidArgumentException('Enter the table.');
		}

		$this->command['DELETE FROM'] = $table;

		return $this;
	}


	/**
	 * Defines the table in database.
	 *
	 * <code>
	 *  from("users"); // FROM users
	 *  from(array("u" => "users")); // FROM users AS u
	 * </code>
	 *
	 * @param string|array $table
	 *
	 * @return SuperMySQLi
	 * @throws InvalidArgumentException
	 */
	public function from($table)
	{
		if (!isset($table[0])) {
			throw new InvalidArgumentException('Enter the table.');
		}

		if (is_array($table)) {
			$table = $this->alias($table);
		}

		$this->addCommand('FROM', $table);

		return $this;
	}


	/**
	 * Defines restrictions result.
	 *
	 * <code>
	 *  where("id > 10"); // WHERE id > 10
	 *  where("id > 10")->where("id < 20"); // WHERE id > 10 AND id < 20
	 *  where(array("id > 10", "id < 20")); // WHERE id > 10 AND id < 20
	 * </code>
	 *
	 * @param string|array $restrictions
	 *
	 * @return SuperMySQLi
	 * @throws InvalidArgumentException
	 */
	public function where($restrictions)
	{
		if (!isset($restrictions[0])) {
			throw new InvalidArgumentException('Enter the conditions.');
		}

		$this->addCommand('WHERE', $restrictions);

		return $this;
	}


	/**
	 * Defines a grouping result.
	 *
	 * <code>
	 *  groupBy("birthday"); // GROUP BY birthday
	 *  groupBy("birthday", "city"); // GROUP BY birthday , city
	 *  groupBy(array("birthday", "city")); // GROUP BY birthday , city
	 * </code>
	 *
	 * @param string|array $by
	 *
	 * @return SuperMySQLi
	 * @throws InvalidArgumentException
	 */
	public function groupBy($by)
	{
		if (!isset($by[0])) {
			throw new InvalidArgumentException('Enter according you want to group.');
		}

		if (!is_array($by)) {
			$by = func_get_args();
		}

		$this->addCommand('GROUP BY', $by);

		return $this;
	}


	/**
	 * Defines a restriction over the groups.
	 *
	 * <code>
	 *  having("birthday < '2000-01-01'"); // HAVING birthday < '2000-01-01'
	 *  having("birthday < '2000-01-01'", "city = 'NY'"); // HAVING birthday < '2000-01-01' , city = 'NY'
	 *  having(array("birthday < '2000-01-01'", "city = 'NY'")); // HAVING birthday < '2000-01-01' , city = 'NY'
	 * </code>
	 *
	 * @param string|array $restrictions
	 *
	 * @return SuperMySQLi
	 * @throws InvalidArgumentException
	 */
	public function having($restrictions)
	{
		if (!isset($restrictions[0])) {
			throw new InvalidArgumentException('Enter the conditions.');
		}

		if (!is_array($restrictions)) {
			$restrictions = func_get_args();
		}

		$this->addCommand('HAVING', $restrictions);

		return $this;
	}


	/**
	 * Defines an ordering result.
	 *
	 * <code>
	 *  orderBy("birthday"); // ORDER BY birthday
	 *  orderBy("birthday ASC", "city DESC"); // ORDER BY birthday ASC , city DESC
	 *  orderBy(array("birthday ASC", "city DESC")); // ORDER BY birthday , city
	 * </code>
	 *
	 * @param string|array $by
	 *
	 * @return SuperMySQLi
	 * @throws InvalidArgumentException
	 */
	public function orderBy($by)
	{
		if (!isset($by)) {
			throw new InvalidArgumentException('Enter according you want to order.');
		}

		if (!is_array($by)) {
			$by = func_get_args();
		}

		$this->addCommand('ORDER BY', $by);

		return $this;
	}


	/**
	 * Defines the maximum number of rows.
	 *
	 * <code>
	 *  limit(2); // LIMIT 2
	 *  limit("2"); // LIMIT 2
	 * </code>
	 *
	 * @param int|string $limit
	 *
	 * @return SuperMySQLi
	 * @throws InvalidArgumentException
	 */
	public function limit($limit)
	{
		if (!is_numeric($limit)) {
			throw new InvalidArgumentException('Enter the number.');
		}

		$this->command['LIMIT'] = (string)$limit;

		return $this;
	}


	/**
	 * Defines the position of the first row.
	 *
	 * <code>
	 *  offset(10); // OFFSET 10
	 *  offset("10"); // OFFSET 10
	 * </code>
	 *
	 * @param int|string $offset
	 *
	 * @return SuperMySQLi
	 * @throws InvalidArgumentException
	 */
	public function offset($offset)
	{
		if (!is_numeric($offset)) {
			throw new InvalidArgumentException('Enter the number.');
		}

		$this->command['OFFSET'] = (string)$offset;

		return $this;
	}


	/**
	 * Executing SQL query.
	 * Clears the existing SQL.
	 *
	 * @return bool|mysqli_result
	 */
	public function execute()
	{
		$query = $this->export();
		$this->command = array();

		return $this->query($query);
	}


	/**
	 * Returns SQL.
	 *
	 * @return string
	 */
	public function getQuery()
	{
		return $this->export();
	}


	/**
	 * Creates aliases for the SQL.
	 *
	 * @param array $arr
	 *
	 * @return array
	 */
	protected function alias(array $arr)
	{
		$aliases = array();
		foreach ($arr as $name => $as) {
			if (is_int($name)) {
				$aliases[] = $as;
			} else {
				$aliases[] = $name . ' AS ' . $as;
			}
		}
		return $aliases;
	}


	/**
	 * Formatting parameters.
	 *
	 * @param array $arr
	 *
	 * @return array
	 * @throws InvalidArgumentException
	 */
	protected function set(array $arr)
	{
		$sets = array();
		foreach ($arr as $name => $value) {
			switch (gettype($value)) {
				case 'boolean':
				case 'integer':
				case 'double':
				case 'float':
					$sets[] = sprintf("%s=%d", $name, $value);
					break;
				case 'string':
					if (preg_match('~^:sql:~', $value)) {
						$sets[] = sprintf("%s=%s", $name, substr($value, 5));
					} else {
						$sets[] = sprintf("%s='%s'", $name, $value);
					}
					break;
				default:
					throw new InvalidArgumentException('Invalid type of value.');
			}
		}
		return $sets;
	}


	/**
	 * Adding command.
	 *
	 * @param string $name
	 * @param string|array $added
	 */
	protected function addCommand($name, $added)
	{
		if (!array_key_exists($name, $this->command)) {
			$this->command[$name] = array();
		}

		if (is_array($added)) {
			$this->command[$name] = array_merge($this->command[$name], $added);
		} else {
			$this->command[$name][] = (string)$added;
		}
	}


	/**
	 * Exports commands to the SQL string.
	 *
	 * @return string
	 */
	protected function export()
	{
		$query = array();
		foreach ($this->command as $statement => $arguments) {
			$query[] = $statement;

			if (is_array($arguments)) {
				$query[] = implode(' ' . $this->separators[$statement] . ' ', $arguments);
			} else {
				$query[] = $arguments;
			}
		}

		return implode(' ', $query);
	}
}
