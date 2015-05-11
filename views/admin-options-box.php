<?php

$eventsPolicyPdf = isset($eventsPolicyPdf) ? $eventsPolicyPdf : '';
$nonceField = isset($nonceField) ? $nonceField : '';

?>
<div class="wrap">
    <h2 class="dashicons-before dashicons-calendar"> Event Settings</h2>

    <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
        <?php echo $nonceField; ?>

        <table class="form-table">

            <tr>
                <th scope="row"><label for="mg_event_policy_pdf"><?php _e('Event Policies PDF') ?></label></th>
                <td><input type="text" id="mg_event_policy_pdf" name="mg_event_policy_pdf" value="<?php echo $eventsPolicyPdf; ?>" class="regular-text"></td>
            </tr>

        </table>
        
        <?php submit_button(); ?>
    </form>
</div>


