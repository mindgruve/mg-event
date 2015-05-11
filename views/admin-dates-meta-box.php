<p><label for="start_date"><strong><?php _e('First Date') ?></strong></label></p>
<p><input type="text" id="start_date" class="" name="start_date" value="<?php echo htmlentities($startDate); ?>" /></p>

<?php /*
<p><label for="start_time"><strong><?php _e('Start Time') ?></strong></label></p>
<p>
    <select id="start_time_hour" name="start_time_hour">
        <?php for ($i = 1; $i<=12; $i++) { ?>
            <option value="<?php echo $i; ?>"<?php echo ($i == $startTimeHour) ? ' selected="selected"' : ''; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    :
    <select id="start_time_minute" name="start_time_minute">
        <?php for ($i = 0; $i<=59; $i++) { ?>
            <option value="<?php echo $i; ?>"<?php echo ($i == $startTimeMinute) ? ' selected="selected"' : ''; ?>><?php echo sprintf('%02d', $i); ?></option>
        <?php } ?>
    </select>

    <input type="radio" name="start_time_ampm" value="am"<?php echo ('am' == $startTimeAmpm) ? ' checked="checked"' : ''; ?>> am
    <input type="radio" name="start_time_ampm" value="pm"<?php echo ('pm' == $startTimeAmpm) ? ' checked="checked"' : ''; ?>> pm
</p>
*/ ?>

<p><label for="end_date"><strong><?php _e('Last Date') ?></strong></label></p>
<p>
    <input type="text" id="end_date" class="" name="end_date" value="<?php echo htmlentities($endDate); ?>" />
    <br /><span class="description">Leave this blank if entering a one-day event</span>
</p>

<?php /*
<p><label for="end_time"><strong><?php _e('End Time') ?></strong></label></p>
<p>
    <select id="end_time_hour" name="end_time_hour">
        <?php for ($i = 0; $i<=12; $i++) { ?>
            <option value="<?php echo $i; ?>"<?php echo ($i == $endTimeHour) ? ' selected="selected"' : ''; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    :
    <select id="end_time_minute" name="end_time_minute">
        <?php for ($i = 0; $i<=59; $i++) { ?>
            <option value="<?php echo $i; ?>"<?php echo ($i == $endTimeMinute) ? ' selected="selected"' : ''; ?>><?php echo sprintf('%02d', $i); ?></option>
        <?php } ?>
    </select>

    <input type="radio" name="end_time_ampm" value="am"<?php echo ('am' == $endTimeAmpm) ? ' checked="checked"' : ''; ?>> am
    <input type="radio" name="end_time_ampm" value="pm"<?php echo ('pm' == $endTimeAmpm) ? ' checked="checked"' : ''; ?>> pm

    <br /><span class="description">Set to same as start time if not specified</span>
</p>
*/ ?>
