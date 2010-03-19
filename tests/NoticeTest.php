<?php
/**
 * Tests the Notice Object
 *
 * @group      Notices
 *
 * @package    Notices
 * @version    v2.0.0
 * @author     Jeremy Lindblom <jeremy@synapsestudios.com>
 * @copyright  Copyright (c) 2009 Synapse Studios
 */
Class NoticeTest extends PHPUnit_Framework_TestCase
{

	public function providerSimilarTo()
	{
		return array(
			array(
				array('type' => 'success', 'message' => 'You have succeeded!', 'persist' => FALSE),
				array('type' => 'success', 'message' => 'You have succeeded!', 'persist' => FALSE),
				TRUE,
			),
			array(
				array('type' => 'success', 'message' => 'You have succeeded!', 'persist' => FALSE),
				array('type' => '*success*', 'message' => 'You have succeeded!', 'persist' => FALSE),
				FALSE,
			),
			array(
				array('type' => 'success', 'message' => 'You have succeeded!', 'persist' => FALSE),
				array('type' => 'success', 'message' => '*You have succeeded!*', 'persist' => FALSE),
				FALSE,
			),
			array(
				array('type' => 'success', 'message' => 'You have succeeded!', 'persist' => FALSE),
				array('type' => 'success', 'message' => 'You have succeeded!', 'persist' => TRUE),
				TRUE,
			),
			array(
				array('type' => 'success', 'message' => 'You have succeeded!', 'persist' => FALSE),
				array('type' => 'error', 'message' => 'You have errored!', 'persist' => FALSE),
				FALSE,
			),
		);
	}

	/**
	 * @test
	 * @dataProvider providerSimilarTo
	 */
	public function testSimilarTo($args_one, $args_two, $expected)
	{
		$reflected = new ReflectionClass('Notice');
		$notice_one = $reflected->newInstanceArgs($args_one);
		$notice_two = $reflected->newInstanceArgs($args_two);
		
		$this->assertSame($expected, $notice_one->similar_to($notice_two));
	}

}