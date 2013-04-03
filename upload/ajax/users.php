<?php

/**
 * All ajax requests that are related users or its object will be listed here
 *  
 * @author Arslan Hassan
 * @license AAL
 * @since 3.0 
 */
include('../includes/config.inc.php');

//Getting mode..
$mode = post('mode');
if (!$mode)
    $mode = get('mode');

$mode = mysql_clean($mode);


switch ($mode) {
    case "subscribe": {
        $to = post( 'to' );
        $insert_id = subscribe_user( $to );
        
        if ( error() ) {
            echo json_encode( array( 'error' => error() ) );
        }
        
        if ( msg() ) {
            $output = subscription_buttons( $to );
            echo json_encode( array( 'success' => msg(), 'output' => $output ) );
        }
    }
    break;
    
    case "unsubscribe": {
        $to = post('to');
        unsubscribe_user( $to );
        
        if ( error() ) {
            echo json_encode( array( 'error' => error() ) );
        }
        
        if ( msg() ) {
            $output = subscription_buttons( $to );
            echo json_encode( array( 'success' => msg(), 'output' => $output ) );
        }
    }
    break;

    case "subscription_options": {
        $position = post('position') ? post('position') : "bottom";        
        
        $list = subscriptions_types_list( post('user') );
        
        $params['file'] = ( 'subscriptions/options_list.html' );
        $params['classes'] = 'subscriptions-options options-list '.post('id').' option-position-'.$position;
        $params['id'] = post('user').'-'.post('subscription').'-list';
        $params['subscribed_content_list'] = $list;
        $params['name'] = post('name');
        
        $output = fetch_template_file( $params );
        
        $return['output'] = $output;
        $return['success'] = true;
        $return['position'] = $position;
        
        echo json_encode( $return );
        
    }
    break;
    
    case "update_subscription_option": {
        
        do_user_content_subscriptions( post('type'), post('owner'), post('action'), post('user') );
                
        if ( error() ) {
            echo json_encode( array( 'error' => error() ) );
        }
        
        if ( msg() ) {
            echo json_encode( array( 'msg' => msg() ) );
        }
    }
    break;
    
    default:
        exit(json_encode(array('err' => lang('Invalid request'))));
}
?>