<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Notices
 *
 * @package    Notices
 * @version    v2.0.0b
 * @author     Jeremy Lindblom <jeremy@synapsestudios.com>
 * @copyright  Copyright (c) 2009 Synapse Studios
 */
class Notices
{
	const UNIQUE_PREFIX = 'unique_';

	/**
	 *
	 * @var		array
	 */
	protected static $notices = array();

	/**
	 *
	 */
	public static function init()
	{
		// Fetch notices from Session
		self::$notices = Session::instance()->get('notices', array());

		// Clear all the non-persistent notices that have already been rendered
		self::clear();

		// Set all the current notices to not-rendered
		foreach (self::$notices as $notice)
		{
			$notice->set_rendered_state(FALSE);
		}

		// Save the notices!
		self::save();
	}

	/**
	 *
	 */
	public static function save()
	{
		// Put the notices array into the Session
		Session::instance()->set('notices', self::$notices);
	}

	/**
	 *
	 * @param	string	$type
	 * @param	string	$message
	 * @param	boolean	$persistent
	 * @return	Notice
	 */
	public static function add($type, $message, $persistent = FALSE)
	{
		// Create a new message
		$notice = new Notice($type, $message, $persistent);

		// The hash acts as a unique identifier.
		self::$notices[$notice->hash] = $notice;

		// Save the notices!
		self::save();

		return $notice;
	}

	/**
	 *
	 * @param	string	$type
	 * @param	string	$message
	 * @param	boolean	$persistent
	 * @return	Notice
	 */
	public function add_unique($type, $message, $persistent = FALSE)
	{
		try
		{
			// Create a new message
			$notice = new Notice_Unique($type, $message, $persistent);

			// The hash acts as a unique identifier. All notices must have a unique type/message combination.
			self::$notices[$notice->hash] = $notice;

			// Save the notices!
			self::save();

			return $notice;
		}
		catch (Exception $e)
		{
			return FALSE;
		}
	}

	/**
	 *
	 * @param	string	$hash
	 * @return	mixed
	 */
	public static function get($hash)
	{
		if (is_string($hash) AND isset(self::$notices[$hash]))
			return self::$notices[$hash];
		else
			return NULL;
	}

	/**
	 *
	 * @param	mixed	$type
	 * @param	boolean	$rendered
	 * @param	boolean	$persistent
	 * @return	array
	 */
	public static function get_all($type = NULL, $rendered = NULL, $persistent = NULL)
	{
		// Prepare the type argument
		if (is_string($type))
		{
			$type = array($type);
		}
		if ( ! is_array($type))
		{
			$type = NULL;
		}

		// Find notices that match the arguments
		$results = array();
		foreach (self::$notices AS $notice)
		{
			$type_matches = is_null($type) OR in_array($notice->type, $type);
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
	 *
	 * @param	mixed	$type
	 * @param	boolean	$rendered
	 * @param	boolean	$persistent
	 */
	public static function clear($type = NULL, $rendered = TRUE, $persistent = FALSE)
	{
		// Unset the matching notices (Defaults to non-persistent, rendered notices)
		foreach (self::get_all($type, $rendered, $persistent) as $notice)
		{
			unset(self::$notices[$notice->hash]);
		}

		self::save();
	}

	/**
	 *
	 * @param	mixed	$type
	 * @param	boolean	$rendered
	 * @param	boolean	$persistent
	 * @return	integer
	 */
	public static function count($type = NULL, $rendered = FALSE, $persistent = FALSE)
	{
		// Count the number of matching notices (Defaults to all non-rendered notices)
		return count(self::get_all($type, $rendered));
	}

	/**
	 *
	 * @param	mixed	$type
	 * @param	boolean	$rendered
	 * @param	boolean	$persistent
	 * @return	string
	 */
	public static function display($type = NULL, $rendered = FALSE, $persistent = NULL)
	{
		// Render the matching notices (Defaults to all non-rendered notices)
		$html = '';
		foreach (self::get_all($type, $rendered, $persistent) as $notice)
		{
			$html .= $notice->render();
		}
		self::save();

		return $html;
	}

	/**
	 *
	 * @param	string	$method
	 * @param	array	$args
	 * @return	mixed
	 */
	public static function __callStatic($method, $args)
	{
		if (strpos($method, self::UNIQUE_PREFIX) === 0)
			return self::add_unique(substr($method, strlen(self::UNIQUE_PREFIX)), arr::get($args, 0), arr::get($args, 1));
		else
			return self::add($method, arr::get($args, 0), arr::get($args, 1));
	}
}