import assert from "assert";

import Ajax from '../es6/Ajax.js';
import FakeJQuery from "./fakes/FakeJQuery";
import FakeConsole from "./fakes/FakeConsole";
import FakeLocation from "./fakes/FakeLocation";
import FakeWindow from "./fakes/FakeWindow";

describe('Ajax class', function(){
	var fjq = new FakeJQuery();
	var fakeConsole = new FakeConsole()
	var fakeWindow = new FakeWindow(new FakeLocation())
	var ajax = new Ajax(fjq, fakeConsole, fakeWindow);
	describe('the query method', function(){
		beforeEach(function() {
			ajax.query("foo", {bar: 'baz'});
		});
		it('logs the query to console', function() {
			assert.deepEqual(fakeConsole.logged,{ action: 'foo', bar: 'baz' });
		})
		it('uses JQuerys post method', function(){
			assert.notEqual('',fjq.uri); 
		})
		it('the post uri is /wp-admin/admin-ajax.php', function(){
			assert.equal('/wp-admin/admin-ajax.php',fjq.uri); 
		})
		it('the query action is te one in the parameters', function(){
			assert.equal('foo',fjq.query.action); 
		})
		it('the query callback is the default ajax one', function(){
			assert.ok("defaultCallback" in ajax);
			assert.equal(ajax.defaultCallback,fjq.callback); 
		})
		it('reloads the page when sends the callback', function(){
			assert.ok(fakeWindow.location.reloaded);
		})
	})

	var response = "hello world";
	var callBack = ajax.defaultCallback;
	describe('the defaultCallback method', function(){
		beforeEach(function() {
			callBack(response);
		});
		it('logs its response to the console', function (){
			assert.equal(fakeConsole.logged,'server response:'+response);
		})
	})
});
