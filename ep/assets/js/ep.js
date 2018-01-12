function accept_rules() {
        var query = {
                'action' : 'ep_accept_rules',
        };
        console.log(query);
        jQuery.post('/wp-admin/admin-ajax.php', query, function(response) {
                console.log('Got this from the server: ' + response);
		window.location.reload();
        });
}
