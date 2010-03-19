<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="en-us" />
		<title>Notices Demo</title>
		<?php echo HTML::style('media/css/notices.css') ?>
		<?php echo HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js') ?>
		<?php echo HTML::script('media/js/notices.js') ?>
		<script type="text/javascript">
			$(function(){
				$('#add_notice').click(function(){
					var type = $('#type').val();
					var message = $('#message').val();
					var persist = $('#persist').val();
					persist = (persist == 'TRUE');
					$('#notices-container').add_notice(type, message, persist);
				});
			});
		</script>
	</head>
	<body>
		<h1>Notices Demo</h1>
		<p>Number of notices in queue <em>before</em> display: <?php echo Notices::count() ?></p>

		<div id="notices-container">
			<?php echo Notices::display() ?>
		</div>

		<br />

		<fieldset>
			<legend><h2>Add a Notice via AJAX</h2></legend>
			<p>
				<label for="type">Notice Type (can be anything):</label>
				<br />
				<input id="type" type="text" />
			</p>
			<p>
				<label for="message">Message:</label>
				<br />
				<textarea id="message" rows="3" cols="30"></textarea>
			</p>
			<p>
				<label>Persistent?</label>
				<br />
				<select id="persist">
					<option value="FALSE">No</option>
					<option value="TRUE">Yes</option>
				</select>
			</p>
			<p>
				<button id="add_notice" type="button">Add a Notice</button>
			</p>
		</fieldset>
	</body>
</html>