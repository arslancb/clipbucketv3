<?php
/* 
 **************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 **************************************************************
*/

define( 'THIS_PAGE', 'manage_subscriptions' );

require 'includes/config.inc.php';
$userquery->logincheck();

/**
 * Add subscription orders
 */
add_object_manager_order( lang("A - Z"), " users.first_name ASC, users.username ASC", "manage_subscriptions" );
add_object_manager_order( lang("Lastest Subscription"), " subscriptions.date_added DESC", "manage_subscriptions" );
add_object_manager_order( lang("Lastest Activity"), " subscriptions.last_activity DESC", "manage_subscriptions" );

$userid = userid();
$user = get_user_details( $userid );

if ( $user ) {
    
    $confirm = get( 'confirm' );
    if ( $confirm == 1 ) {
        $subscribe = get( 'subscribe' );
        $unsubscribe = get('unsubscribe');
        
        if ( $subscribe || $unsubscribe ) {
            $sub_id = get('owner');
            $sub = get_user_details( $sub_id );

            if ( $sub ) {
                $body = lang('<div class=\"name-avatar\"><img src=\"'.$userquery->getUserThumb( $sub ).'\" class=\"avatar\"/><span class=\"name\">%s</div>');
                $url = queryString('confirm=2', array('confirm') );
                $is_subscribed = is_user_subscribed( $sub['userid'] );
                $continue = true;
                
                if ( $subscribe and !$is_subscribed ) {
                    $heading = sprintf( lang('Subscribe %s'), name( $sub ) );
                    $body = sprintf( $body, "Please confirm, you want to subscribe ".name( $sub ) );
                } else if ( $unsubscribe and $is_subscribed ) {
                    $heading = sprintf( lang('Unsubscribe %s'), name( $sub ) );
                    $body = sprintf( $body, "Please confirm, you want to unsubscribe <a href='".$userquery->profile_link( $sub )."'>".name( $sub ).'</a>' );
                } else {
                    $continue = false;
                }
                
                if ( $continue === true ) {
                    $javascript = '<script>$( document ).ready( function(){ cb_confirm( "'.$heading.'","'.$body.'",function(){ window.location="'.$url.'" }) } )</script>';
                    register_anchor( $javascript,'cb_head'); 
                }
            }    
        }
    }
    
    if ( $confirm == 2 ) {
        $subscribe = get( 'subscribe' );
        $unsubscribe = get('unsubscribe');
        
        if ( $subscribe || $unsubscribe )  {
            $sub_id = get('owner');
            $sub = get_user_details( $sub_id );

            if ( $sub ) {
                $is_subscribed = is_user_subscribed( $sub['userid'] );
                if ( $subscribe and !$is_subscribed ) {
                    subscribe_user( $sub_id );
                } else if ( $unsubscribe and $is_subscribed ) {
                   unsubscribe_user( $sub_id );
                }
                
                if ( !error() ) {
                    $success = true;
                }
                
                if ( $success ) {
                    redirect_to( BASEURL.'/manage_subscriptions.php');
                }
            }
        }
        
    }
    
    $order = return_object_order('manage_subscriptions');
    $subscriptions = get_user_subscriptions( $user['userid'], null, $order );
    assign( 'subscriptions', $subscriptions );
}

$file = "subscriptions/manage_subscriptions.html";

$global = ( file_exists( LAYOUT."/".$file ) ? false : STYLES_DIR."global" );
subtitle( lang('Manage Subscriptions') );
template_files( $file, $global );
display_it();
?>
