<?php
class TestData {
	function __construct() {
		$post1_term = new FakeTerm(['slug' => 'vote', 'description' => '1', 'name' => 'A vote']);
		$fake_term = new FakeTerm(['slug' => 'vvv', 'description' => '8', 'name' => 'An unknown post']);
		$this->testData = [
				'posts' => [
						1 => [
								'slug' => 'vote',
								'title' => 'title_1',
								'thumbnail' => '/thumbnail_1.png',
								'type' => 'problem'
						],
						2 => [
								'slug' => 'slug_2',
								'title' => 'title_2',
								'type' => 'solution',
								'thumbnail' => '/thumbnail_2.png',
								'terms' => ['vita' => [$post1_term]]
						],
						3 => [
								'slug' => 'slug_3',
								'title' => 'title_3',
								'type' => 'solution',
								'thumbnail' => '/thumbnail_3.png',
								'terms' => ['vita' => [$post1_term]]
						],
						4 => [
								'slug' => 'slug_4',
								'title' => 'title_4',
								'thumbnail' => '/thumbnail_4.png',
								'type' => 'solution',
								'parent' => 'vote',
								'terms' => ['vita' => [$post1_term]]
						],
						5 => [
								'slug' => 'slug_5',
								'title' => 'title_5',
								'thumbnail' => '/thumbnail_5.png',
								'type' => 'solution',
								'parent' => 'vote',
								'terms' => ['vita' => [$post1_term]]
						],
						6 => [
								'slug' => 'slug_6',
								'title' => 'title_6',
								'thumbnail' => '/thumbnail_6.png',
								'type' => 'szakkolegium'
						],
						7 => [
								'slug' => 'slug_7',
								'title' => 'title_7',
								'thumbnail' => '/thumbnail_7.png',
								'type' => 'unknown'
						],
				],
				"currentpost" => 1,
				"taxonomy" => [
						'vita' => [$post1_term],
						'szakkoli' => [$fake_term]]
		];
	}
}
