import assert from "assert";

import Ajax from '../es6/Ajax.js';
import FakeJQuery from "./fakes/FakeJQuery";
import FakeConsole from "./fakes/FakeConsole";
import FakeLocation from "./fakes/FakeLocation";
import FakeWindow from "./fakes/FakeWindow";

describe('Accepting the rules', function(){
	var fjq = new FakeJQuery();
	var fakeConsole = new FakeConsole()
	var fakeWindow = new FakeWindow(new FakeLocation())
	var ajax = new Ajax(fjq, fakeConsole, fakeWindow);
	describe('the acceptRules method of Ajax', function(){
		beforeEach(function() {
			ajax.acceptRules();
		});
		it('uses the query action ep_accept_rules', function(){
			assert.equal('ep_accept_rules',fjq.query.action); 
		})
		it('the query callback is the default ajax one', function(){
			assert.ok("defaultCallback" in ajax);
			assert.equal(ajax.defaultCallback,fjq.callback); 
		})

	})
});
