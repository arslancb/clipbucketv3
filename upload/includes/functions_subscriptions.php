<?php
$cb_subscriptions_content_limit = 15;
$cb_subscription_types = array();
$cb_subscriptions_tpl_files = array();
$cb_current_subscription = '';
$cb_subscription_action_links = array();
$cb_cache_user_subscriptions = array();


function init_subscriptions_js() {
    echo '<script type="text/javascript" src="'.JS_URL.'/functions_subscriptions.js"></script>';
    echo "\n";
    echo "<link href='".BASEURL."/styles/global/subscriptions/subscriptions.css' rel='stylesheet' type='text/css' />";
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
        'is_content_hidden', 'content_owner_id'
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
    global $cb_subscription_types;
    return $cb_subscription_types;
}

/**
 * Get subscription actions
 *  
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global array $cb_subscription_types
 * @return array
 */
function get_subscription_action_links() {
    global $cb_subscription_action_links;
    return $cb_subscription_action_links;
}

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
        
        foreach ( $types as $type_id => $name ) {
            $checked = " unchecked";
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
            
            $output .= '<li class="subscription subscription-type '.$type_id.'-subscription'.$checked.'"><a href="#'.$type_id.'" data-user="'.$userid.'" data-type="'.$type_id.'" '.( $subscribed_to ? " data-owner = '".$subscribed_to."' " : "").' ><span class="subscription-check'.( $checked_confirm ? " checked" : " unchecked" ).'"><i class="subscription-type-checked icon-ok"></i></span> '.$title.'</a></li>';
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
    
    $query = "SELECT subscription_id FROM ".tbl('subscriptions')." AS subscriptions ";
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
        
    if ( get_user_profile_field( $to_user['userid'], 'allow_subscription' ) == 'no' ) {
        return false;
    }
    
    $params['file'] = 'subscriptions/blocks/subscribe_buttons.html';
    $params['classes'] = 'subscription-button';
    $params['id'] = 'unsubscribe-'.$to_user['userid'].'-button';
    $params['text'] = lang('Unsubscribe');
    $params['subscribe_to'] = name( $to_user ) ;    
    //$params['attributes'] = " data-page-id = '".THIS_PAGE."' data-parent-page-id = '".PARENT_PAGE."' ";
    $params['attributes'] = "";
    
    if ( $subscription_id = is_user_subscribed( $to_user['userid'], $userid ) ) {
        $params['classes'] .= ' is-subscribed subscribed';
        $params['attributes'] .= " data-subscribe-button='true' data-is-subscribed='true' data-subscription-id='".$subscription_id['subscription_id']."' data-show-options='true' data-subscribed-name='".name( $to_user )."' data-subscribed-to = '".$to_user['userid']."' ";
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

function build_user_subscriptions_query ( $subscriptions, $type_id = null ) {
    global $cb_current_subscription;
    
    if ( is_null( $type_id ) ) {
        $type_id = $cb_current_subscription;
    }
    
    if ( $subscriptions )  {
        foreach ($subscriptions as $subscription )
        {
            $subscribed_content = json_decode( $subscription['subscribed_content'], true );

            if ( isset( $subscribed_content[ $type_id ] ) ) {
                $users[] = $subscription['userid'];
            }
        }
                
        return $users;
    }
    
    return false;
}

function add_new_subscription( $type, $titles ) {
    global $cb_subscription_types, $cb_current_subscription;
    
    if ( !$type or !$titles ) {
        $cb_current_subscription = false;
        return false;
    }
    
    if ( isset( $cb_subscription_types[ $type ] ) ) {
        $cb_current_subscription = false;
        return false;
    }
    
    $cb_subscription_types[ $type ] = $titles;
    $cb_current_subscription = $type;
    
    return true;
}

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
 * <em>For Example:</em>
 * add_new_subscription_type( 'video', lang('Video'), lang('Videos'), 'subscriptions/blocks/video.html' );
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param string $type Type of subscription. Should be same as <em>register_object</em> type
 * @param string $singular Singular string for type.
 * @param string $plural Plural string for type
 * @param string $tpl Path of template file for type block.
 * @return boolean
 */
function add_new_subscription_type( $type, $singular, $plural, $tpl ) {
    
    if ( !$type or !$singular or !$plural or !$tpl ) {
        return false;
    }
    
    $titles = array( $singular, $plural );
    
    if( add_new_subscription( $type, $titles ) ) {
        if ( add_subscription_template_file( $tpl ) ) {
            return true;
        }
    }
    
    return false;
}

/**
 * This function makes $userid subscribed to $to.
 * By default, $userid will be subscribed to all types content.
 * Later $userid can unsubscribe some content
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
       $content_types = array_keys( $content_types_list );
       
       if ( $content_types ) {
           foreach( $content_types as $type ) {
               $subscribed_content[ $type ] = array( 'new' => (int)0, 'email' => (int)0 );
           }
           
           $fields = array(
               'userid' => $userid,
               'subscribed_to' => $to_user['userid'],
               'subscribed_content' => json_encode( $subscribed_content ),
               'date_added' => NOW(),
               'time_added' => time()
           );
                      
           $insert_id = db_insert( tbl('subscriptions'), $fields );
           db_update( tbl('users'), array('total_subscriptions' => '{{total_subscriptions+1}}'), " userid = '".$userid."' " );
           db_update( tbl('users'), array('subscribers' => '{{subscribers+1}}'), " userid = '".$to_user['userid']."' " );
           
           e( sprintf( lang('usr_sub_msg'), name( $to_user ) ), 'm' );
           return $insert_id;
       }
   }
      
    return false;
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
                    'subscribed_to', 'subscribed_content',
                    'last_activity', 'new_activity', 'date_added AS subscription_added'
                ),
                'subscriptions_content' => get_subscription_content_fields(),
                'users' => get_user_fields()
            )
    );
    
    $query = "SELECT ".$fields." FROM ".cb_sql_table('subscriptions_content');
    $query .= " LEFT JOIN ".cb_sql_table('subscriptions')." ON subscriptions_content.subscription_id = subscriptions.subscription_id";
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
    global $userquery;
    
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
            $object = get_object( $content['content_type'], $content['content_id'] );
            $file_params = array( 'user' => $user );

            if( $object ) {
                $random = RandomString( 10 );
                $object['has_seen'] = $content['has_seen'];
                $file_params['object'] = $object;
                $file_params['file'] = $tpl_files[ $content['content_type'] ];
                $file_params['classes'] = "subscription-block clearfix ".$content['content_type']."-subscription".( $key == $first_index ? " subscription-first-block first-".$content['content_type']."-block" : "" ).( $key == $last_index ? " subscription-last-block last-".$content['content_type']."-block" : "" );
                $file_params['id'] = $content['content_owner_id']."-".$content['subscription_id']."-". $random ;
                $file_params['attributes'] = " data-user = '".$content['content_owner_id']."' data-username = '".$content['username']."' data-name='".name( $content )."' data-subscription = '".$content['subscription_id']."' data-id = '".$random."' ";
            }/* else {
                $file_params['file'] = 'subscriptions/blocks/no_item.html';
                $file_params['classes'] = "subscription-block clearfix object-not-found".( $key == $first_index ? " subscription-first-block " : "" ).( $key == $last_index ? " subscription-last-block" : "" );
            }*/
            
            $subscription_content .= fetch_template_file( $file_params );
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
    

//    if ( $user['total_subscriptions'] and $user['total_subscriptions'] > 0 ) {
//        return true;
//    }
    
    return true;
}

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

function subscription_md5( $toid, $userid = null ) {
    if ( is_null( $userid ) ) {
        $userid = userid();
    }
    
    $md5 = $userid."~".$toid;
    return md5( $md5 );
}
?>
