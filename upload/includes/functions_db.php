<?php



/**
    * Function used to insert data in database
    * @param : table name
    * @param : fields array
    * @param : values array
    * @param : extra params
    */
    function dbInsert($tbl,$flds,$vls,$ep=NULL)
    {
        global $db ;
        $db->insert($tbl,$flds,$vls,$ep);
    }
    
    /**
    * Function used to Update data in database
    * @param : table name
    * @param : fields array
    * @param : values array
    * @param : Condition params
    * @params : Extra params
    */
    function dbUpdate($tbl,$flds,$vls,$cond,$ep=NULL)
    {
        global $db ;
        return $db->update($tbl,$flds,$vls,$cond,$ep);      
    }
    
    
    
    /**
    * Function used to Delete data in database
    * @param : table name
    * @param : fields array
    * @param : values array
    * @params : Extra params
    */
    function dbDelete($tbl,$flds,$vls,$ep=NULL)
    {
        global $db ;
        return $db->delete($tbl,$flds,$vls,$ep);        
    }
    
//Mysql Clean Queries
function sql_free($id) {
    if (!get_magic_quotes_gpc()) {
        $id = addslashes($id);
    }
    return $id;
}

function mysql_clean($id,$replacer=true){
        //$id = clean($id);
        global $db;
        if (get_magic_quotes_gpc())
        {
            $id = stripslashes($id);
        }
        $id = htmlspecialchars(mysqli_real_escape_string($db->mysqli,$id));
        if($replacer)
            $id = Replacer($id);
        return $id;
    }

function escape_gpc($in) {
    if (get_magic_quotes_gpc()) {
        $in = stripslashes($in);
    }
    return $in;
}

/**
 * functions related to database
 * 
 */
function db_select($query)
{
    global $db;
    return $db->_select($query);
}

function db_update($tbl, $fields, $cond)
{
    global $db;

    $count = 0;
    foreach ($fields as $field => $val) {

        if ($count > 0)
            $fields_query .= ',';


        $needle = substr($val, 0, 2);

        if ($needle != '{{')
            $value = "'" . filter_sql($val) . "'";
        else
        {
            $val = substr($val, 2, strlen($val) - 4);
            $value = filter_sql($val);
        }

        $fields_query .= $field . "=$value ";
        $count++;
    }

    //Complete Query
    $query = "UPDATE $tbl SET $fields_query WHERE $cond $ep";
    //if(!mysql_query($query)) die($query.'<br>'.mysql_error());
    //$db->total_queries++;
    //$db->total_queries_sql[] = $query;
    //$db->Execute($query);

    try
    {
        $db->mysqli->query($query);
    }
    catch(DB_Exception $e)
    {
        $e->getError();
    }

    return true;
}

function db_insert($tbl, $fields)
{
    global $db;

    $count = 0;

    $query_fields = array();
    $query_values = array();


    foreach ($fields as $field => $val) {

        $query_fields[] = $field;

        $needle = substr($val, 0, 2);

        if ($needle != '{{')
            $query_values[] = "'" . filter_sql($val) . "'";
        else
        {
            $val = substr($val, 2, strlen($val) - 4);
            $query_values[] = filter_sql($val);
        }

        $count++;
    }

    $fields_query = implode(',', $query_fields);
    $values_query = implode(',', $query_values);




    //Complete Query
    $query = "INSERT INTO $tbl ($fields_query) VALUES ($values_query) $ep";

    //if(!mysql_query($query)) die($query.'<br>'.mysql_error());
    $db->total_queries++;
    $db->total_queries_sql[] = $query;
    try
    {
        $db->mysqli->query($query);
    }
    catch(DB_Exception $e)
    {
        $e->getError();
    }

    return $db->insert_id();
}

function filter_sql($data)
{
    global $db;
    $data = mysqli_real_escape_string($db->mysqli, $data);
    return $data;
}

/**
 * Function used to count fields in mysql
 * @param TABLE NAME
 * @param Fields
 * @param condition
 */
function dbcount($tbl, $fields = '*', $cond = false)
{
   global $db;
    return $db->count($tbl,$fields,$cond);
}

function cb_query_id( $query ) {
    return md5( $query );
}


/**
 * Created by JetBrains PhpStorm.
 * User: Fawaz
 * Date: 8/26/13
 * Time: 3:51 PM
 * To change this template use File | Settings | File Templates.
 */


if( !function_exists( 'tbl' ) ) {

    function tbl($tbl)
    {
        global $DBNAME;
        $prefix = TABLE_PREFIX;
        $tbls = explode(",",$tbl);
        $new_tbls = "";
        foreach($tbls as $ntbl)
        {
            if(!empty($new_tbls))
                $new_tbls .= ",";
            $new_tbls .= "`".$DBNAME."`.".$prefix.$ntbl."";
        }

        return $new_tbls;
    }

}

/**
 * Format array into table fields
 *
 * @param $fields
 * @param bool $table
 * @return bool|string
 */
function table_fields( $fields, $table = false ) {
    $the_fields = '';

    if ( $fields ) {
        $array = $fields;
        foreach ($array as $key => $_fields)
        {

            if (is_array($_fields))
            {
                foreach ($_fields as $field)
                {
                    if ($the_fields)
                        $the_fields .=", ";
                    $the_fields .= $key . '.' . $field;
                }
            }else
            {
                $field = $_fields;

                if ($the_fields)
                    $the_fields .=", ";

                if ($tbl)
                    $the_tbl = tbl($tbl). '.' ;
                else
                    $the_tbl = '';

                $the_fields .= $the_tbl . $field;
            }
        }
    }

    return $the_fields ? $the_fields : false;
}

if( !function_exists( 'tbl_fields' ) ) {
    /**
     * Alias function for table_fields
     *
     * @param $fields
     * @param bool $table
     * @return bool|string
     */
    function tbl_fields( $fields, $table = false ) {
        return table_fields( $fields, $table );
    }
}

if ( !function_exists('cb_sql_table') ) {
    /**
     * Since we start using AS in our sql queries, it was getting
     * more and more difficult to know how author has defined
     * the table name. Using this, will confirm that table will be
     * defined AS it's name provided in $table.
     *
     * If author still wants to define table name differently, he
     * can provide it in $as
     *
     * @author Fawaz Tahir <fawaz.cb@gmail.com>
     * @param string $table
     * @param string $as
     * @return string $from_query
     */
    function cb_sql_table( $table, $as = null ) {
        if ( $table ) {
            $from_query = tbl( $table )." AS ".( ( !is_null( $as ) and is_string( $as ) ) ? $as : $table );
            return $from_query;
        }
        return false;
    }
}

if ( !function_exists( 'table' ) ) {
    function table( $table, $as = null ) {
        return cb_sql_table( $table, $as );
    }
}

/**
 * Alias function for method _select
 *
 * @param $query
 * @return mixed
 */
function cb_select( $query ) {
    global $db;
    return $db->_select( $query );
}

/**
 * Alias function for function cb_select
 *
 * @param $query
 * @return mixed
 */
function select( $query ) {
    
    return cb_select( $query );
}


function db_multi_insert($tbl, $multi_fields)
{
    global $db;

    $count = 0;

    if ($multi_fields)
    {

        foreach ($multi_fields as $fields)
        {
            $query_fields = array();
            $query_values = array();

            foreach ($fields as $field => $val)
            {

                $query_fields[] = $field;

                $needle = substr($val, 0, 2);

                if ($needle != '{{')
                    $query_values[] = "'" . filter_sql($val) . "'";
                else
                {
                    $val = substr($val, 2, strlen($val) - 4);
                    $query_values[] = filter_sql($val);
                }

                $count++;
            }

            $fields_query = implode(',', $query_fields);
            $values_query[] = '(' . implode(',', $query_values) . ')';
        }

        $values_query_multi = implode(',', $values_query);
    }




    //Complete Query
    $query = "INSERT INTO $tbl ($fields_query) VALUES $values_query_multi ";

    /*//if(!mysql_query($query)) die($query.'<br>'.mysql_error());
    $db->total_queries++;
    $db->total_queries_sql[] = $query;
    $db->Execute($query);

    if (mysql_error())
    {
        //if(LOG_DB_ERRORS)

        die($db->db_query . '<br>' . mysql_error());
    }*/

    $db->write($query);

    return $db->insert_id();
}

?>