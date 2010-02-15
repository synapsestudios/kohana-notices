<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Notices Controller
 *
 * @package    Notices
 * @version    v2.0.0b
 * @author     Jeremy Lindblom <jeremy@synapsestudios.com>
 * @copyright  Copyright (c) 2009 Synapse Studios
 */
class Controller_Notices extends Controller
{
	
	public function action_demo()
	{
		// Create one notice of each type (4th one is persistent)
		Notices::add('success', 'Hey y\'all, you just succeeded!');
		Notices::add('tip', 'Did you know that there is cheese available in the break room?', TRUE);
		Notices::warning('Be careful! There is something lurking around here. It smells like a herd of cattle but sounds like a grasshopper sneeze. What?... You\'ve never heard a grasshopper sneeze? Man, you are weird. We heard them all the time when we were kids. Great fun, those grasshoppers.');
		Notices::add('error', 'AAAARRRRRRRRGH!!!');

		// Echo the number of enqueued notices
		echo '<p>Number of notices in queue: '.Notices::count().'</p>';

		$this->request->redirect('notices/demo2');
	}

	public function action_demo2()
	{
		echo HTML::style('http://localhost/Kohana_v3.0/media/css/notices.css');
		echo '<h1>Notices Demo</h1>';
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