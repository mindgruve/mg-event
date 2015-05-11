<table class="form-table">

    <tr>
        <th scope="row"><label for="donate_label"><?php _e('Donate Label') ?></label></th>
        <td><input type="text" id="donate_label" name="donate_label" value="<?php echo $donateLabel; ?>" class="regular-text"></td>
    </tr>

    <tr>
        <th scope="row"><label for="donate_url"><?php _e('Donate Url') ?></label></th>
        <td><input type="text" id="donate_url" name="donate_url" value="<?php echo $donateUrl; ?>" class="regular-text"></td>
    </tr>

    <tr>
        <th scope="row"><label for="email_recipient"><?php _e('Email Recipient') ?></label></th>
        <td><input type="text" id="email_recipient" name="email_recipient" cols="80" rows="10" value="<?php echo $emailRecipient; ?>" class="regular-text"></td>
    </tr>

</table>
