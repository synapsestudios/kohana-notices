<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Unique Notice - Extends the Notice object by enforcing uniqueness of a Notice
 *
 * @package    Notices
 * @author     Jeremy Lindblom <jeremy@synapsestudios.com>
 * @copyright  Copyright (c) 2009 Synapse Studios
 */
class Kohana_Notice_Unique extends Notice
{
	/**
	 * Creates a notice and ensures the type/message combo is unique
	 *
	 * @param	string	 $type
	 * @param	string	 $msg_key
	 * @param	array	 $values
	 * @param	boolean	 $persistent
	 */
	public function __construct($type, $msg_key, array $values = NULL, $persistent = FALSE)
	{
		parent::__construct($type, $msg_key, $values, $persistent);

		foreach (Notices::get_all($this->type) as $notice)
		{
			if ($this->similar_to($notice))
				throw new Exception('The new notice is not unique.');
		}
	}

} // End Notice_Unique
