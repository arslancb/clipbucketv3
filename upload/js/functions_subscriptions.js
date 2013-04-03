    
    var  __subscription_options_timeout = 1500,
            __subscription_options_timeout_counter;

/**
 * Subscribe user
 */
function subscribe_user( button ) {
    var data = {};
    
    if ( button.hasClass('disabled') ) {
        return false;
    }
    
    data['to'] = button.data('subscribe-to');
    data['id'] = button.attr('id');
    data['mode'] = 'subscribe';
    data['page'] = button.data('page-id');
    data['parent'] = button.data('parent-page-id');
    data['name'] = button.data('subscribe-name');
    
    button.attr( 'disabled', 'disabled' );
    button.addClass('disabled');
        
     amplify.request('users', data,function( d ) {
         
         if ( d.error ) {
            button.removeAttr( 'disabled' );
            button.removeClass('disabled');
            displayError( d.error );
         }
         
         if ( d.success ) {
             $( '#'+data['id'] ).after( d.output );
             $( '#'+data['id'] ).remove();
             
            attach_subscription_events( $('#unsubscribe-'+data['to']+'-button') );
            attach_subscription_options_list( $('#unsubscribe-'+data['to']+'-button') );
         }
     });
}

/**
 * Unsubscribe user
 */
function unsubscribe_user( btn ) {
    var data = {};
    
    data['subscription'] = btn.data('subscription-id');
    data['to'] = btn.data('subscribed-to');
    data['id'] = btn.attr('id');
    data['mode'] = 'unsubscribe';
    data['page'] = btn.data('page-id');
    data['parent'] = btn.data('parent-page-id');
    
    btn.attr( 'disabled', 'disabled' );
    btn.addClass('disabled');
    
    if ( $('#'+data['to']+'-'+data['subscription']+'-list') ) {
       $('#'+data['to']+'-'+data['subscription']+'-list').remove();
    }
    
     amplify.request('users', data,function( d ) {
         if ( d.error ) {
             btn.removeAttr( 'disabled' );
            btn.removeClass('disabled');
            displayError( d.error );
         }
         
         if ( d.output ) {            
            $( '#'+data['id'] ).after( d.output );
            $( '#'+data['id'] ).remove();
            attach_subscription_events( $('#subscribe-'+data['to']+'-button') );
         }
     });
}

/**
 * Attaches subscribe/unsubscribe events
 */
function attach_subscription_events( __subscribe_button ) {
    __subscribe_button.on( 'click', function( event ) {
        // Prevent Default Action
        event.preventDefault();

        if ( __subscribe_button.data('is-subscribed') == true ) {
            
            if ( __subscription_options_timeout_counter ) {
                clearTimeout( __subscription_options_timeout_counter );
            }
            
            unsubscribe_user( $( this ) );
        } else {
            subscribe_user( $( this ) )
        }

    });
}

/**
 * Attaches types of subscription events
 */
function attach_subscription_options_list( __subscribe_button ) {
    
    //alert( __subscribe_button.data('show-options') );
    
    if ( __subscribe_button.data('is-subscribed') != true || __subscribe_button.data('show-options') == undefined ) {
        return;
    }
            
        __subscribe_button.on({
            'mouseenter' : function( event ) {
                __subscription_options_timeout_counter = setTimeout( function() {
                        
                        if ( $('#'+__subscribe_button.data('subscribed-to')+'-'+__subscribe_button.data('subscription-id')+'-list').css('display') == 'none' ) {
                            $('.subscriptions-options').hide();
                            __subscribe_button.addClass('subscription-options-opened');
                            $('#'+__subscribe_button.data('subscribed-to')+'-'+__subscribe_button.data('subscription-id')+'-list').show();
                            return;
                        }
                        
                        __subscribe_button.addClass('loading-subscription-options');
                        
                        var data = {};
                        
                        data['user'] = __subscribe_button.data('subscribed-to');
                        data['page'] = __subscribe_button.data('page-id');
                        data['parent'] = __subscribe_button.data('parent-page-id');
                        data['subscription'] = __subscribe_button.data('subscription-id');
                        data['position'] = __subscribe_button.data('options-position');
                        data['mode'] = 'subscription_options';
                        data['id'] = __subscribe_button.attr('id');
                        data['subscribed'] = __subscribe_button.data('is-subscribed');
                        data['name'] = __subscribe_button.data('subscribed-name');
                        
                        amplify.request('users', data,function( d ) {
                            __subscribe_button.removeClass('loading-subscription-options');
                             __subscribe_button.addClass('subscription-options-opened');
                            if ( d.success ) {
                                $('.subscriptions-options').hide();
                                
                                $('body').prepend( d.output );
                                var options_list = $('#'+__subscribe_button.data('subscribed-to')+'-'+__subscribe_button.data('subscription-id')+'-list');
                                options_list.css({
                                    position : 'absolute'
                                });
                                
                                list_positions = get_subscription_options_container_positions( ( d.position ), __subscribe_button, options_list  );
                                
                                options_list.css({
                                    'top' : list_positions['top']+"px",
                                    'left' : list_positions['left']+"px",
                                    'zIndex' : '9999',
                                    'display' : 'block'
                                });
                                
                                options_list.find('.subscription-type-check[data-update=subscription-type]').on( 'click', function( event ) {
                                    var _current_checkbox = $( this ), data = {};
                                    _current_checkbox.attr( 'disabled', 'disabled' );

                                    data['user'] = _current_checkbox.data('user');
                                    data['owner'] = _current_checkbox.data('owner');
                                    data['mode'] = 'update_subscription_option';
                                    data['type'] = _current_checkbox.data('type');
                                    
                                    if ( _current_checkbox.prop('checked') == false ) {
                                        data['action'] = 'uncheck';
                                        _current_checkbox.parent('li.subscription-type').removeClass('checked').addClass('unchecked');
                                    } else {
                                        data['action'] = 'check';
                                        _current_checkbox.parent('li.subscription-type').removeClass('unchecked').addClass('checked');
                                    }
                                    
                                    
                                    
                                    amplify.request( 'users', data, function( r ) {
                                        _current_checkbox.removeAttr( 'disabled' );
                                        options_list.hide();
                                        
                                        if ( r.error ) {
                                            if ( data['action'] == 'uncheck' ) {
                                                _current_checkbox.prop( 'checked', true );
                                                _current_checkbox.parent('li.subscription-type').removeClass('unchecked').addClass('checked');
                                            } else {
                                                _current_checkbox.prop( 'checked', false );
                                                _current_checkbox.parent('li.subscription-type').removeClass('checked').addClass('unchecked');
                                            }

                                            displayError( r.error );
                                        }
                                    });
                                });
                                                                
                                options_list.on( 'mouseleave', function() {
                                    options_list.hide();
                                    __subscribe_button.removeClass('subscription-options-opened')
                                });
                            }
                            
                            if( d.error )
                            {
                                displayError( d.error );
                            }
                        });
                        
                    }, __subscription_options_timeout );
            },
            
            'mouseleave' : function( event ) {
                if ( __subscription_options_timeout_counter ) {
                    clearTimeout( __subscription_options_timeout_counter );
                }
            }
        });
}

/*
 * Fix position of subscribed content list
 */
function get_subscription_options_container_positions( position, button, list ) {
    
    var positions = {},
            button_offsets = button.offset(),
            top, left;
    
    switch ( position ) {
        case "bottom":
        default : {
            top = ( button_offsets.top + button.outerHeight() ) + 6;
            left = button_offsets.left;
        }
        break;
        
        case "top": {
            top = ( button_offsets.top - list.outerHeight() ) - 6;
            left = button_offsets.left;
        }
        break;
        
        case "left": {
            left = ( button_offsets.left - list.outerWidth() ) - 6;
            top = ( button_offsets.top - ( list.outerHeight() / 2 ) + ( button.outerHeight() / 2 ) );    
        }
        break;
        
        case "right": {
            left = ( button_offsets.left + button.outerWidth() ) + 6;
            top = ( button_offsets.top - ( list.outerHeight() / 2 ) + ( button.outerHeight() / 2 ) );
        }
        break;
    }
    
    positions['top'] = top;
    positions['left'] = left;
    
    return positions;
}

$( document ).ready( function() {
    var __subscribe_button = $('[data-subscribe-button=true]');
    
    $.each( __subscribe_button, function( index, btn ) {
        attach_subscription_events( $( btn ) );
        attach_subscription_options_list( $( btn ) );
    });

    
    $('.subscription-type-check[data-update=subscription-type]').on( 'click', function( event ){
        var _current_checkbox = $( this ), data = {};
        //_current_checkbox.attr( 'disabled', 'disabled' );
        
        data['user'] = _current_checkbox.data('user');
        data['owner'] = _current_checkbox.data('owner');
        data['mode'] = 'update_subscription_option';
        data['type'] = _current_checkbox.data('type');
        
        if ( _current_checkbox.prop('checked') == false ) {
            data['action'] = 'uncheck';
        } else {
            data['action'] = 'check';
        }
        
        amplify.request( 'users', data, function( r ) {
            _current_checkbox.removeAttr( 'disabled' );
            if ( r.error ) {
                if ( data['action'] == 'uncheck' ) {
                    _current_checkbox.prop( 'checked', true );
                } else {
                    _current_checkbox.prop( 'checked', false );
                }
                
                displayError( r.error );
            }
        });
    });
}); 