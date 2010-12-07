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
	const UNIQUE_PREFIX = 'unique_';

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
	 * @param	boolean	 $persistent  If TRUE, the notice must be manually closed via JavaScript. Defaults to FALSE.
	 * @return	Notice
	 */
	public static function add($type, $msg_key, array $values = NULL, $persistent = FALSE)
	{
		// Create a new message
		$notice = new Notice($type, $msg_key, $values, $persistent);

		// The hash acts as a unique identifier.
		Notices::$notices[$notice->hash] = $notice;

		// Save the notices!
		Notices::save();

		return $notice;
	}

	/**
	 * Adds a new unique notice to the notices queue. The notice type
	 * corresponds to a CSS class used for styling. Unique notices must
	 * have a unique combination of type and message.
	 *
	 * @param	string	 $type        The type of notice
	 * @param	string	 $msg_key     The the key of the message to be sent to the user
	 * @param   array    $values      Values to replace the ones in the message using `__()`
	 * @param	boolean	 $persistent  If TRUE, the notice must be manually closed via JavaScript. Defaults to FALSE.
	 * @return	mixed
	 */
	public static function add_unique($type, $msg_key, array $values = NULL, $persistent = FALSE)
	{
		try
		{
			// Create a new message
			$notice = new Notice_Unique($type, $msg_key, $values, $persistent);

			// The hash acts as a unique identifier. All notices must have a unique type/message combination.
			Notices::$notices[$notice->hash] = $notice;

			// Save the notices!
			Notices::save();

			return $notice;
		}
		catch (Exception $e)
		{
			return FALSE;
		}
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
	 * Retrieves a set of notices based on type, rendered state, and persistence
	 *
	 * @param	mixed	 $type        Notice type
	 * @param	boolean	 $rendered    Whether or not a Notice has been rendered
	 * @param	boolean	 $persistent  Whether or not a Notice is persistent
	 * @return	array
	 */
	public static function get_all($type = NULL, $rendered = NULL, $persistent = NULL)
	{
		// Prepare the type argument
		$type = (is_string($type) OR is_array($type)) ? (array) $type : NULL;

		// Find notices that match the arguments
		$results = array();
		foreach (Notices::$notices AS $notice)
		{
			$type_matches = (is_null($type) OR in_array($notice->type, $type));
			$render_state_matches = is_bool($rendered) ? ($rendered == $notice->is_rendered) : TRUE;
			$persistence_state_matches = is_bool($persistent) ? ($persistent == $notice->is_persistent) : TRUE;

			if ($type_matches AND $render_state_matches AND $persistence_state_matches)
			{
				$results[] = $notice;
			}
		}

		return $results;
	}

	/**
	 * Clear (unset) a set of notices (Defaults to all non-persistent,
	 * rendered notices)
	 *
	 * @param	mixed	 $type        Notice type
	 * @param	boolean	 $rendered    Whether or not a Notice has been rendered
	 * @param	boolean	 $persistent  Whether or not a Notice is persistent
	 * @return  void
	 */
	public static function clear($type = NULL, $rendered = TRUE, $persistent = FALSE)
	{
		foreach (Notices::get_all($type, $rendered, $persistent) as $notice)
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
	 * @param	boolean	 $persistent  Whether or not a Notice is persistent
	 * @return	integer
	 */
	public static function count($type = NULL, $rendered = FALSE, $persistent = FALSE)
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
	 * Adds a new Notice and redirects to a URL
	 *
	 * @param   string  $type     The type of notice
	 * @param	string	$message  The message to be sent to the user
	 * @param   string  $url      URL to which the user should be redirected
	 * @return  void
	 */
	public static function now($type, $message, $url)
	{
		Notices::add($type, $message);
		Request::instance()->redirect($url);
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
		if (strpos($method, Notices::UNIQUE_PREFIX) === 0)
			return Notices::add_unique(substr($method, strlen(Notices::UNIQUE_PREFIX)), Arr::get($args, 0), Arr::get($args, 1), Arr::get($args, 2));
		else
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
