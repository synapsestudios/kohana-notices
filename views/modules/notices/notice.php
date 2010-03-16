<div id="notice-<?php echo $notice->hash ?>" class="notice <?php echo $notice->type ?>">
	<div class="notice-image">
		<?php echo Notices::image($notice->type, array('width' => 32, 'height' => 32, 'alt' => ucwords(__($notice->type)))) ?>
	</div>
	<div class="notice-content">
		<strong class="notice-type"><?php echo ucwords(__($notice->type)) ?>:</strong>
		<?php echo $notice->message ?>
	</div>
	<div class="notice-close">
		<?php echo HTML::anchor(
			Route::get('notice-remove')->uri(array('hash' => $notice->hash)),
			HTML::image(
				'media/images/notices/notice-close.png',
				array('width' => 16, 'height' => '16', 'alt' => __('Close'))),
			array('title' => __('Close'))
		) ?>
	</div>
	<div class="notice-clear"></div>
</div>