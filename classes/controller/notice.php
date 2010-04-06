<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Notices Controller
 *
 * @package    Notices
 * @author     Jeremy Lindblom <jeremy@synapsestudios.com>
 * @copyright  Copyright (c) 2009 Synapse Studios
 */
class Controller_Notice extends Controller
{
	/**
	 * Runs the Notices Module Demo
	 *
	 * @param   string  id
	 */
	public function action_demo()
	{
		if ($this->request->param('id', 'initialize') == 'display')
		{
			$this->request->response = View::factory('notices/demo');
		}
		else
		{
			Notices::clear(NULL, NULL, NULL);

			// Create one notice of each type (the 'tip' is persistent)
			Notices::add('denied', 'You do not have permission to view this page.');
			Notices::add('error', 'An unknown error has occurred.');
			Notices::add('event', 'Your wedding has been schedule for '.date('m/d/Y').'!');
			Notices::add('help', 'Please see the '.HTML::anchor('guide', 'user guide').' for more help');
			Notices::add('info', 'Some notices can be very long. They shouldn\'t be in typical cases, but sometimes you just need a little extra room to provide information to the user. The CSS for notices allows this to happen much better than the previous version of the notices module. In fact, I think this version can display them very well&hellip; except for in Internet Exploder. It\'s sad that IE doesn\'t support rounded corners. Otherwise, it would be just as pretty. However, I didn get the gradients to work, so it\'s not too bad.');
			Notices::add('message', 'Hey! I just wanted to send you a litle message.');
			Notices::add('success', 'Congratulations! You are now an ultra, elite member!');
			Notices::add('tip', 'Did you know that you can create persistent notices? Actually, this one is persistent. Go ahead and refresh the page. It won\'t go away until you click the "X".', TRUE);
			Notices::add('warning', 'The operation you have requested could potentially delete over 100 records. You are you sure you want to proceed? '.HTML::anchor('#', 'Yes').' or '.HTML::anchor('#', 'No'));
			Notices::add('wizard', 'This is a demonstration of the notices module!');

			$this->request->redirect('notice/demo/display');
		}
	}

	/**
	 * Clears the Notices queue
	 */
	public function action_clear()
	{
		Notices::clear(NULL, NULL, NULL);
		echo '<p>Number of notices in queue: '.Notices::count().'</p>';
	}

	/**
	 * Removes a persistent Notice
	 *
	 * @ajax
	 * @param   string  id
	 */
	public function action_remove()
	{
		if ( ! Request::$is_ajax)
			throw new Kohana_Request_Exception('Trying to access an AJAX method without AJAX.');

		$hash = $this->request->param('id');

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

	/**
	 * Adds a new Notice and sends the rendered HTML as a repsonse
	 *
	 * @ajax
	 * @param   string  type
	 * @param   string  message
	 * @param   string  persist
	 */
	public function action_add()
	{
		if ( ! Request::$is_ajax)
			throw new Kohana_Request_Exception('Trying to access an AJAX method without AJAX.');

		$type = strtolower(urldecode($this->request->param('type')));
		$message = urldecode($this->request->param('message'));
		$persist = (bool) $this->request->param('persist');

		$response = array(
			'status'	=> 'success',
			'message'	=> 'A Notice was added.',
			'data'		=> NULL
		);

		try
		{
			$type = urldecode($type);
			$message = urldecode($message);
			$persist = (bool) $persist == 'TRUE';

			$notice = Notices::add($type, $message, $persist);
			$response['message'] = 'Notice '.$notice->hash.' was added.';
			$response['data'] = $notice->render();
			Notices::save();
		}
		catch (Exception $e)
		{
			$response['status'] = 'error';
			$response['message'] = 'The was a problem adding the Notice.';
		}

		$this->request->response = json_encode($response);
	}
	
} // End Notice Controller
