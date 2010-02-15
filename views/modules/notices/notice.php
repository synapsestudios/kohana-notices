<div id="notice-<?php echo $notice->hash ?>" class="notice <?php echo $notice->type ?>">
	<div class="notice-image">
		<?php echo HTML::image('media/images/notices/'.$notice->type.'.png') ?>
	</div>
	<div class="notice-content">
		<strong class="notice-type"><?php echo ucwords(__($notice->type)) ?>:</strong>
		<?php echo $notice->message ?>
	</div>
	<div style="clear: left;"></div>
</div>