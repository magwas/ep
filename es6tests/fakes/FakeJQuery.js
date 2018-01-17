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
export default FakeJQuery;