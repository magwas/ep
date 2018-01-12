<?php declare(strict_types=1);
class TestData {
	function __construct() {
		$this->test_data = [
			'terms'       => [
				[
					1,
					'vita',
					'slug'        => 'vote',
					'description' => '1',
					'name'        => 'A vote',
				],
				[
					2,
					'szakkoli',
					'slug'        => 'slug_6',
					'description' => '6',
					'name'        => 'An unknown post',
				],
			],
			'posts'       => [
				1 => [
					'post_name'  => 'vote',
					'post_title' => 'title_1',
					'thumbnail'  => '/thumbnail_1.png',
					'post_type'  => 'problem',
					'terms'      => [ 2 ],
				],
				2 => [
					'post_name'  => 'slug_2',
					'post_title' => 'title_2',
					'post_type'  => 'javaslat',
					'thumbnail'  => '/thumbnail_2.png',
					'terms'      => [ 1 ],
				],
				3 => [
					'post_name'  => 'slug_3',
					'post_title' => 'title_3',
					'post_type'  => 'solution',
					'thumbnail'  => '/thumbnail_3.png',
					'terms'      => [ 1 ],
				],
				4 => [
					'post_name'  => 'slug_4',
					'post_title' => 'title_4',
					'thumbnail'  => '/thumbnail_4.png',
					'post_type'  => 'javaslat',
					'parent'     => 'vote',
					'terms'      => [ 1 ],
				],
				5 => [
					'post_name'  => 'slug_5',
					'post_title' => 'title_5',
					'thumbnail'  => '/thumbnail_5.png',
					'post_type'  => 'javaslat',
					'parent'     => 'vote',
					'terms'      => [ 1 ],
				],
				6 => [
					'post_name'  => 'slug_6',
					'post_title' => 'title_6',
					'thumbnail'  => '/thumbnail_6.png',
					'post_type'  => 'szakkolegium',
				],
				7 => [
					'post_name'  => 'slug_7',
					'post_title' => 'title_7',
					'thumbnail'  => '/thumbnail_7.png',
					'post_type'  => 'unknown',
				],
				8 => [
					'post_name'  => 'slug_8',
					'post_title' => 'title_8',
					'thumbnail'  => '/thumbnail_8.png',
					'post_type'  => 'szakkolegium',
				],
			],
			'currentpost' => 1,
			'users'       => [
				[ 'ID' => 0 ],
				[
					'ID'           => 1,
					'display_name' => 'Unaccepting User',
					'user_meta'    => [
						'accepted_the_rules'  => [],
						'eDemoSSO_assurances' => [],
					],
				],
				[
					'ID'           => 2,
					'display_name' => 'Accepting Uncertified User',
					'user_meta'    => [
						'accepted_the_rules'  => [ 1 ],
						'eDemoSSO_assurances' => [],
					],
				],
				[
					'ID'           => 3,
					'display_name' => 'Accepting Emagyar User',
					'user_meta'    => [
						'accepted_the_rules'  => [ 1 ],
						'eDemoSSO_assurances' => [ '["emagyar"]' ],
					],
				],
				[
					'ID'           => 4,
					'display_name' => 'Accepting Magyar User',
					'user_meta'    => [
						'accepted_the_rules'  => [ 1 ],
						'eDemoSSO_assurances' => [ '["magyar"]' ],
					],
				],
				[
					'ID'           => 5,
					'display_name' => 'Accepting Magyar and Emagyar User',
					'user_meta'    => [
						'accepted_the_rules'  => [ 1 ],
						'eDemoSSO_assurances' => [ '["magyar","emagyar"]' ],
					],
				],
			],
		];
	}
}
