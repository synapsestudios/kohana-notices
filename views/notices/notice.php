<div id="notice-<?php echo $notice->hash ?>" class="notice <?php echo $notice->type ?><?php echo $notice->is_persistent ? ' notice-persistent' : '' ?>">
	<div class="notice-content">
		<strong class="notice-type"><?php echo ucwords(__($notice->type)) ?>:</strong>
		<?php echo $notice->message ?>
	</div>
	<div class="notice-close">
		<?php echo HTML::anchor(
			Route::get('notice-remove')->uri(array('hash' => $notice->hash)),
			__('Close'),
			array('title' => __('Close'))
		) ?>
	</div>
	<div class="notice-clear"></div>
</div>
