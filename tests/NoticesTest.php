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

	protected function setUp()
	{
		Notices::init();
	}

	protected function tearDown()
	{
		Session::instance()->destroy();
	}

	public function providerAdd()
	{
		return array(
			array('success', 'You have succeeded!', array(), FALSE),
			array('error', 'You have errored!', array(), FALSE),
			array('warning', 'You have been warned!', array(), TRUE),
			array('info', 'You have been informed!', array(), FALSE),
		);
	}

	/**
	 * Test the Notices::add(...) method
	 *
	 * @test
	 * @dataProvider providerAdd
	 */
	public function testAdd($type, $message, $values, $persist)
	{
		// Create a new Notice
		$result = Notices::add($type, $message, $values, $persist);

		// Retrieve the Notices queue
		$notices = Session::instance()->get('notices', array());

		// Make sure the result is a Notice
		$this->assertTrue($result instanceOf Notice);

		// Make sure the result was added to the session
		$this->assertTrue(isset($notices[$result->hash]));

		// Make sure the notice in the session matches the result
		$this->assertTrue($result->similar_to($notices[$result->hash]));
	}

	public function providerAddUnique()
	{
		return array(
			array('success', 'You have succeeded!!!', array(), FALSE, TRUE),
			array('error', 'You have succeeded!', array(), FALSE, TRUE),
			array('success', 'You have succeeded!', array(), FALSE, FALSE),
			array('success', 'You have succeeded!', array(), TRUE, FALSE),
			array('error', 'You have errored!', array(), FALSE, TRUE),
		);
	}

	/**
	 * Test the Notices::add_unique(...) method
	 *
	 * @test
	 * @dataProvider providerAddUnique
	 */
	public function testAddUnique($type, $message, $values, $persist, $allowed)
	{
		// Put an initial Notice in the Notices queue
		$original = Notices::add('success', 'You have succeeded!', array(), FALSE);

		// Try to create a unique Notice
		$result = Notices::add_unique($type, $message, $values, $persist);

		// Retrieve the Notices queue
		$notices = Session::instance()->get('notices', array());

		// Make sure that uniqueness is enforced
		$this->assertSame($allowed, (bool) $result);

		// If it was unique, perform assertions similar to testAdd
		if ($result)
		{
			// Make sure the result is a Notice
			$this->assertTrue($result instanceOf Notice);

			// Make sure the result was added to the session
			$this->assertTrue(isset($notices[$result->hash]));

			// Make sure the notice in the session matches the result
			$this->assertTrue($result->similar_to($notices[$result->hash]));
		}
	}

}