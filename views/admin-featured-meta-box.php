<p>
    <input type="checkbox" id="event_featured" class="" name="event_featured" <?php if($event_featured): ?>checked="checked" <?php endif;?> />
    <label for="event_featured"><?php _e('Feature this event in the slide show') ?></label>
</p>

<p>
	<label for="event_featured_title"><?php _e('Shortened title for featured event') ?></label>
	<input type="text" id="event_featured_title" name="event_featured_title" value="<?php echo $event_featured_title; ?>" class="regular-text">
</p>