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
        $_POST['output'] = subscription_buttons( post('to') );
        echo json_encode( $_POST );
    }
    break;
    
    case "unsubscribe": {
        $_POST['output'] = subscription_buttons( post('to') );
        echo json_encode( $_POST );
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
        echo json_encode( $_POST );
    }
    break;
    
    default:
        exit(json_encode(array('err' => lang('Invalid request'))));
}
?>