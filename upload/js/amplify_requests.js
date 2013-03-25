var ajaxURL = baseurl+'/ajax';

//Update sidebar
amplify.request.define( "videos", "ajax", {
    url: ajaxURL+"/videos.php",
    type: "POST",
    dataType: "json",
    decoder: function( data, status, xhr, success, error ) {
        if ( status === "success" ) {
            success( data );
        } else {
            error( data );
        }
    }
});

//Groups
amplify.request.define( "groups", "ajax", {
    url: ajaxURL+"/groups.php",
    type: "POST",
    dataType: "json",
    decoder: function( data, status, xhr, success, error ) {
        if ( status === "success" ) {
            success( data );
        } else {
            error( data );
        }
    }
});


//defining up clipbucjet ajax request
amplify.request.define( "main", "ajax", {
    url: ajaxURL+"/main.php",
    type: "POST",
    dataType: "json",
    decoder: function( data, status, xhr, success, error ) {
        if ( status === "success" ) {
            success( data );
        } else {
            error( data );
        }
    }
});

//photos private message
amplify.request.define( "photos", "ajax", {
    url: ajaxURL+"/photos.php",
    type: "POST",
    dataType: "json",
    decoder: function( data, status, xhr, success, error ) {
        if ( status === "success" ) {
            success( data );
        } else {
            error( data );
        }
    }
});


//feeds amplify
amplify.request.define( "feeds", "ajax", {
    url: ajaxURL+"/feed.php",
    type: "POST",
    dataType: "json",
    decoder: function( data, status, xhr, success, error ) {
        if ( status === "success" ) {
            success( data );
        } else {
            error( data );
        }
    }
});

//Dashboard
amplify.request.define( "dashboards", "ajax", {
    url: ajaxURL+"/dashboards.php",
    type: "POST",
    dataType: "json",
    decoder: function( data, status, xhr, success, error ) {
        if ( status === "success" ) {
            success( data );
        } else {
            error( data );
        }
    }
});

//Users
amplify.request.define( "users", "ajax", {
    url: ajaxURL+"/users.php",
    type: "POST",
    dataType: "json",
    decoder: function( data, status, xhr, success, error ) {
        if ( status === "success" ) {
            success( data );
        } else {
            error( data );
        }
    }
});