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
	 * Retrieves the notices form the session and stores them in the static
	 * notices array. It also clears notices that have already been rendered.
	 */
	public static function init()
	{
		// Fetch notices from Session
		Notices::$notices = Session::instance()->get('notices', array());

		// Clear all the non-persistent notices that have already been rendered
		Notices::clear();

		// Set all the current notices to not-rendered
		foreach (Notices::$notices as $notice)
		{
			$notice->set_rendered_state(FALSE);
		}

		// Save the notices!
		Notices::save();
	}

	/**
	 * Saves the current notices to the session
	 */
	public static function save()
	{
		// Put the notices array into the Session
		Session::instance()->set('notices', Notices::$notices);
	}

	/**
	 * Adds a new notice to the notices queue. The notice type corresponds to a
	 * CSS class used for styling.
	 *
	 * @param	string	 $type        The type of notice
	 * @param	string	 $msg_key     The the key of the message to be sent to the user
	 * @param   array    $values      Values to replace the ones in the message using `__()`
	 * @return	Notice
	 */
	public static function add($type, $msg_key, array $values = NULL)
	{
		// Create a new message
		$notice = new Notice($type, $msg_key, $values);

		// The hash acts as a unique identifier.
		Notices::$notices[$notice->hash] = $notice;

		// Save the notices!
		Notices::save();

		return $notice;
	}

	/**
	 * Retrieves a particular notice by its hash
	 *
	 * @param	string	$hash  A unique hash identifying a Notice
	 * @return	mixed
	 */
	public static function get($hash)
	{
		if (is_string($hash) AND isset(Notices::$notices[$hash]))
			return Notices::$notices[$hash];
		else
			return NULL;
	}

	/**
	 * Retrieves a set of notices based on type, and rendered state
	 *
	 * @param	mixed	 $type        Notice type
	 * @param	boolean	 $rendered    Whether or not a Notice has been rendered
	 * @return	array
	 */
	public static function get_all($type = NULL, $rendered = NULL)
	{
		// Prepare the type argument
		$type = (is_string($type) OR is_array($type)) ? (array) $type : NULL;

		// Find notices that match the arguments
		$results = array();
		foreach (Notices::$notices AS $notice)
		{
			$type_matches = (is_null($type) OR in_array($notice->type, $type));
			$render_state_matches = is_bool($rendered) ? ($rendered == $notice->is_rendered) : TRUE;

			if ($type_matches AND $render_state_matches)
			{
				$results[] = $notice;
			}
		}

		return $results;
	}

	/**
	 * Clear (unset) a set of notices
	 *
	 * @param	mixed	 $type        Notice type
	 * @param	boolean	 $rendered    Whether or not a Notice has been rendered
	 * @return  void
	 */
	public static function clear($type = NULL, $rendered = TRUE)
	{
		foreach (Notices::get_all($type, $rendered) as $notice)
		{
			unset(Notices::$notices[$notice->hash]);
		}

		Notices::save();
	}

	/**
	 * Count a set of notices (Defaults to all non-rendered notices)
	 *
	 * @param	mixed	 $type        Notice type
	 * @param	boolean	 $rendered    Whether or not a Notice has been rendered
	 * @return	integer
	 */
	public static function count($type = NULL, $rendered = FALSE)
	{
		return count(Notices::get_all($type, $rendered));
	}

	/**
	 * Display a set of notices (Defaults to all non-rendered notices)
	 *
	 * @param	mixed	 $type        Notice type
	 * @param	boolean	 $rendered    Whether or not a Notice has been rendered
	 * @param	boolean	 $persistent  Whether or not a Notice is persistent
	 * @return	string
	 */
	public static function display($type = NULL, $rendered = FALSE, $persistent = NULL)
	{
		$html = '';
		foreach (Notices::get_all($type, $rendered, $persistent) as $notice)
		{
			$html .= $notice->render();
		}

		Notices::save();

		return $html;
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
