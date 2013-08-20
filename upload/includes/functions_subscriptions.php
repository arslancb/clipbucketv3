<?php
$CB_SUBSCRIPTIONS = array();

$cb_subscriptions_content_limit = 15;
$cb_subscriptions_types = array();
$cb_subscriptions_tpl_files = array();
$cb_subscriptions_action_links = array();
$cb_subscriptions_section = array();
$cb_subscriptions_callback = array();
$cb_subscriptions_db_values = array();
$cb_current_subscription = '';
$cb_current_subscription_object = '';
$object_cache_time = 1800;

function init_subscriptions_js() {
    echo '<script type="text/javascript" src="'.JS_URL.'/functions_subscriptions.js"></script>';
    echo "\n";
    echo "<link href='".BASEURL."/styles/global/subscriptions/theme/subscriptions.css' rel='stylesheet' type='text/css' />";
    echo "\n";
}

/**
 * Gets fields of subscriptions table
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param array $extra
 * @return array
 */
function get_subscriptions_fields( $extra = null ) {
    $fields = array(
        'subscription_id', 'userid AS subscriber_id',
        'subscribed_to', 'date_added','type', 'subscribed_content',
        'last_activity', 'new_activity'
    );
    
    if ( is_null( $extra ) and is_array( $extra ) ) {
        $fields = array_merge( $fields, $extra );
    }
    
    return $fields;
}

/**
 * Get subscription content fields
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @return array
 */
function get_subscription_content_fields() {
    $fields = array(
        'subscription_content_id', 'subscription_id',
        'content_id', 'content_type','date_added', 'has_seen',
        'is_content_hidden', 'content_owner_id', 'content_cache_id'
    );
    
    if ( is_null( $extra ) and is_array( $extra ) ) {
        $fields = array_merge( $fields, $extra );
    }
    
    return $fields;
}

/**
 * Get subscription type template files path
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global array $cb_subscriptions_tpl_files
 * @return array
 */
function get_subscription_types_tpl_files() {
    global $cb_subscriptions_tpl_files;
    return $cb_subscriptions_tpl_files;
}

/**
 * Get subscription types
 *  
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global array $cb_subscription_types
 * @return array
 */
function get_subscription_types() {
    global $cb_subscriptions_types;
    return $cb_subscriptions_types;
}

/**
 * Get subscription actions
 *  
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global array $cb_subscription_action_links
 * @return array
 */
function get_subscription_action_links() {
    global $cb_subscriptions_action_links;
    return $cb_subscriptions_action_links;
}

/**
 * Get subscription sections
 *  
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global array $cb_subscription_sections
 * @return array
 */
function get_subscriptions_section() {
    global $cb_subscriptions_sections;
    return $cb_subscriptions_sections;
}

/**
 * Get subscription db values
 *  
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global array $cb_subscription_db_values
 * @return array
 */
function get_subscriptions_db_values() {
    global $cb_subscriptions_db_values;
    return $cb_subscriptions_db_values;
}

/**
 * Get subscription callback
 *  
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global array $cb_subscription_callback
 * @return array
 */
function get_subscriptions_callback() {
    global $cb_subscriptions_callback;
    return $cb_subscriptions_callback;
}

/**
 * This function checks if user is subscribed to specific
 * content
 * 
 * @param string $type_id
 * @param string|array $content
 * @return boolean
 */
function is_user_subscribed_to_content( $type_id, $content ) {
    if( is_string( $content ) ) {
        $content = json_decode( $content, true );
    }
    
    if ( $content and is_array( $content ) ) {
        if ( isset( $content[ $type_id ] ) ) {
            return true;
        }
    }
    
    return false;
}

/**
 * This shows the option lists of subscribed content on unsubscribe button
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param int $subscribed_to
 * @param int $userid
 * @return string|boolean
 */
function subscriptions_types_list( $subscribed_to = null, $userid = null ) {
    $types = get_subscription_types();
    
    if ( is_null( $userid ) ) {
        $userid = userid();
    }
    
    if ( $types ) {
        $output = '';
        
        if ( !is_null( $subscribed_to ) ) {
            $selected_content = get_user_subscribed_content( $subscribed_to, $userid );
        }
        $time = time();
        foreach ( $types as $type_id => $name ) {
            $checked = " unchecked";
            $checked_confirm = false;
            
            if ( $selected_content ) {
                if ( isset( $selected_content[ $type_id ] ) ) {
                    $checked = " checked";
                    $checked_confirm = true;
                }
            }
            
            $title = $name;
            
            if ( is_array( $name ) ) {
                $title = $name[1];
            }
            
            $output .= '<li class="subscription subscription-type '.$type_id.'-subscription'.$checked.'">';
            if ( $selected_content ) {
                $attributes = ' data-update="subscription-type" data-user="'.$userid.'" data-type="'.$type_id.'" '.( $subscribed_to ? " data-owner = '".$subscribed_to."' " : "");
                if ( $checked_confirm ) {
                    $attributes .= ' checked="checked" data-is-checked="true" ';
                } else {
                    $attributes .= ' data-is-checked="false" ';
                }
                $output .= '<input type="checkbox" class="checkbox-css3 subscription-type-check subscription-'.$type_id.'-check" '.$attributes.' id="'.$userid.'-'.$type_id.'-'.$time.'" />';
                $output .= '<label for="'.$userid.'-'.$type_id.'-'.$time.'"></label> ';
            }
            $output .= ' '.$title;
            $output .= '</li>';
        }
        
        return $output;
    }
    
    return false;
}

/**
 * Checks if you're subscribed to user or not
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param INT $to
 * @param INT $userid
 * @return boolean
 */
function is_user_subscribed( $to, $userid = null ) {
    if ( is_null( $userid ) ) {
        $userid = userid();
    }
    
    $query = "SELECT subscription_id, subscribed_content FROM ".tbl('subscriptions')." AS subscriptions ";
    $query .= " WHERE subscriptions.userid = '".$userid."' AND subscriptions.subscribed_to = '".$to."' ";
    
    $data = db_select( $query );
    if ( $data ) {
        return $data[0];
    } else {
        return false;
    }
}

/**
 * This function creates subscribe accordingly.
 * The HTML loads from <em>subscriptions/blocks/subscribe_buttons.html</em>.
 * Following things should be present in your custom styles. These are smarty variables:
 * {$classes} - This variables holds classes according to user subscription condition.
 * {$id} - This holds an ID
 * {$text} - This holds text either <em>Subscribe</em> or <em>Unsubscribe</em>
 * {$attributes} - This holds required attributes to make subscription process work. 
 * These should be used like:
 * <button class="{$classes} your-own-classes" id="{$id}" {$attributes}>{$text}</button>
 * 
 * {$is_subscribed} - This tells if you're subscribed to provided user or not
 * {$subscribed_to} - This holds the name of user you're about to subscribe/unsubscribe
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param INT $to
 * @param INT $userid
 * @return string
 */
function subscription_buttons( $to, $userid = null ) {
    
    if ( is_null( $userid ) ) {
        $userid = userid();
    }
    
    if ( !$userid ) {
        return false;
    }
    
    if ( is_array( $to ) ) {
        if ( isset( $to['userid'] ) and isset( $to['username'] ) ) {
            $to_user = $to;
        }
    } else if ( is_numeric( $to ) || is_string( $to ) ) {
        $to_user = get_user_details ( $to );
    }
    
    if ( !$to_user ) {
        return false;
    }
    
    if ( $to_user['userid' ] == $userid ) {
        // Since user cannot subscribe himself
        // return
        return false;
    }
    
    $subscription_id = is_user_subscribed( $to_user['userid'], $userid );
    $allowed_subscription = get_user_profile_field( $to_user['userid'], 'allow_subscription' );
    if ( $allowed_subscription == 'no' and !$subscription_id ) {
        return false;
    }
    
    $params['file'] = 'subscriptions/blocks/subscribe_buttons.html';
    $params['classes'] = 'subscription-button';
    $params['id'] = 'unsubscribe-'.$to_user['userid'].'-button';
    $params['text'] = lang('Unsubscribe');
    $params['subscribe_to'] = name( $to_user ) ;    
    //$params['attributes'] = " data-page-id = '".THIS_PAGE."' data-parent-page-id = '".PARENT_PAGE."' ";
    $params['attributes'] = "";
    
    if ( $subscription_id ) {
        $params['classes'] .= ' is-subscribed subscribed';
        $params['attributes'] .= " data-subscribe-button='true' data-is-subscribed='true' data-subscription-id='".$subscription_id['subscription_id']."' data-subscribed-name='".name( $to_user )."' data-subscribed-to = '".$to_user['userid']."' ";
        if ( $allowed_subscription == 'no' ) {
            $params['attributes'] .= " title = '".sprintf('%s has disabled subscription. If you unsubscribe now, you would not be able to subscribe again.', name( $to_user ) )."' rel='tooltip' ";
        }
        if ( THIS_PAGE != 'manage_subscriptions' ) {
            $params['attributes'] .= " data-show-options='true' ";
        }
        $params['is_subscribed'] = true;
    } else {
        $params['attributes'] .= " data-subscribe-button='true' data-is-subscribed='false' data-subscribe-name='".name( $to_user )."' data-do-initial-subscribe='true' data-do-popup='true' data-subscribe-to='".$to_user['userid']."' ";
        $params['classes'] .= ' do-subscription not-subscribed';
        $params['id'] = 'subscribe-'.$to_user['userid'].'-button';
        $params['text'] = lang('Subscribe');
        $params['is_subscribed'] = false;
    }
        
    return fetch_template_file( $params );
}

/**
 * Gets list of content you have subscribed to, of user
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global object $userquery
 * @param int $to
 * @param int $userid
 * @return array
 */
function get_user_subscribed_content( $to, $userid = null ) {
    global $userquery;
    
    if ( $to ) {
        if ( is_numeric( $to ) ) {
            $to_user = get_user_details( $to );
        }
        
        if ( is_array( $to ) and isset( $to['subscribed_content'] ) ) {
            return json_decode( $to['subscribed_content'], true );
        }
        
        if ( is_null( $userid ) ) {
            $userid = $userquery->udetails ? $userquery->udetails : userid();
        }

        if ( $userid ) {
            if ( is_numeric( $userid ) ) {
                $user = get_user_details( $userid );
            } else {
                $user = $userid;
            }
        }
        
        if ( $to_user and $user ) {
            $query = "SELECT subscriptions.subscribed_content FROM ".tbl('subscriptions')." AS subscriptions ";
            $query .= " WHERE subscriptions.userid = '".$user['userid']."' AND subscriptions.subscribed_to = '".$to_user['userid']."' ";
            
            $results = db_select( $query );
            if ( $results ) {
                return json_decode( $results[0]['subscribed_content'], true );
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    return false;
}

/**
 * Get subscriptions of current or provided user id
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global object $userquery
 * @param int $userid
 * @param int $limit
 * @param string $order
 * @param string $cond
 * @return boolean
 */
function get_user_subscriptions( $userid = null, $limit = null, $order = "subscriptions.date_added desc", $cond = null ) {
    global $userquery;
    if ( is_null( $userid ) ) {
        $userid = $userquery->udetails ? $userquery->udetails : userid();
    }
    
    if ( $userid ) {
        if ( is_numeric( $userid ) ) {
            $user = get_user_details( $userid );
        } else {
            $user = $userid;
        }
    }
    
    if ( !$user ) {
        return false;
    }
    
    $subscription_fields = get_subscriptions_fields();
    $user_fields = get_user_fields();
    
    $fields = tbl_fields(
        array(
            'subscriptions' => $subscription_fields,
            'users' => $user_fields
        )
    );
    
    $query = "SELECT $fields FROM ".cb_sql_table('subscriptions');
    $query .= " LEFT JOIN ".cb_sql_table('users')." ON subscriptions.subscribed_to = users.userid ";
    
    $query .= " WHERE subscriptions.userid = '".$user['userid']."' ";
    if ( !is_null( $cond ) ) {
        $query .= " AND ".$cond;
    }
    
    if ( $order ) {
        $query .= " ORDER BY ".$order;
    }
    
    if ( !is_null( $limit ) ) {
        $query .= " LIMIT ".$limit;
    }

    $results = db_select( $query );
    if ( $results ) {
        return $results;
    } else {
        return false;
    }
}

/**
 * Adds new subscription types and their titles.
 * 0 index is for singular and 1 index is for plural
 * 
 * @author Fawaz Tahir <fawaz.cb@gmai.com>
 * @global array $cb_subscriptions_types
 * @global string $cb_current_subscription
 * @param string $type
 * @param array $titles
 * @return boolean
 */
function add_new_subscription( $type, $titles ) {
    global $cb_subscriptions_types, $cb_current_subscription;
    
    if ( !$type or !$titles ) {
        $cb_current_subscription = false;
        return false;
    }
    
    if ( isset( $cb_subscriptions_types[ $type ] ) ) {
        $cb_current_subscription = false;
        return false;
    }
    
    $cb_subscriptions_types[ $type ] = $titles;
    $cb_current_subscription = $type;
    
    return true;
}

/**
 * This adds the template file for subscription type
 * 
 * @author Fawaz Tahir <fawaz.cb@gmai.com>
 * @global string $cb_current_subscription
 * @global array $cb_subscriptions_tpl_files
 * @param string $file
 * @return boolean
 */
function add_subscription_template_file( $file ) {
    global $cb_current_subscription, $cb_subscriptions_tpl_files;
    
    if ( $cb_current_subscription === false ) {
        return false;
    }
    
    $cb_subscriptions_tpl_files[ $cb_current_subscription ] = $file;
    
    return true;
}

/**
 * This adds a new subscription type in clipbucket. All parameters are required.
 * For Example:
 * <pre><code>add_new_subscription_type( 'video', lang('Video'), lang('Videos'), 'subscriptions/blocks/video.html' );</code></pre>
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param string $type Type of subscription. Should be same as <em>register_object</em> type
 * @param string $singular Singular string for type.
 * @param string $plural Plural string for type
 * @param string $tpl Path of template file for type block.
 * @return boolean
 */
function add_new_subscription_type( $type, $singular, $plural, $tpl = null ) {
    global $CB_SUBSCRIPTIONS;
    
    if ( !$type or !$singular or !$plural ) {
        return false;
    }
    
    $titles = array( $singular, $plural );
    
    if( add_new_subscription( $type, $titles ) ) {
        $CB_SUBSCRIPTIONS[ $type ] = array(
            'type' => $type,
            'titles' => $titles
        );
        if ( !is_null( $tpl ) and is_string( $tpl ) ) {
            if ( add_subscription_template_file( $tpl ) ) {
                $CB_SUBSCRIPTIONS[ $type ][ 'tpl' ] = $tpl;
            }
        }
        
        return $CB_SUBSCRIPTIONS;
    }
    
    return false;
}

/**
 * Adds callback function to get object details while displaying
 * the subscription content
 * 
 * @author Fawaz Tahir <fawaz.cb@gmai.com>
 * @global string $cb_current_subscription
 * @global array $CB_SUBSCRIPTIONS
 * @global array $cb_subscriptions_callback
 * @param string $callback
 * @return boolean
 */
function add_new_subscription_callback( $callback ) {
    global $cb_current_subscription, 
                 $CB_SUBSCRIPTIONS,
                 $cb_subscriptions_callback;
    
    if ( $cb_current_subscription === false ) {
        return false;
    }
    
    if ( !is_string( $callback ) or !function_exists( $callback ) ) {
        return false;
    }
    
    $cb_subscriptions_callback[ $cb_current_subscription ] = $callback;
    $CB_SUBSCRIPTIONS[ $cb_current_subscription ]['callback'] = $callback; 

    return $CB_SUBSCRIPTIONS;
}

/**
 * Adds section of current subscription
 * 
 * @author Fawaz Tahir <fawaz.cb@gmai.com>
 * @global string $cb_current_subscription
 * @global array $CB_SUBSCRIPTIONS
 * @global array $cb_subscriptions_section
 * @param type $section
 * @return boolean
 */
function add_new_subscription_section( $section ) {
    global $cb_current_subscription, 
                 $CB_SUBSCRIPTIONS,
                 $cb_subscriptions_section;
    
    if ( $cb_current_subscription === false ) {
        return false;
    }
    
    if ( !is_string( $section ) ) {
        return false;
    }
    
    $cb_subscriptions_section[ $cb_current_subscription ] = $section;
    $CB_SUBSCRIPTIONS[ $cb_current_subscription ][ 'section' ] = $section;
    
    return $CB_SUBSCRIPTIONS;
}

/**
 * Adds database values which are required for subscirption to work properly.
 * $table_name: Table name of subscription content, it should without prefix,
 * our code will do it. For example: <pre><code>video</code></pre>
 * 
 * $unique_id_column_name: This is the unique identifier for object. For Example
 * <pre><code>videoid</code></pre>
 * 
 * $date_column_name : By default, all the clipbucket's object date column name is
 * date_added which stores datetime. If your object someother, please provide it's
 * name
 * 
 * @author Fawaz Tahir <fawaz.cb@gmai.com>
 * @global string $cb_current_subscription
 * @global array $CB_SUBSCRIPTIONS
 * @global array $cb_subscriptions_db_values
 * @param string $table_name
 * @param string $unique_id_column_name
 * @param string $date_column_name
 * @return boolean
 */
function add_new_subscription_db_values( $table_name, $unique_id_column_name, $date_column_name = 'date_added' ) {
    global $cb_current_subscription, 
                 $CB_SUBSCRIPTIONS,
                 $cb_subscriptions_db_values;
    
    if ( $cb_current_subscription === false ) {
        return false;
    }
    
    if ( !is_string( $table_name ) or !is_string( $unique_id_column_name ) ) {
        return false;
    }
    
    $sql = array(
        'table'      => $table_name,
        'id'            => $unique_id_column_name,
        'date'       => $date_column_name
    );
    
    $cb_subscriptions_db_values[ $cb_current_subscription ] = $sql;
    $CB_SUBSCRIPTIONS[ $cb_current_subscription ][ 'sql' ] = $sql;
    
    return $CB_SUBSCRIPTIONS;
}

/**
 * This adds an extra condition which will be used when user subscribes
 * someone. For example:
 * <pre><code>add_new_subscription_condition(" video.status = 'Successful' and video.broadcast != 'unlisted' and video.broadcast != 'private' ");</code></pre>
 * It above example we are making sure that when user subscribes someone, only his successful and public videos gets added
 * 
 * @global string $cb_current_subscription
 * @global array $CB_SUBSCRIPTIONS
 * @global array $cb_subscriptions_db_values
 * @param string $condition
 * @return boolean
 */
function add_new_subscription_condition( $condition ) {
    global $cb_current_subscription, 
                 $CB_SUBSCRIPTIONS,
                 $cb_subscriptions_db_values;
    
    if ( $cb_current_subscription === false ) {
        return false;
    }
    
    if ( !is_string( $condition ) ) {
        return false;
    }
    
    $cb_subscriptions_db_values[ $cb_current_subscription ][ 'where' ] = $condition;
    $CB_SUBSCRIPTIONS[ $cb_current_subscription ][ 'sql' ][ 'where' ] = $condition;
    
    return $CB_SUBSCRIPTIONS;
}

/**
 * Returns the custom condition of subscription type. We also clean the values of condition
 * to avoid mySQL Injection
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param string $type
 * @return boolean
 */
function get_subscription_outer_condition( $type ) {
    $sql_values = get_subscriptions_db_values();
    $sql = $sql_values[ $type ];
    
    if ( $sql ) {
        $where = $sql['where'];
        // Before returning $where, clean it
        $where = clean_up_outer_condition( $where );
        return $where;
    }
    
    return false;
}

/**
 * This gets the initial objects when user first subscribes someone. Today's object will be
 * considered only and lastest 10 results.
 * 
 * @param string $type
 * @param int $userid
 * @return boolean
 */
function get_subscription_initial_objects( $type, $userid ) {
    $sql_values = get_subscriptions_db_values();
    $sql = $sql_values[ $type ];
    
    if ( !$sql ) {
        return false;
    }
    
    extract( $sql );
    
    /* Query */
    $fields = tbl_fields(
            array(
                $table => array(
                    $id, 'userid', $date
                )
            )
    );
    
    
    $query = " SELECT ".$fields." FROM ".cb_sql_table( $table );
    start_where();
    add_where("$table.userid = '$userid'");
    //add_where("curdate()=date($table.$date)");
    if ( $where ) {
        add_where( clean_up_outer_condition( $where ) );
    }
    $query .= " WHERE ".get_where();
    end_where();
    $query .= " ORDER BY ".$table.".".$date." DESC";
    $query .= " LIMIT 15";
    
    $results = db_select( $query );
    if ( $results ) {
        return $results;
    } else {
        return false;
    }
}

function add_subscription_content( $type, $results, $subscription_id ) {
    $sql_values = get_subscriptions_db_values();
    $callbacks = get_subscriptions_callback();
    
    $sql = $sql_values[ $type ];
    if ( !$sql ) {
        return false;
    }
    
    extract( $sql );
    
    if ( $results ) {
        foreach ( $results as $result ) {
            if ( isset( $callbacks[ $type ] ) ) {
                $content = $callbacks[ $type ]( $result[ $id ] );
            } else {
                $content = get_content( $type, $result[ $id ] );
            }            
            
            if ( !$content or !is_array( $content ) ) {
                continue;
            }
            
            $array['content_id'] = $result[ $id ];
            $array['content_type'] = $type;
            $array['content_owner_id'] = $result['userid'];
            $array['date_added'] = $result[ $date ];
            $array['time_added'] = strtotime( $result[ $date ] );
            $array['content_cache_id'] = add_object_cache( $result[ $id ], create_subscription_cache_type( $type ), ( is_array( $content ) ? json_encode( $content ) : $content ) );
            $array['subscription_id'] = $subscription_id;
            $fields[] = $array;
        }
        
        if ( $fields and count( $fields ) > 0 ) {
            $insert = db_multi_insert( tbl( 'subscriptions_content' ), $fields );
            if ( $insert ) {
                e( lang('Inital actions performed'), 'm' );
            }
        }
    }
}

function do_subscription_inital_actions( $subscription_id, $type, $userid ) {
    $results = get_subscription_initial_objects( $type, $userid );
    if ( $results ) {
        add_subscription_content( $type, $results, $subscription_id );
    }
}

/**
 * This makes a string compatible for regular expression
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param string $index
 * @return string
 */
function add_start_end_regex_pattren( $index ) {
    return "/".$index."/";
}

/**
 * This function cleans the outer condition given by user when adds new
 * subscrpition type. It is required to clean the values passed by user to
 * avoid mySQL Injection.
 * 
 * Since all sql values are wrapped in quotations, we'll extract values in
 * them, applies mysql_clean function and replaces them with new cleaned
 * values
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param string $where
 * @return string
 */
function clean_up_outer_condition( $where ) {
    preg_match_all( "/([\"\'])(.*?)\\1/", $where, $matches );
    
    if ( $matches ) {
        $before = $matches[2];
        $after = array_map( "mysql_clean", $matches[2] ); // Cleanup the values
        $before_pattern = array_map ( "add_start_end_regex_pattren", $before ); // Add delimeter for regex
        $where = preg_replace( $before_pattern, $after, $where ); // Replace old values with cleaned ones
    }
    
    return $where;
}



/**
 * This function makes $userid subscribed to $to.
 * By default, $userid will be subscribed to video or the first content type.
 * Later $userid can subscribe/unsubscribe some content
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param int $to
 * @param int $userid
 * @return boolean
 */
function subscribe_user( $to, $userid = null ) {
   if ( is_null( $userid ) ) {
       $userid = userid();
   }
   
   $to_user = get_user_details( $to );
   
   if ( !$to_user ) {
       e(lang('usr_exist_err'));
   } else if ( !$userid ) {
       e(sprintf( lang('please_login_subscribe'), name( $to_user ) ));
   } else if ( is_user_subscribed( $to, $userid ) ) {
       e(sprintf( lang("usr_sub_err"), name( $to_user ) ) );
   } else if ( $to_user['userid'] == $userid ) {
       e(lang("you_cant_sub_yourself"));
   } else if ( get_user_profile_field( $to_user['userid'], 'allow_subscription' ) == 'no' ) {
      e( sprintf( lang('%s has disabled subscriptions for %s channel'), name( $to_user ), ( $to_user['sex'] == 'male' ? 'his' : 'her' ) ) );
   } else {
       $content_types_list = get_subscription_types();
       
       if ( $content_types_list ) {
           
           // subscribe to first type of all types
           if ( isset( $content_types_list['video'] ) ) {
               $type = 'video';
           } else {
               $type = key( $content_types_list );
           }

           $subscribed_content[ $type ] = array( 'new' => (int)0, 'email' => (int)0 );
           
           $fields = array(
               'userid' => $userid,
               'subscribed_to' => $to_user['userid'],
               'subscribed_content' => json_encode( $subscribed_content ),
               'date_added' => NOW(),
               'time_added' => time()
           );
           
           $insert_id = db_insert( tbl('subscriptions'), $fields );
           if ( $insert_id ) {
                db_update( tbl('users'), array('total_subscriptions' => '{{total_subscriptions+1}}'), " userid = '".$userid."' " );
                db_update( tbl('users'), array('subscribers' => '{{subscribers+1}}'), " userid = '".$to_user['userid']."' " );
                
                do_subscription_inital_actions( $insert_id, $type, $to_user['userid'] );
                
                e( sprintf( lang('usr_sub_msg'), name( $to_user ) ), 'm' );
                return $insert_id;            
           }
       }
   }
      
    return false;
}

/**
 * This unsubscribes $userid from $to, removes all subscription content
 * 
 * @global object $db
 * @param int $to
 * @param int $userid
 */
function unsubscribe_user( $to, $userid = null ) {
    global $db;
   if ( is_null( $userid ) ) {
       $userid = userid();
   }
   
   $to_user = get_user_details( $to );
   
   if ( !$to_user ) {
       e(lang('usr_exist_err'));
   } else if ( $subscription = is_user_subscribed( $to, $userd ) ) {
        $query = "DELETE FROM ".tbl('subscriptions')." WHERE subscription_id = '".$subscription['subscription_id']."' ";
        $db->Execute( $query );
        $query = "DELETE FROM ".tbl('subscriptions_content')." WHERE subscription_id = '".$subscription['subscription_id']."' ";
        $db->Execute( $query );

        db_update( tbl('users'), array('total_subscriptions' => '{{total_subscriptions-1}}'), " userid = '".$userid."' " );
        db_update( tbl('users'), array('subscribers' => '{{subscribers-1}}'), " userid = '".$to."' " );
        
        e(lang("class_unsub_msg"), "m");
   } else {
       e( lang('Invalid subscription') );
   }
}

/**
 * Gets subscribed content from subscriptions_content table
 * of provided userid.
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global int $cb_subscriptions_content_limit
 * @param type $userid
 * @return boolean
 */
function get_user_subscriptions_content( $userid = null ) {
    global $cb_subscriptions_content_limit;
    
    if ( is_null( $userid ) ) {
        $userid = userid();
    }
    
    if ( !$userid ) {
        return false;
    }
    
    $fields = tbl_fields(
            array(
                    'subscriptions' => array(
                    'subscription_id', 'userid as subscriber_id',
                    'subscribed_to', 'subscribed_content'
                ),
                'subscriptions_content' => get_subscription_content_fields(),
                'users' => get_user_fields(),
                'objects_cache' => array(
                    'content', 'last_updated'
                )
            )
    );
    
    $query = "SELECT ".$fields." FROM ".cb_sql_table('subscriptions_content');
    $query .= " LEFT JOIN ".cb_sql_table('subscriptions')." ON subscriptions_content.subscription_id = subscriptions.subscription_id";
    $query .= " LEFT JOIN ".cb_sql_table('objects_cache')." ON subscriptions_content.content_cache_id = objects_cache.object_id ";
    $query .= " LEFT JOIN ".cb_sql_table('users')." ON subscriptions.userid = users.userid ";
    $subscriptions = get_user_subscriptions();
    $subs_query = build_user_subscriptions_query_string( $subscriptions );
    
    start_where();
    add_where(" subscriptions.userid = '".$userid."' ");
    add_where(" subscriptions_content.is_content_hidden = 'no' ");
    
    if ( $subs_query ) {
        add_where( $subs_query );
    }
    $query .= " WHERE ".get_where();
    end_where();
    
    $query .= " ORDER BY subscriptions_content.date_added DESC";
    $query .= " LIMIT ".$cb_subscriptions_content_limit;
    
    $results = db_select( $query );
    if ( $results ) {
        return $results;
    } else {
        return false;
    }
}

/**
 * This builds the user subscriptions's query. Query looks like following
 * ( subscriptions_content.content_owner_id = 'USERID' AND 
 *   ( subscriptions_content.content_type = 'CONTENT_TYPE' OR ...... ) 
 * )
 * 
 * Content type condition loops untill all types are included which 
 * user has subscribed.
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param type $subscriptions
 * @return string|boolean
 */
function build_user_subscriptions_query_string( $subscriptions ) {
    if ( $subscriptions ) {
        $query = "";
        foreach( $subscriptions as $subscription ) {
            $userid = $subscription['userid'];
            $content = array_keys( json_decode( $subscription['subscribed_content'], true ) );
            if( $query ) {
                $query .= " OR ";
            }
            
            $query .= "( ";
            $query .= " subscriptions_content.content_owner_id = '".$userid."' ";
            if ( $content ) {
                $query .= "AND (";
                $type_query = "";
                foreach( $content as $con ) {
                    if ( $type_query ) {
                        $type_query .= " OR ";
                    }
                    $type_query .= " subscriptions_content.content_type = '".$con."' ";
                }
                $query .= $type_query;
                $query .= ")";
            }
            $query .= " )";
        }
        return $query;
    }
    
    return false;
}

/**
 * This shows subscriptions content of provided userid or logged-in user.
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param int $userid
 * @return boolean
 */
function display_user_subscriptions_content( $userid = null ) {
    global $userquery, $cb_current_subscription_object;
    
    if ( is_null( $userid ) ) {
        $userid = $userquery->udetails ? $userquery->udetails : userid();
    }
    
    if( $userid ) {
        if ( is_numeric( $userid ) ) {
            $user = get_user_details( $userid );
        } else {
            $user = $userid;
        }
    }
    
    if ( !$user ) {
        return false;
    }
    
    if ( !has_subscriptions( $userid ) ) {
        return false;
    }
    
    $userid = $user['userid'];
    
    $subscriptions_content = get_user_subscriptions_content( $userid );
    if ( $subscriptions_content ) {
        $tpl_files = get_subscription_types_tpl_files();
        
        end( $subscriptions_content );
        $last_index = key( $subscriptions_content );

        reset( $subscriptions_content );
        $first_index = key( $subscriptions_content );
        
        $header_params = array(
            'file' => 'subscriptions/header.html',
            'user' => $user
        );
        
        $subscription_header = "<div class='subscriptions-header-block clearfix'>";
        $subscription_header .= fetch_template_file( $header_params );
        $subscription_header .= "</div>";
        
        $subscription_content .= "<div class='subscriptions-content-container clearfix'>";
        foreach( $subscriptions_content as $key => $content ) {
                        
            $object = do_subscription_object_content( $content );
            $file_params = array( 'user' => $user );

            if( $object ) {
                $random = RandomString( 10 );
                $object['has_seen'] = $content['has_seen'];
                $object['content_type'] = $content['content_type'];
                $object['content_id'] = $content['content_id'];
                
                $cb_current_subscription_object = $object;
                
                $file_params['object'] = $object;
                $file_params['file'] = ( $tpl_files[ $content['content_type'] ] ? $tpl_files[ $content['content_type'] ] : "subscriptions/blocks/content.html" ) ;
                $file_params['classes'] = "subscription-block clearfix ".$content['content_type']."-subscription".( $key == $first_index ? " subscription-first-block first-".$content['content_type']."-block" : "" ).( $key == $last_index ? " subscription-last-block last-".$content['content_type']."-block" : "" );
                $file_params['id'] = $content['content_owner_id']."-".$content['subscription_id']."-". $random ;
                $file_params['attributes'] = " data-user-id = '".$content['content_owner_id']."' data-username = '".$content['username']."' data-name='".name( $content )."' data-subscription = '".$content['subscription_id']."' data-id = '".$random."' ";
                $subscription_content .= fetch_template_file( $file_params );
            }
        }
        $subscription_content .= "</div>";
        
        $footer_params = array(
            'file' => 'subscriptions/footer.html',
            'classes' => '',
            'user' => $user
        );
        
        $subscription_footer = "<div class='subscriptions-footer-block clearfix'>";
        $subscription_footer .= fetch_template_file( $footer_params );
        $subscription_footer .= "</div>";
        
        return $subscription_header.$subscription_content.$subscription_footer;
    }
    
    return false;
}

/**
 * This gets the current content object displaying in loop
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global string $cb_current_subscription_object
 * @return string
 */
function get_current_subscription_object() {
    global $cb_current_subscription_object;
    return $cb_current_subscription_object;
}

/**
 * Checks if object has field or not
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param string $field
 * @return boolean
 */
function object_has( $field = 'title' ) {
    $current = get_current_subscription_object();
    
    if ( $current[ $field ] ) {
        return true;
    }
    
    return false;
}

/**
 * This function return the value of $filed if exists.
 * Applies two filters:
 * <pre>
 * <code>
 *  subscription_content_$field eg: subscription_content_link
 *  subscription_content_$content_type_$field : subscription_content_video_link
 * </code>
 * </pre>
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param string $field
 * @return mix $value
 */
function show_content_field( $field = 'title' ) {
    if ( object_has( $field ) ) {
        $current = get_current_subscription_object();
        $value = $current[ $field ];
        
        $value = apply_filters( $value, 'subscription_content_'.$field );
        $value = apply_filters( $value, 'subscription_content_'.$current['content_type'].'_'.$field );
        
        if ( $value ) {
            return $value;
        } else {
            return false;
        }
    }
    
    return false;
}

/**
 * This checks whether cached object time has expired or not.
 * If it's expired, first see the object a custom callback to get
 * content, if it exists, call that.
 * 
 * If not results are returned, try global <code>get_content</code>
 * function.
 * 
 * If object returns, update the last update column for cache content.
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param array $content
 * @return mix
 */
function do_subscription_object_content( $content ) {
    $callbacks = get_subscriptions_callback();
    $last_update = $content['last_updated'];
    if ( ( time() - $last_update ) > 1800 ) {        
        // First check if we have a custom callback for content
        if ( isset( $callbacks[ $content['content_type'] ] ) ) {
            $object = $callbacks[ $content['content_type'] ] ( $content['content_id'] );
        }
        
        if ( !$object ) {
            // Nothing found, do get_content
            $object = get_content( $content['content_type'], $content['content_id'] );
        }
        
        if ( !$object || !is_array( $object ) ) {
            return false;
        }
        
        db_update( tbl('objects_cache'), array( 'content' => json_encode( $object ), 'last_updated' => time() ), " object_id = '".$content['content_cache_id']."' " );
        return $object;
    } else {
        $object = json_decode( $content['content'], true );
        return $object;
    }
    
    return false;
}

/**
 * This function checks whether $userid or logged-in user has 
 * subscriptions or not.
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global object $userquery
 * @param int $userid
 * @return boolean
 */
function has_subscriptions( $userid = null ) {
    global $userquery;
    if ( is_null( $userid ) ) {
        $userid = $userquery->udetails ? $userquery->udetails : userid();
    }
    
    if ( $userid ) {
        if ( is_numeric( $userid ) ) {
            $user = get_user_details( $userid );
        } else {
            $user = $userid;
        }
    }
    
    if ( !$user ) {
        return false;
    }
    

    if ( $user['total_subscriptions'] and $user['total_subscriptions'] > 0 ) {
        return true;
    }
    
    return true;
}

/**
 * This display the list of subscriptions of user.
 * Uses "subscriptions/user.html" for output.
 * 
 * Following things are assigned:
 * {$classes} - List classes
 * {$attributes} - List attributes
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global object $userquery
 * @param int $userid
 * @return string
 */
function display_subscriptions_list( $userid = null ) {
    global $userquery;
    if ( is_null( $userid ) ) {
        $userid = $userquery->udetails ? $userquery->udetails : userid();
    }
    
    if ( $userid ) {
        if ( is_numeric( $userid ) ) {
            $user = get_user_details( $userid );
        } else {
            $user = $userid;
        }
    }
    
    if ( !$user ) {
        return false;
    }
    
    if ( !has_subscriptions( $user ) ) {
        return false;
    }
    
    $subscriptions = get_user_subscriptions( $userid );
    
    if ( $subscriptions ) {
        
        end( $subscriptions );
        $last_index = key( $subscriptions );

        reset( $subscriptions );
        $first_index = key( $subscriptions );
        
        foreach( $subscriptions as $key => $subscription ) {
            $params['file'] = 'subscriptions/user.html';
            $params['user'] = $subscription;
            $params['classes'] = 'subscription-user'.( $key == $first_index ? " subscription-user-first" : "" ).( $key == $last_index ? " subscription-user-last" : "" );
            $params['attributes'] = ' data-subscription-id="'.$subscription['subscription_id'].'" data-userid="'.$subscription['subscribed_to'].'" ';
            
            if ( is_active_subscription( $subscription ) ) {
                $params['classes'] .= " active active-subscription ";
            }
            
            $output .= fetch_template_file( $params );
        }
        
        return $output;
    } else {
        return false;
    }
}

/**
 * checks whether if current subscription is active
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param array $subscription
 * @return boolean
 */
function is_active_subscription( $subscription ) {
    $active = get('subscription');
    if ( $active ) {
        if ( $subscription['subscription_id'] == $active ) {
            return true;
        } else {
            return false;
        }
    }
    
    return false;
}

/**
 * This is used to display subscription type list in manage_susbcriptions.php
 * If $subscription is not provided, only type names will displayed wrapped in $wrapper
 * If provided, checkboxes will be created which you can use to subscribe or unsubscribe
 * to content
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param array $subscription
 * @param string $wrapper Default LI
 * @return string
 */
function manager_subscription_types( $subscription = null, $wrapper = null ) {
    if ( is_null( $wrapper ) ) {
        $wrapper = "li";
    }
    $types = get_subscription_types();
    
    if ( $types ) {
        foreach( $types as $type_id => $type ) {
            $output .= "<$wrapper class='subscription-type $wrapper-wrapper' data-type='$type_id'>";
            if ( is_null( $subscription ) ) {
                $output .= $type[1];
            } else {
                $check = is_user_subscribed_to_content( $type_id, $subscription['subscribed_content'] );
                $attributes = " data-user = '".$subscription['subscriber_id']."' data-type = '".$type_id."' data-owner = '".$subscription['userid']."' data-update='subscription-type' ";
                $output .= "<input class='subscription-type-check checkbox-css3 subscription-$type_id-check' type='checkbox' id='".$subscription['subscription_id']."-$type_id-".$subscription['userid']."' $attributes ".( $check ? "checked='checked' data-is-checked='true'" : "data-is-checked='false'")." />";
                $output .= "<label for='".$subscription['subscription_id']."-$type_id-".$subscription['userid']."'></label>";
            }
            $output .= "</$wrapper>";
        }
        return $output;       
    }
    
    return false;
}

/**
 * Shows subscriptions type list in manage_subscriptions.php
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param string $wrapper
 * @return string
 */
function display_subscription_types( $wrapper = null ) {
    return manager_subscription_types( null, $wrapper );
}

/**
 * Shows checkboxes for subscribing or unsubscribing content
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param array $subscription
 * @param string $wrapper
 * @return string
 */
function display_user_subscribed_content( $subscription, $wrapper = null ) {
    return manager_subscription_types( $subscription, $wrapper );
}

/**
 * Performs subscribing or unsubscribing actions for subscription content
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param type $type
 * @param type $owner
 * @param type $action
 * @param type $userid
 * @return boolean
 */
function do_user_content_subscriptions( $type, $owner, $action, $userid = null ) {
    $action = strtolower( $action );
    
    if ( !$action or ( $action != 'check' and $action != 'uncheck' ) ) {
        e( lang('Invalid action provided') );
        return false;
    }
    
    $types = get_subscription_types();
    
    if ( !$type or !isset( $types[ $type ] ) ) {
        e( lang('Invalid subscription type provided') );
        return false;
    }
    
    if ( $subscription = is_user_subscribed( $owner, $userid ) ) {
        $subscribed_content = $subscription['subscribed_content'];
        if ( $subscribed_content ) {
            $subscribed_content = json_decode( $subscription['subscribed_content'], true );
        }

        if ( $action == 'uncheck' ) {
            $total_subscribed_content = count( $subscribed_content );
            
            if ( $total_subscribed_content == 1 ) {
                $allowed_subs = get_user_profile_field( $owner, 'allow_subscription' );
                
                if ( $allowed_subs == 'no' ) {
                    $message = " title = '".sprintf('%s has disabled subscription. If you unsubscribe now, you would not be able to subscribe again.', lang('User') )."' rel='tooltip' ";
                }
                
                e( lang('You must at-least subscribe to 1 object. Please <a href="manage_subscriptions.php?unsubscribe='.$subscription['subscription_id'].'&owner='.$owner.'&confirm=1"'.$message.'>unsubscribe</a> user, if you are not intertested in anyone') );
                return false;
            }
            
            if ( $subscribed_content[ $type ] ) {
                unset( $subscribed_content[ $type ] );
            }
        } else if ( $action == 'check' ) {
            if ( !$subscribed_content[ $type ] ) {
                // User is first time subscrbing to $type, do the initial subscription thing
                if ( !was_user_subscribed_to_content( $subscription['subscription_id'], $type ) ) {
                    do_subscription_inital_actions( $subscription['subscription_id'], $type, $owner );
                }
                
                $subscribed_content[ $type ] = array(
                    'new' => ( int )0,
                    'email' => ( int )0
                );
            }
        }
        

        $fields = array(
            'subscribed_content' => json_encode( $subscribed_content )
        );
        
        $update = db_update( tbl('subscriptions'), $fields, " subscription_id = '".$subscription['subscription_id']."' " );
        
        if ( $update ) {
            e( lang('Subscription content updated' ), "m" );
        } else {
            e( lang('Unable to update subscription content') );
        }
    } else {
        e( lang('Invalid subscription') );
    }
    
}

/**
 * This confirms that whether user was previously subscribed to
 * content or not.
 * 
 * If user is subscribing for the first time, call <code>do_subscription_inital_actions</code>
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param int $sub_id
 * @param string $type
 * @return boolean
 */
function was_user_subscribed_to_content( $sub_id, $type ) {
    $query = " SELECT subscription_content_id FROM ".cb_sql_table('subscriptions_content');
    start_where();
    add_where(" subscriptions_content.subscription_id = '".$sub_id."' ");
    add_where(" subscriptions_content.content_type = '".$type."' ");
    $query .= " WHERE".get_where();
    end_where();
    $query .= " ORDER BY subscriptions_content.date_added DESC LIMIT 1";
    
    $result = db_select( $query );
    if ( $result ) {
        return true;
    } else {
        return false;
    }
}

/**
 * Because in database limit of cache object type charaters are upto 5
 * we'll constuction the subscription type in such a that it'll unique to 
 * subscription and is only five charaters.
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param string $type
 * @return string
 */
function create_subscription_cache_type( $type ) {
    $string = "subscription"; $string_len =  12;
    $type_len = strlen( $type );

    if ( $type_len < 5 ) {
        $remaining = 5 - $type_len;
        $return_type = $type.substr( $string, 0, $remaining);
    }
    
    if ( $type_len == 5 ) {
        $type_sub = substr( $type, 0, 2 );
        $return_type = $type_sub.substr( $string, 0, 3 );
    }
    
    if ( $type_len > 5 ) {
        $type_sub = substr( $type, 2, 2 );
        $return_type = $type_sub.substr( $string, 0, 3 );
    }
    
    return $return_type;
}

/* Default Callbacks */

/**
 * This function get photo details, setups an array for content.
 * This also serves as an example on how to use custom callback
 * for subscription type
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param type $id
 * @return array|boolean
 */
function get_subscription_photo( $id ) {
    global $cbphoto, $userquery;
    $photo = $cbphoto->get_photo( $id, true );
    if ( $photo ) {
        $fields = array( 'photo_description', 'photo_tags', 'collection_id', 'collection_name', 'views', 'file_directory', 'server_url', 'broadcast', 'date_added' );
        $fields = get_photo_fields( $fields );
        
        foreach( $fields as $field ) {
            if ( $field == 'photo_details' ) {
                continue;
            }
            $details[ $field ] = $photo[ $field ];
        }
        
        $details['title'] = $photo['photo_title'];
        $details['description'] = $photo['photo_descritpion'];
        $details['tags'] = tags( $photo['photo_tags'], "photos" );
        $details['heading'] = sprintf( lang('<a href="%s">%s</a> added a new photo in <a href="%s">%s</a>'), $userquery->profile_link( $photo ), name( $photo ), collection_links( $photo ), $photo['collection_name'] );
        $details['link'] = view_photo_link( $photo );
        $details['thumb'] = get_image_url( $photo, 'm' );
        
        return $details;
    }
        
    return false;
}
?>
