class Ajax {
	constructor(jquery) {
		this.jquery = jquery;
	}
	acceptRules() {
		this.jquery.post();
	}
}

export default Ajax;