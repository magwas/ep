<?php
class TestData {
	function __construct() {
		$this->testData = [
				"terms" => [
						[1, 'vita','slug' => 'vote', 'description' => '1', 'name' => 'A vote'],
						[2, 'szakkoli','slug' => 'slug_6', 'description' => '6', 'name' => 'An unknown post'],
				],
				'posts' => [
						1 => [
								'slug' => 'vote',
								'title' => 'title_1',
								'thumbnail' => '/thumbnail_1.png',
								'type' => 'problem',
								'terms' => [2]
						],
						2 => [
								'slug' => 'slug_2',
								'title' => 'title_2',
								'type' => 'javaslat',
								'thumbnail' => '/thumbnail_2.png',
								'terms' => [1]
						],
						3 => [
								'slug' => 'slug_3',
								'title' => 'title_3',
								'type' => 'solution',
								'thumbnail' => '/thumbnail_3.png',
								'terms' => [1]
						],
						4 => [
								'slug' => 'slug_4',
								'title' => 'title_4',
								'thumbnail' => '/thumbnail_4.png',
								'type' => 'javaslat',
								'parent' => 'vote',
								'terms' => [1]
						],
						5 => [
								'slug' => 'slug_5',
								'title' => 'title_5',
								'thumbnail' => '/thumbnail_5.png',
								'type' => 'javaslat',
								'parent' => 'vote',
								'terms' => [1]
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
						8 => [
								'slug' => 'slug_8',
								'title' => 'title_8',
								'thumbnail' => '/thumbnail_8.png',
								'type' => 'szakkolegium'
						],
				],
				"currentpost" => 1,
				"users" => [["ID" => 0],
					[
						'ID' => 1,
						'display_name'=>'Unaccepting User',
						'user_meta' => ['accepted_the_rules' => [], 'eDemoSSO_assurances' => []]],
					[
						'ID' => 2,
						'display_name'=>'Accepting Uncertified User',
						'user_meta' => ['accepted_the_rules' => [1], 'eDemoSSO_assurances' => []]],
					[
						'ID' => 3,
						'display_name'=>'Accepting Emagyar User',
						'user_meta' => ['accepted_the_rules' => [1], 'eDemoSSO_assurances' => ['["emagyar"]']]],
					[
						'ID' => 4,
						'display_name'=>'Accepting Magyar User',
						'user_meta' => ['accepted_the_rules' => [1], 'eDemoSSO_assurances' => ['["magyar"]']]],
					[
						'ID' => 5,
						'display_name'=>'Accepting Magyar and Emagyar User',
						'user_meta' => ['accepted_the_rules' => [1], 'eDemoSSO_assurances' => ['["magyar","emagyar"]']]],
				]
			];
	}
}
