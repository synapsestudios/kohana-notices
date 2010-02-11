<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Notices Helper class
 *
 * @package    Notices
 * @version    v1.0
 * @author     Jeremy Lindblom <jeremy@synapsestudios.com>
 * @copyright  Copyright (c) 2009 Synapse Studios
 */
/**
 * xx
 */
class Controller_Notices extends Controller
{
	
	public function action_demo()
	{
		echo '<h1>Notices Demo Part I</h1>';

		// Create one notice of each type (4th one is persistent)
		Notices::add('success', 'Hey y\'all, you just succeeded!');
		Notices::add('cheese', 'Did you just get cheesed?', TRUE);
		Notices::warning('Be careful!');
		Notices::add('error', 'AAAARRRRRRRRGH!!!');

		// Echo the number of enqueued notices
		echo '<p>Number of notices in queue: '.Notices::count().'</p>';

		$this->request->redirect('notices/demo2');
	}

	public function action_demo2()
	{
		echo '<p>Number of notices in queue: '.Notices::count().'</p>';
		echo Notices::display();
	}

	public function action_clear()
	{
		Notices::clear(NULL, NULL, NULL);
		echo '<p>Number of notices in queue: '.Notices::count().'</p>';
	}

	public function action_remove($hash)
	{
		// check if ajax

		$response = array(
			'status'	=> 'success',
			'message'	=> 'Notice '.$hash.' was removed.',
			'data'		=> NULL
		);

		$notice = Notices::get($hash);
		if ( ! is_null($notice))
		{
			$notice->set_rendered_state(TRUE);
			$notice->remove_persistence();
			$response['data'] = $notice;
			Notices::save();
		}
		else
		{
			$response['status'] = 'error';
			$response['message'] = 'Notice '.$hash.' was not found.';
		}

		$this->request->response = json_encode($response);
	}
	
} // End Notices Controller