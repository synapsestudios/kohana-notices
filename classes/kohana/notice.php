<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Notice - The Notice object encapsulates the type, message, and persistence of
 * a Notice.
 *
 * @package    Notices
 * @author     Jeremy Lindblom <jeremy@synapsestudios.com>
 * @copyright  Copyright (c) 2009 Synapse Studios
 */
class Kohana_Notice
{
	/**
	 * @var  string  A unique indentifying hash
	 */
	protected $hash = NULL;

	/**
	 * @var  string  The notice type used for css styling
	 */
	protected $type = 'notice';

	/**
	 * @var  string  The content of the notice
	 */
	protected $message = '';

	/**
	 * @var  boolean  Whether or not the notice is persistent
	 */
	protected $is_persistent = FALSE;

	/**
	 * @var  boolean  Whether or not the notice is rendered
	 */
	protected $is_rendered = FALSE;

	/**\
	 * @var  integer  Timestamp of when the notice was created
	 */
	protected $microtime = 0;

	/**
	 * Creates a notice
	 *
	 * @param	string	 $type
	 * @param	string	 $msg_key
	 * @param	array	 $values
	 * @param	boolean	 $persistent
	 */
	public function __construct($type, $msg_key, array $values = NULL, $persistent = FALSE)
	{
		if ( ! is_string($type))
			throw new InvalidArgumentException('Type must be a valid string.');

		if ( ! is_string($message))
			throw new InvalidArgumentException('Message must be a valid string.');

		$this->type = $type;
		$this->message = __(Kohana::message('notices', $msg_key), $values);
		$this->is_persistent = (bool) $persistent;
		$this->microtime = microtime(TRUE);
		$this->hash = $this->crc_hash($type.$message.$this->microtime); // Unique hash
	}

	/**
	 * Renders a notice
	 *
	 * @return	string
	 */
	public function render()
	{
		$this->is_rendered = TRUE;
		return View::factory('notices/notice')
			->set('notice', $this)
			->render();
	}

	/**
	 * Gets the property of a notice
	 *
	 * @param	string	$key  Notice property
	 * @return	mixed
	 */
	public function __get($key)
	{
		return isset($this->$key) ? $this->$key : NULL;
	}

	/**
	 * Returns the rendered notice
	 *
	 * @return	string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Removes the persitence of a notice
	 *
	 * @return  void
	 */
	public function remove_persistence()
	{
		$this->is_persistent = FALSE;
	}

	/**
	 * Sets the state to either rendered or not-rendered
	 *
	 * @param	boolean	 $state  Whether or not a Notice has been rendered
	 * @return  void
	 */
	public function set_rendered_state($state = FALSE)
	{
		$state = (bool) $state;
		$this->is_rendered = $state;
	}

	/**
	 * Checks if two notices have the same type and message
	 *
	 * @param	Notice	 $notice  The Notice you are comparing
	 * @return	boolean
	 */
	public function similar_to(Notice $notice)
	{
		$this_representation = $this->type.$this->message;
		$notice_representation = $notice->type.$notice->message;
		return (bool) ($this_representation == $notice_representation);
	}

	/**
	 * Returns the 8-character CRC hash identifying a notice
	 *
	 * @param	string	$string  The string to be hashed
	 * @return	string
	 */
	protected function crc_hash($string)
	{
		return str_pad(dechex(crc32($string) & 0xffffffff), 8, "0", STR_PAD_LEFT);
	}

} // End Notice
