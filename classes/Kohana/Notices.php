<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Notices Module - The purpose of this module is to provide an easy way to send
 * messages to the user on any page, based on events and decisions in the
 * application. Most commonly, these message are used to inform users of errors.
 * The Notices module uses the session to pass messages on to the next page.
 *
 * @package    Notices
 * @author     Jeremy Lindblom <jeremy@synapsestudios.com>
 * @copyright  Copyright (c) 2009 Synapse Studios
 */
class Kohana_Notices
{
	/**
	 * @var  array  The queue of notices to be rendered and displayed
	 */
	protected static $notices = array();

	/**
	 * Adds a new notice to the notices queue. The notice type corresponds to a
	 * CSS class used for styling.
	 *
	 * @param	string	 $type        The type of notice
	 * @param	string	 $key         The the key of the message to be sent to the user
	 * @param   array    $values      Values to replace the ones in the message using `__()`
	 * @return	Notice
	 */
	public static function add($type, $key, array $values = NULL)
	{
		// Fetch notices from Session
		Notices::$notices = Session::instance()->get('notices', array());

		// The hash acts as a unique identifier.
		Notices::$notices[$type] = array
		(
			'type'   => $type,
			'key'    => $key,
			'values' => $values,
		);

		Session::instance()->set('notices', Notices::$notices);
	}

	/**
	 * Retrieves a set of notices based on type, and rendered state
	 *
	 * @param	mixed	 $types       Notice types
	 * @param	boolean	 $once        Whether or not to remove the notice
	 * @return	array
	 */
	public static function get($types = NULL, $once = TRUE)
	{
		// Fetch notices from Session
		Notices::$notices = Session::instance()->get('notices', array());

		if (is_string($types))
		{
			$results = Arr::get(Notices::$notices, $types);

			if ($once)
				unset(Notices::$notices[$types]);
		}
		elseif (is_array($types))
		{
			$results = array();

			foreach ($types as $type)
			{
				$results[$type] = Arr::get(Notices::$notices, $type);

				if ($once)
					unset(Notices::$notices[$type]);
			}
		}
		else
		{
			// Return all of them
			$results = Notices::$notices;

			if ($once)
				Notices::$notices = array();
		}

		Session::instance()->set('notices', Notices::$notices);

		return $results;
	}

	/**
	 * The `__callStatic()` allows the creation of notices using the shorter
	 * syntax: `Notices::success('message');` This works for PHP 5.3+ only
	 *
	 * @param	string	$method  Method name
	 * @param	array	$args    method arguments
	 * @return	mixed
	 */
	public static function __callStatic($method, $args)
	{
		return Notices::add($method, Arr::get($args, 0), Arr::get($args, 1), Arr::get($args, 2));
	}

	/*
	 * Enforce static behavior
	 */
	final private function __construct()
	{
		// Enforce static behavior
	}

} // End Notices
