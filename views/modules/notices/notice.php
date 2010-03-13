<div id="notice-<?php echo $notice->hash ?>" class="notice <?php echo $notice->type ?>">
	<div class="notice-image">
		<?php echo HTML::image('media/images/notices/'.$notice->type.'.png') ?>
	</div>
	<div class="notice-content">
		<strong class="notice-type"><?php echo ucwords(__($notice->type)) ?>:</strong>
		<?php echo $notice->message ?>
	</div>
	<div class="notice-close">
		<?php echo HTML::anchor('notices/remove/'.$notice->hash, HTML::image('media/images/notices/notice-close.png')) ?>
	</div>
	<div class="notice-clear"></div>
</div>