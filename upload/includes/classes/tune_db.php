<?php

/**
 * Class to handle Database objects and queries
 * Will Deal with multiple Connections
 * Master-slave for reading and writing from different servers
 * 
 * For Tune.pk
 * @author : Arslan Hassan <arslan@tune.pk>
 * @since : v3
 */



class tune_db
{

	//PDO for reading queries only
	var $db_read;
	//PDO for writing queries only
	var $db_write;


	//Last generated error
	var $last_error;

	//List all queries logged in this object
	var $query_list;
	var $last_query;

	//Insert Id
	var $insert_id;

	//Effected rows
	var $effected_rows;

	//Old variables
	var $_numOfRows;
	var $num_rows;

	var $total_queries;
	var $total_queries_sql;

	var $total_writes = 0;
	var $total_reads = 0;

	var $show_status = false;

	//Set this to true only if you want to 
	//Log weather Writes and Reads are 
	//Parsing and going to proper function or not
	var $detailed_loging = true;

	var $detailed_logs = array();

	//Show last query if mysql encouters an error
	var $show_last_query_on_error = false;

	var $enable_errors = false;

	var $admin_email = 'arslan@tune.pk';
	var $dev_emial = 'dev.notify@tune.pk';

	var $send_email_on_error = true;
		

	/**
	 * Connect to databases both Master and Slave
	 * @param Array $params array('write'=>array('host','user','pass','db'),'read'...)
	 */

	function connect($params)
	{
		//An array with
		
		$writeP = isset($params['write']) ? $params['write'] : array();
		$readP = isset($params['read']) ? $params['read'] : array();

		if(!isset($writeP['db']))
			$this->show_error('No database was seleacted :? ');

		$this->db_write = new mysqli($writeP['host'], $writeP['user'], $writeP['pass'], $writeP['db']);

		$this->db_write->options(MYSQLI_OPT_CONNECT_TIMEOUT,50);
		
		if($this->db_write->connect_error)
			$this->show_error("Couldn't connect to Write Server :-S");

		$this->db_read = new mysqli($readP['host'], $readP['user'], $readP['pass'], $readP['db']);
		$this->db_read->options(MYSQLI_OPT_CONNECT_TIMEOUT,50);

		if($this->db_read->connect_error)
			$this->show_error("Couldn't connect to Read Server :-S");

		$this->db_read->set_charset("utf8");
		$this->db_write->set_charset("utf8");
		
	}


	/**
	 * Fetch data from database, using read server
	 * 
	 * @param STRING $query;
	 * @return ARRAY $results
	 */
	function read($query)
	{
		if(!$query) return;

		//One more query
		$this->total_queries++;
		$this->total_queries_sql[] = $query;
		$this->total_reads++;

		$this->last_query = $query;
		$result = $this->db_read->query($query);

		if($this->detailed_loging)
		{
			$time_start = (float) microtime(true);

			$the_log = array(
				"msg" => "Reading Query",
				"query" => $query
			);
		}

		if($this->db_read->error)
		{
			$this->show_error($this->db_read->error);
			return false;
		}

		if($this->detailed_loging)
		{
			$time_end =  (float) microtime(true);
			$time_took = (float) $time_end - $time_start;

			$the_log['time_took'] = round($time_took,4).'s';

			$this->detailed_logs[] = $the_log;
		}

		$this->_numOfRows = $this->num_rows = $result->num_rows;

		for ($row_no = 0; $row_no < $result->num_rows; $row_no++) {
			$result->data_seek($row_no);
			$data[] = $result->fetch_assoc();
		}
	
	    $result->close(); 

	    $this->db_read->results = $data;
	    return $data;
	}

	/**
	 * Perform a write query in database
	 * Update/Delete/Insert
	 *
	 * @param STRING $query
	 */
	function write($query)
	{


		if(!$query) return;

		//One more query
		$this->total_queries++;
		$this->total_writes++;
		$this->total_queries_sql[] = $query;
		$this->last_query = $query;

		if($this->detailed_loging)
		{
			$time_start = microtime(true);

			$the_log = array(
				"msg" => "Writing Query",
				"query" => $query
			);
		}

		$this->db_write->query($query);

		if($this->detailed_loging)
		{
			$time_end =  microtime(true);
			$time_took = $time_end - $time_start;

			$the_log['time_took'] = round($time_took,4).'s';

			$this->detailed_logs[] = $the_log;
		}

		if($this->db_write->error)
		{
			$this->show_error($this->db_write->error);
		}

		if($this->db_write->affected_rows) $this->affected_rows = $this->db_write->affected_rows;
		if($this->db_write->insert_id) $this->insert_id = $this->db_write->insert_id;
	}

	/**
	 * Get insert_id from $db_write
	 *
	 * @return INT
	 */

	function insert_id()
	{
		if(isset($this->db_write->insert_id))
			return $this->db_write->insert_id;
	}



	/**
	 * Some crapy old functions to make this work from previuos
	 * Methods of ADODB-lite shit
	 *
	 * @param STRING $query
	 */
	function execute($query)
	{
		
		$query = ltrim($query); //Removing spaces if any from the left side
		if(strtolower(substr($query,0,6)) == "select")
		{
			$this->read($query);
		}
		else
		{
			$this->write($query);
		}
	}



	/**
	 * Another old function from the past, well thats sad but we have to write it anyway
	 * 
	 * @param STRING $query
	 */

	function _select($query)
    { 
        $results = $this->read($query);

        if($results) return $results;

        return false;
    }


    /**
     * Another old SHIT
	 * Function used to select data from table
	 * @param : table name
	 * @param : fields array
	 * @param : values array
	 * @params : Extra params
	 */
	function select($tbl,$fields='*',$cond=false,$limit=false,$order=false,$ep=false)
	{
		//return dbselect($tbl,$fields,$cond,$limit,$order);
		$query_params = '';
		//Making Condition possible
		if($cond)
		$where = " WHERE ";
		else
		$where = false;
		
		$query_params .= $where;
		if($where)
		{
			$query_params .= $cond;
		}
		
		if($order)
			$query_params .= " ORDER BY $order ";
		if($limit)
			$query_params .= " LIMIT $limit ";
			
		$query = " SELECT $fields FROM $tbl $query_params $ep ";
		// pr($query,true);
		
		return $this->_select($query);
	}	


	/**
	 * Some crappy old function of counting someting
	 *
	 * @param STRING $tbl Table name
	 * @param STRING $fields Fields, Seperated by comma
	 * @param MIXED $cond Query Condition
	 */

	function count($tbl,$fields='*',$cond=false)
	{


		global $db;
		if($cond)
			$condition = " Where $cond ";
		$query = "Select Count($fields) From $tbl $condition";
		
		$results = $this->_select($query);

		if($results)
		{
			foreach ($results as $result)
			{
				if(is_array($result))
				{
					foreach($result as $count)
						return $count;
				}
			}
		}

		return false;
	}


	/**
	 * A pseudo function to return data from ReadObject
	 */
	function getRows()
	{
		return $this->db_read->results;
	}


	/**
	* Function used to Update data in database
	* @param : table name
	* @param : fields array
	* @param : values array
	* @param : Condition params
	* @params : Extra params
	*/
	function update($tbl,$flds,$vls,$cond,$ep=NULL,$valid_func_keys=array())
	{
		$total_fields = count($flds);
		$count = 0;
		for($i=0;$i<$total_fields;$i++)
		{
			$count++;
			//$val = mysql_clean($vls[$i]);
			$val = ($vls[$i]);
			preg_match('/\|no_mc\|/',$val,$matches);
			//pr($matches);
			
			if($matches[0]!='')
				$val = preg_replace('/\|no_mc\|/','',$val);
			else
				$val = mysql_clean($val);
				
			//$needle = substr($val,0,3);

			$spec_vals = array(
				'{incr}','{decr}','{incrby','{decrby'
			);
			
			if(!in_array(substr($val,0,7),$spec_vals) || !in_array($flds[$i],$valid_func_keys))
				$fields_query .= $flds[$i]."='".$val."'";
			else
			{

				switch(substr($val,0,7))
				{
					case "{incr}":
						$fields_query .= $flds[$i]."=".$flds[$i]."+1";
					break;

					case "{decr}":
						$fields_query .= $flds[$i]."=".$flds[$i]."-1";
					break;

					case "{decrby";
						{
							preg_match('/\{decrby ([0-9]+)\}/i',$val,$matches);
							$decby = $matches[1];

							if($decby)
							{
								$fields_query .= $flds[$i]."=".$flds[$i]."-".$decby;
							}else
							{
								$fields_query .= $flds[$i]."='".$val."'";
							}
						}
					break;

					case "{incrby";
						{
							preg_match('/\{incrby ([0-9]+)\}/i',$val,$matches);
							$incrby = $matches[1];

							if($incrby)
							{
								$fields_query .= $flds[$i]."=".$flds[$i]."+".$incrby;
							}else
							{
								$fields_query .= $flds[$i]."='".$val."'";
							}
						}
					break;

					default:
					{
						$fields_query .= $flds[$i]."='".$val."'";
					}
				}

			}
			if($total_fields!=$count)
				$fields_query .= ',';
		}
		

		//Complete Query
		$query = "UPDATE $tbl SET $fields_query WHERE $cond $ep";

		/*//if(!mysql_query($query)) die($query.'<br>'.mysql_error());
		$this->total_queries++;
		$this->total_queries_sql[] = $query;
		$this->Execute($query);
		if(mysql_error()) die ($this->db_query.'<br>'.mysql_error());
		*/

		$this->write($query);

		return $query;
		
	}


	/**
	 * Function used to insert values in database
	 */
	function insert($tbl,$flds,$vls,$ep=NULL)
	{
		//dbInsert($tbl,$flds,$vls,$ep);
		$total_fields = count($flds);
		$count = 0;

		$fields_query = "";
		$values_query = "";

		foreach($flds as $field)
		{
			$count++;
			$fields_query .= $field;
			if($total_fields!=$count)
				$fields_query .= ',';
		}
		$total_values = count($vls);
		$count = 0;
		

		foreach($vls as $value)
		{
			
			
			preg_match('/\|no_mc\|/',$value,$matches);
			//pr($matches);
			if($matches[0]!='')
				$val = preg_replace('/\|no_mc\|/','',$value);
			else
				$val = mysql_clean($value);
			
			
			if($count>0)
				$values_query .= ",";

			
			$values_query .= "'".$val."'";


			$count++;

		}
		
		//Complete Query
		$query = "INSERT INTO $tbl ($fields_query) VALUES ($values_query) $ep";
		
		/*$this->total_queries_sql[] = $query;
		//if(!mysql_query($query)) die(mysql_error());
		$this->total_queries++;
		$this->Execute($query);
		
		if(mysql_error()) die ($this->db_query.'<br>'.mysql_error());*/

		$this->write($query);

		return $this->insert_id();
				
	}

	/**
	 * Function used to Delete data in database
	 * @param : table name
	 * @param : fields array
	 * @param : values array
	 * @params : Extra params
	*/
	function delete($tbl,$flds,$vls,$ep=NULL)
	{
		//dbDelete($tbl,$flds,$vls,$ep);
		
		
		global $db ;
		$total_fields = count($flds);
		$count = 0;
		for($i=0;$i<$total_fields;$i++)
		{
			$count++;
			$val = mysql_clean($vls[$i]);
			$needle = substr($val,0,3);
			if($needle != '|f|')
				$fields_query .= $flds[$i]."='".$val."'";
			else
			{
				$val = substr($val,3,strlen($val));
				$fields_query .= $flds[$i]."=".$val."";
			}
			if($total_fields!=$count)
				$fields_query .= ' AND ';
		}
		//Complete Query
		$query = "DELETE FROM $tbl WHERE $fields_query $ep";

		$this->write($query);
		
		/*//if(!mysql_query($query)) die(mysql_error());
		$this->total_queries++;
		$this->total_queries_sql[] = $query;
		$this->Execute($query);
		if(mysql_error()) die ($this->db_query.'<br>'.mysql_error());*/
		
 	}

 	/**
 	 * Clean input using mysqli_real_escap_string
 	 *
 	 * @param STRING $id
 	 */
 	function clean($id)
 	{

    	if (get_magic_quotes_gpc())
    	{
	        $id = stripslashes($id);
	    }

	    $id = htmlspecialchars($this->escape_string($id));

	    return $id;
 	}

 	/**
 	 * Escape string
 	 */
 	function escape_string($id)
 	{
 		
 		return $this->db_read->real_escape_string($id);
 	}

	/**
	 * Show DB and Queries status at the end of every page
	 * 
	 */
	function show_status()
	{
		if($this->show_status)
		{
			
			$array = array(
				'total_reads' => $this->total_reads,
				'total_writes' => $this->total_writes,
				'total_queries' => $this->total_queries,
				'query_list' => $this->total_queries_sql);

			if($this->detailed_loging)
			{
				$array['detailed'] = $this->detailed_logs;
			}

			pr($array,true);
		}
	}

	function affected_rows(){
		return $this->db_write->affected_rows;
	}


	/**
	 * Show some nice error
	 * 
	 * @param STRING $error
	 */
	function show_error($error)
	{
		$backtrace = debug_backtrace();
		$useful = array();
		foreach($backtrace as $back)
		{
			$good = array(
				'file' => $back['file'],
				'line' => $back['line'],
				'function' => $back['function'],
				'class' => $back['class'],
				'type' => $back['type'],
				'args' => $back['args']
			);

			$useful[] = $good;
		}

		$backtraceDetails = "";

		foreach($useful as  $msg)
		{
			if($backtraceDetails)
				$backtraceDetails .= "\r\n\r\n=======\r\n";

			foreach($msg as $key => $val)
				$backtraceDetails .= "\r\n".$key." : ".(is_array($val) ?  json_encode($val) : $val);
		}


		if($this->send_email_on_error && $this->admin_email)
		{
			$message = $error;
			$message .= "<br>Query<br>".$this->last_query;
			$message .= "<br>More Details<br>";
			$message .= nl2br($backtraceDetails);
						
			//mail ( $this->admin_email, 'Database Error', $message);

			if(function_exists('cbmail'))
			{

				cbmail(array(
					'to' => $this->admin_email,
					'subject' => "Database error on ".BASEURL,
					'content' => $message
				));
				cbmail(array(
					'to' => $this->dev_emial,
					'subject' => "Database error on ".BASEURL,
					'content' => $message
				));

			}
		}
		
		if($this->enable_errors)
		{
			echo '<html>';
				echo '<head>';
					echo '<title>DB Connection issue - Tune.pk</title>';
					echo '<style>';
						echo 'body{margin:0px; padding:15px; font-family:"Open Sans",Helvetica,sans-serif}';
						echo '.error_div{padding:14px; margin:14px; background-color:#e4eaee; 
							text-align:center;font-size:14px; font-weight:bold}';
					echo '</style>';
				echo '</head>';
				echo '<body>';
					echo '<div class="error_div">';
						echo '<h2>Wooops..</h2>';
						echo $error;

						if($this->last_query && $this->show_last_query_on_error);
						{
							echo '<br>';
							echo 'Query : ';
							echo $this->last_query;
						}
					echo '</div>';
				echo '</body>';
			echo '</html>';
			exit();
		}
		
	}

}


?>