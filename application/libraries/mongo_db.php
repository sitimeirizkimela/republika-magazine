<?php

class mongo_db {

	protected $CI;
	
	public $connection;
	public $db;
	public $cursor;
	
	private $select = array();
	private $where = array();
	private $limit = NULL;
	private $offset = NULL;
	private $sort = array();
	
	function __construct()
	{
		if(!class_exists('Mongo'))
		{
			show_error('It looks like the MongoDB PECL extension isn\'t installed or enabled', 500);
			return;
		}
		
		$this->CI =& get_instance();
		$this->connect();
	}
	
	private function connect()
	{
		$this->CI->config->load('mongodb');
		
		$hosts = $this->CI->config->item('mongo_host2');
		$db = $this->CI->config->item('mongo_db');
		$username = $this->CI->config->item('mongo_username');
		$password = $this->CI->config->item('mongo_password');
		
		if($hosts == "")
		{
			show_error('No host or port configured to connect to MongoDB', 500);
			return;	
		}
		
		$host = array();
		foreach($hosts as $ip => $port)
		{
			$host[] = $ip . (($port != '') ? ":". $port : '');
		}
		$host = implode(",", $host);
		
		if(!empty($db))
		{
			$this->db = $db;
		}
		else
		{
			show_error('No Mongo database selected', 500);
		}
		
		$auth = '';
		if($username !== "" && $password !== "")
		{
			$auth = "{$username}:{$password}@";
		}
		
		$connection_string = "mongodb://{$auth}{$host}/$db";
		
		$options = array('connect' => true);
		
		$replicaset = $this->CI->config->item('mongo_replicaset');
		if($replicaset != '') $options['replicaSet'] = $replicaset;

		// Make the connection
		try
		{
			$this->connection = new Mongo($connection_string);
		}
		catch(Exception $e)
		{
			show_error($e->getCode(). ' ' .$e->getMessage(), 500);
		}
		
		return $this;
	}
	
	function find($collection, $where = array(), $field = array())
	{
		$query = (is_array($where) && !empty($where)) ? $where : $this->where;
		$field = (is_array($field) && !empty($field)) ? $this->_validate_field($field) : $this->select;
		
		$this->cursor = $this->connection->{$this->db}->{$collection}->find($query, $field);
		
		if($this->sort !== NULL)
		{
			$this->cursor = $this->cursor->sort($this->sort);
		}
		
		if($this->limit !== NULL)
		{
			$this->cursor = $this->cursor->limit($this->limit);
		}
		
		if($this->offset !== NULL)
		{
			$this->cursor = $this->cursor->skip($this->offset);
		}
		$this->logg('find   - ' .$collection . '  - ' . json_encode($query));
		return $this->cursor;
	}
	
	function result()
	{
		if(empty($this->cursor) || !is_a($this->cursor, 'MongoCursor'))
			return array();
		
		$this->cursor->reset();
		$result = array();
		
		while($this->cursor->hasNext())
		{
			$result[] = $this->cursor->getNext();
		}
		
		return $result;
	}
	
	function get($collection)
	{
		$this->find($collection);
		return $this->result();
	}
	
	function findOne($collection, $where = array(), $field = array())
	{
		$query = (is_array($where) && !empty($where)) ? $where : $this->where;
		$field = (is_array($field) && !empty($field)) ? $this->_validate_field($field) : $this->select;
		$this->logg('findone   - ' .$collection . '  - ' . json_encode($query));
		
		
		return $this->connection->{$this->db}->{$collection}->findOne($query, $field);
	}
	
	function select($fields = array())
	{
		$fields = $this->_validate_field($fields);
		$this->select = array_merge($this->select, $fields);
		
		if(!empty($this->cursor) && is_a($this->cursor, 'MongoCursor'))
		{
			return $this->cursor->fields($this->select);
		}
		
		return this;
	}
	
	function where($where = array())
	{
		if(empty($where)) return;
		
		$this->where = array_merge($this->where, $where);
		
		return $this;
	}
	
	function order_by($what, $order = "ASC")
	{
		if($order == "ASC"){ $order = 1; }
		elseif($order == "DESC"){ $order = -1; }
		
		$this->sort[$what] = $order;
		
		if(!empty($this->cursor) && is_a($this->cursor, 'MongoCursor'))
		{
			return $this->cursor->sort($this->sort);
		}
		
		return $this;
	}
	
	function limit($limit = NULL, $offset = NULL)
	{
		if($limit !== NULL && is_numeric($limit) && $limit >= 1)
		{
			$this->limit = $limit;
		}
		
		if($offset !== NULL && is_numeric($offset) && $offset >= 1)
		{
			$this->offset = $offset;
		}
		
		if(!empty($this->cursor) && is_a($this->cursor, 'MongoCursor'))
		{
			$this->cursor->limit($this->limit)->skip($this->offset);
		}
		
		return $this;
	}
	function _clear(){
		return true;
	}
	function count($collection = "")
	{
		if($collection !== "")
		{
			$documents = $this->connection->{$this->db}->{$collection}->find($this->where);
			
			if($this->limit !== NULL)
			{
				$documents = $documents->limit($this->limit);
			}
			
			if($this->offset !== NULL)
			{
				$documents = $documents->skip($this->offset);
			}
			
			$this->_clear();
			return $documents->count();
		}
		elseif(!empty($this->cursor) && is_a($this->cursor, 'MongoCursor'))
		{
			return $this->cursor->count();
		}
		else
		{
			$this->_clear();
			show_error('No Mongo collection selected', 500);
		}
	}
	
	function insert($collection = "", $insert = array())
	{
		if($collection == "")
		{
			show_error("No Mongo collection selected to insert into", 500);
		}
		
		if(count($insert) == 0 || !is_array($insert))
		{
			show_error("Nothing to insert into Mongo collection or insert is not an array", 500);
		}
		$this->logg(' insert  - ' .$insert);
		$return = $this->connection->{$this->db}->{$collection}->insert($insert, true);
		
		
		return $insert['_id'];
	}
	
	function update($collection = "", $update = array(), $where = array(), $operation = '$set', $upsert = true, $multiple = true)
	{
		if($collection == "")
		{
			show_error("No Mongo collection selected to insert into", 500);
		}
		
		if(count($update) == 0 || !is_array($update))
		{
			show_error("Nothing to update in Mongo collection or update is not an array", 500);
		}
		
		if(empty($where))
			$where = $this->where;
		$this->logg('update   - ' .$collection .  json_encode($where));
				$update_result = $this->connection->{$this->db}->{$collection}->update($where, array($operation => $update), array('upsert' => $upsert, 'multiple'=> $multiple));
		$this->reset();
		return $update_result;
	}
	
	function delete($collection, $where = array())
	{
		if($collection == "")
		{
			show_error("No Mongo collection selected to insert into", 500);
		}
		
		if(empty($where))
			$where = $this->where;
			$this->logg('   - ' .$collection .  json_encode($where));
		return $this->connection->{$this->db}->{$collection}->remove($where);
	}
	
	function _validate_field($field = array())
	{
		if(is_array($field) && !empty($field))
		{
			foreach($field as $col => $value)
			{
				if(is_numeric($col))
				{
					$field[$value] = true;
				}
				else
				{
					if(!is_bool($value))
						$field[$col] = false;
				}
			}
			
			return $field;
		}
	}
	
	function reset()
	{
		$this->select = array();
		$this->where = array();
		$this->limit = NULL;
		$this->offset = NULL;
		$this->sort = array();
	}
	
	function logg($que)
	{
		return;
		$log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i:s a").PHP_EOL.
        "query: ".$que.PHP_EOL.
        "-------------------------".PHP_EOL;
		//Save string to log, use FILE_APPEND to append.
		file_put_contents('uploads/'.date("j.n.Y").'.txt', $log, FILE_APPEND);
	}
}
