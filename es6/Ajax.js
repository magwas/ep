class Ajax {
	constructor(jquery, console, window) {
		this.jquery = jquery;
		this.console = console;
		this.window = window;
		this.defaultCallback = (response) => {
			this.console.log("server response:" + response)
			this.window.location.reload();		
		}
	}
	copyfields(query,data) {
        if(data) {
        	for(var key in data) {
            	query[key] = data[key];        			
        	}
        }		
	}
	query(action,data) {
        var query = {
                'action' : action,
        };
        this.copyfields(query,data);
        this.console.log(query);
		this.jquery.post('/wp-admin/admin-ajax.php', query, this.defaultCallback);
	}
	acceptRules() {
		this.query('ep_accept_rules');
	}
	
	joinSzakkol(szakkolName) {
		this.query('ep_join_szakkol', {szakkol: szakkolName});
	}
}

export default Ajax;