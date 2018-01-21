<?php //phpcs:disable WordPress.Files.FileName.InvalidClassFileName

require_once 'vendor/autoload.php';
require_once 'e2e/class-e2etest.php';


class Operations extends E2eTest {

	protected function join_szakkol() {
		$this->url( 'http://localhost/test-szakkol' );
		$this->join_button = $this->byXPath( "//*[@id='ep_dashboard']/button[@class='szakkol-join-button']" );
		$this->assertEquals( 'Belépek a szakkolégiumba', $this->join_button->text() );
		$this->waitForPageReload(
			function () {
				$this->join_button->click();
			}, 10000
		);
	}

	protected function accept_rules() {
		$this->waitForPageReload(
			function () {
				$this->byXPath( "//*[@id='ep_acceptrules']" )->click();
			}, 10000
		);
		$this->dashboard_link = $this->byXPath( "//*[@id='ep_dashboard']/a" );
		$this->assertEquals( 'http://localhost/hogyan-szerzek-magyar-vagy-emagyar-igazolast', $this->dashboard_link->attribute( 'href' ) );
	}

	protected function look_at_rules() {
		$this->url( 'http://localhost/' );
		$this->dashboard_link = $this->byXPath( "//*[@id='ep_dashboard']/a" );
		$this->assertEquals( 'http://localhost/alapito-okirat', $this->dashboard_link->attribute( 'href' ) );
		$this->waitForPageReload(
			function () {
				$this->dashboard_link->click();
			}, 10000
		);
	}


	protected function login() {
		$this->url( 'http://localhost/wp-login.php' );
		$this->byId( 'user_login' )->value( 'bob' );
		$this->byId( 'user_pass' )->value( 'bobpassword' );
		$this->waitForPageReload(
			function () {
				$this->byId( 'loginform' )->submit();
			}, 10000
		);
		$this->assertEquals( 'Howdy, bob', $this->byId( 'wp-admin-bar-my-account' )->text() );
	}
}
