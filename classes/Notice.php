<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Notice
 *
 * @package    Notices
 * @version    v2.0.0b
 * @author     Jeremy Lindblom <jeremy@synapsestudios.com>
 * @copyright  Copyright (c) 2009 Synapse Studios
 */
class Notice
{
	/**
	 * A unique indentifying hash
	 * @var		string
	 */
	protected $hash = NULL;

	/**
	 * The notice type used for css styling
	 * @var		string
	 */
	protected $type = 'notice';

	/**
	 * The content of the notice
	 * @var		string
	 */
	protected $message = '';

	/**
	 * Whether or not the notice is persistent
	 * @var		boolean
	 */
	protected $is_persistent = FALSE;

	/**
	 * Whether or not the notice is rendered
	 * @var		boolean
	 */
	protected $is_rendered = FALSE;

	/**
	 * Timestamp of when the notice was created
	 * @var		integer
	 */
	protected $microtime = 0;

	/**
	 *
	 * @param	string	$type
	 * @param	string	$message
	 * @param	boolean	$persistent
	 */
	public function __construct($type, $message, $persistent = FALSE)
	{
		if ( ! is_string($type))
			throw new InvalidArgumentException('Type must be a valid string.');

		if ( ! is_string($message))
			throw new InvalidArgumentException('Message must be a valid string.');

		$this->type = $type;
		$this->message = __($message); // Use i18n
		$this->is_persistent = (bool) $persistent;
		$this->microtime = microtime(TRUE);
		$this->hash = $this->crc_hash($type.$message.$this->microtime); // Unique hash
	}

	/**
	 *
	 * @return	string
	 */
	public function render()
	{
		$this->is_rendered = TRUE;
		return View::factory('modules/notices/notice')
			->set('notice', $this)
			->render();
	}

	/**
	 *
	 * @param	string	$key
	 * @return	mixed
	 */
	public function __get($key)
	{
		return isset($this->$key) ? $this->$key : NULL;
	}

	/**
	 *
	 * @return	string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 *
	 */
	public function remove_persistence()
	{
		$this->is_persistent = FALSE;
	}

	/**
	 *
	 * @param	boolean	$state
	 */
	public function set_rendered_state($state = FALSE)
	{
		$state = (bool) $state;
		$this->is_rendered = $state;
	}

	/**
	 *
	 * @param	Notice	$notice
	 * @return	boolean
	 */
	public function similar_to(Notice $notice)
	{
		$this_representation = $this->type.$this->message;
		$notice_representation = $notice->type.$notice->message;
		return (bool) $this_representation == $notice_representation;
	}

	/**
	 *
	 * @param	string	$string
	 * @return	string
	 */
	protected function crc_hash($string)
	{
		return str_pad(dechex(crc32($string) & 0xffffffff), 8, "0", STR_PAD_LEFT);
	}
	
} // End Notice