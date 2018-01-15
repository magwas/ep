import assert from "assert";

import Ajax from '../es6/Ajax.js';

class FakeJQuery {
	constructor() {
		this.uri = '';
	}
	post(uri, query, callback) {
		this.uri = uri;
		this.query = query;
		this.callback = callback;
	}
}

describe('Ajax class', function(){
	var fjq = new FakeJQuery();
	var ajax = new Ajax(fjq)
	  describe('the acceptRules method', function(){
		  ajax.acceptRules();
	    it('uses JQuerys post method', function(){
	      assert.notEqual('',fjq.uri); 
	    })
	  })
	});