<table class="form-table">

    <tr>
        <th scope="row"><label for="type"><?php _e('Type') ?></label></th>
        <td>
            <select name='type' id='type'>
                <?php
                foreach ($eventTypes as $eventType) {
                    if (!is_wp_error($types) && !empty($types) && !strcmp($eventType->slug, $types[0]->slug)) {
                        echo "<option class='theme-option' value='" . $eventType->slug . "' selected>" . $eventType->name . "</option>\n";
                    } else {
                        echo "<option class='theme-option' value='" . $eventType->slug . "'>" . $eventType->name . "</option>\n";
                    }
                }
                ?>
            </select>
        </td>
    </tr>

</table>
