<div id="notice-<?php echo $notice->hash ?>" class="notice-box <?php echo $notice->type ?>">
	<p class="notice-message">
		<strong class="notice-type"><?php echo ucwords(__($notice->type)) ?>:</strong>
		<?php echo $notice->message ?>
	</p>
</div>