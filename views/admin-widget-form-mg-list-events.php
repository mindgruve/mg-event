<p>
    <label for="<?php echo $this->get_field_id('title'); ?>">Title: </label>
    <input type='text' id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo htmlentities($instance['title']); ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('eventType'); ?>">Event Type: </label>
    <select id="<?php echo $this->get_field_id('eventType'); ?>" name="<?php echo $this->get_field_name('eventType'); ?>" >
        <?php foreach ($eventTypeOptions as $key => $value) { ?>
            <?php $selected = ($key == $instance['eventType']) ? " selected='selected'" : ''; ?>
            <option value="<?php echo $key; ?>"<?php echo $selected ?>><?php echo $value; ?></option>
        <?php } ?>
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('wordCount'); ?>">Word Limit: </label>
    <input type='text' id="<?php echo $this->get_field_id('wordCount'); ?>" name="<?php echo $this->get_field_name('wordCount'); ?>" value="<?php echo htmlentities($instance['wordCount']); ?>" />
</p>
<p>
    <input type='checkbox' class='checkbox' <?php if ($instance['showThumbnail']) echo " checked='checked'"; ?> id="<?php echo $this->get_field_id('showThumbnail'); ?>" name="<?php echo $this->get_field_name('showThumbnail'); ?>" />
    <label for="<?php echo $this->get_field_id('showThumbnail'); ?>">Display Thumbnail</label>
</p>
<p>
    <input type='checkbox' class='checkbox' <?php if ($instance['showDate']) echo " checked='checked'"; ?> id="<?php echo $this->get_field_id('showDate'); ?>" name="<?php echo $this->get_field_name('showDate'); ?>" />
    <label for="<?php echo $this->get_field_id('showDate'); ?>">Display Date</label>
</p>
<p>
    <label for="<?php echo $this->get_field_id('limit'); ?>">Limit: </label>
    <select id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" >
        <?php foreach ($limitOptions as $key => $value) { ?>
            <?php $selected = ($key == $instance['limit']) ? " selected='selected'" : ''; ?>
            <option value="<?php echo $key; ?>"<?php echo $selected ?>><?php echo $value; ?></option>
        <?php } ?>
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('orderBy'); ?>">Order By: </label>
    <select id="<?php echo $this->get_field_id('orderBy'); ?>" name="<?php echo $this->get_field_name('orderBy'); ?>" >
        <?php foreach ($orderByOptions as $key => $value) { ?>
            <?php $selected = ( $key == $instance['orderBy'] ) ? " selected='selected'" : ''; ?>
            <option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo $value; ?></option>
        <?php } ?>
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('order'); ?>">Order: </label>
    <select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" >
        <?php foreach ($orderOptions as $key => $value) { ?>
            <?php $selected = ( $key == $instance['order'] ) ? " selected='selected'" : ''; ?>
            <option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo $value; ?></option>
        <?php } ?>
    </select>
</p>


<hr />

<p>
    <label for="<?php echo $this->get_field_id('postCategory'); ?>">Post Category: </label>
    <select id="<?php echo $this->get_field_id('postCategory'); ?>" name="<?php echo $this->get_field_name('postCategory'); ?>" >
        <?php foreach ($categoryOptions as $key => $value) { ?>
            <?php $selected = ($key == $instance['postCategory']) ? " selected='selected'" : ''; ?>
            <option value="<?php echo $key; ?>"<?php echo $selected ?>><?php echo $value; ?></option>
        <?php } ?>
    </select>
</p>
<p>
    <input type='checkbox' class='checkbox' <?php if ($instance['postHasThumbnail']) echo " checked='checked'"; ?> id="<?php echo $this->get_field_id('postHasThumbnail'); ?>" name="<?php echo $this->get_field_name('postHasThumbnail'); ?>" />
    <label for="<?php echo $this->get_field_id('postHasThumbnail'); ?>">Require Thumbnail</label>
</p>
<p>
    <label for="<?php echo $this->get_field_id('postLimit'); ?>">Post Limit: </label>
    <select id="<?php echo $this->get_field_id('postLimit'); ?>" name="<?php echo $this->get_field_name('postLimit'); ?>" >
        <?php foreach ($limitOptions as $key => $value) { ?>
            <?php $selected = ($key == $instance['postLimit']) ? " selected='selected'" : ''; ?>
            <option value="<?php echo $key; ?>"<?php echo $selected ?>><?php echo $value; ?></option>
        <?php } ?>
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('postOrderBy'); ?>">Post Order By: </label>
    <select id="<?php echo $this->get_field_id('postOrderBy'); ?>" name="<?php echo $this->get_field_name('postOrderBy'); ?>" >
        <?php foreach ($orderByOptions as $key => $value) { ?>
            <?php $selected = ( $key == $instance['postOrderBy'] ) ? " selected='selected'" : ''; ?>
            <option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo $value; ?></option>
        <?php } ?>
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('postOrder'); ?>">Post Order: </label>
    <select id="<?php echo $this->get_field_id('postOrder'); ?>" name="<?php echo $this->get_field_name('postOrder'); ?>" >
        <?php foreach ($orderOptions as $key => $value) { ?>
            <?php $selected = ( $key == $instance['postOrder'] ) ? " selected='selected'" : ''; ?>
            <option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo $value; ?></option>
        <?php } ?>
    </select>
</p>