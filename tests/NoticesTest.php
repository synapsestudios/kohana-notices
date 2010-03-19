<?php
/**
 * Tests the Notices module `Notices` class
 *
 * @group      Notices
 *
 * @package    Notices
 * @version    v2.0.0
 * @author     Jeremy Lindblom <jeremy@synapsestudios.com>
 * @copyright  Copyright (c) 2009 Synapse Studios
 */
Class NoticesTest extends PHPUnit_Framework_TestCase
{

//	protected function setUp()
//	{
//		Notices::init();
//	}
//
//	protected function tearDown()
//	{
//		Session::instance()->destroy();
//	}
//
//	public function providerAdd()
//	{
//		return array(
//			array('success', 'You have succeeded!', FALSE, new Notice('success', 'You have succeeded!', FALSE), TRUE),
//			array('error', 'You have errored!', TRUE, new Notice('error', 'You have errored!', TRUE), TRUE),
//			array('success', 'You have errored!', FALSE, new Notice('success', 'You have succeeded!', FALSE), FALSE),
//		);
//	}
//
//	/**
//	 * @test
//	 * @dataProvider providerAdd
//	 */
//	public function testAdd($type, $message, $persist, $expected, $should_pass)
//	{
//		$result = Notices::add($type, $message, $persist);
//		$notices = Session::instance()->get('notices', array());
//
//		// Make sure the result is a Notice
//		$this->assertTrue($result instanceOf Notice);
//
//		// Make sure notice was added to the session
//		$this->assertTrue(isset($notices[$result->hash]));
//
//		// Make sure the notice in the session matches the result
//		$this->assertTrue($result->similar_to($notices[$result->hash]));
//
////		echo $result->type.'/'.$expected->type."\n";
////		echo $result->message.'/'.$expected->message."\n";
////		var_dump($result->similar_to($expected), $should_pass);echo "\n";
//
//		// Make sure the expected notice matches the result
//		$this->assertTrue(($result->similar_to($expected) == $should_pass));
//	}

}