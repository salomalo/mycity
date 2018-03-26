
var url = '';
var urlBreadcrumbs = '';
var urlMenu = '';
    
function set_urlBreadcrumbs(cl) {
    urlBreadcrumbs = cl;
}

function set_urlMenu(cl) {
    urlMenu = cl;
}

$(function() {
    $(document).on("click", 'div.widget_categories div div ul li a, ul.breadcrumb li:gt(0) a', function(e){
        e.preventDefault();
        
        var pid = null;
        var href = $(this).attr('href');
        
        var pos = href.indexOf('pid=');
        if(pos > 0){
            pid = href.substr(pos+4);
        }
        
        var content = $('div.column-center');
        var arr = [];
        var breadcrumb = $('ul.breadcrumb');
        var menu = $('div.widget_categories');
        
        jQuery.ajax({
            url: href,
            type: "GET",
            dataType: "text",
            data: {/*pid:pid*/},
            async: false,
            success: function(response) {
                
                data = JSON.parse(response);
                
                $.each(data, function(i) {
                    console.log(data[i]);
                    arr.push(data[i]);
                });

            },
            error: function(response) {

            }
        });
        
        deleteMarkers();
    
        set_data(JSON.stringify(arr[1]));
        
        ViewMarkers();
        
        breadcrumb.html('');
        
        jQuery.ajax({
            url: urlBreadcrumbs,
            type: "POST",
            dataType: "text",
            data: {breadcrumbs:arr[0]},
            async: false,
            success: function(response) {
                 breadcrumb.replaceWith(response);
            },
            error: function(response) {

            }
        });
        
        menu.html('');
        
        jQuery.ajax({
            url: urlMenu,
            type: "POST",
            dataType: "text",
            data: {pid:pid},
            async: false,
            success: function(response) {
                 menu.replaceWith(response);
            },
            error: function(response) {

            }
        }); 
        
        
    });
});