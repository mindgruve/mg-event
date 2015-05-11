<table class="form-table">

    <tr>
        <th scope="row"><label for="venue"><?php _e('Venue') ?></label></th>
        <td><input type="text" id="venue" class="large-text" name="venue" value="<?php echo htmlentities($venue); ?>" /></td>
    </tr>

    <tr>
        <th scope="row"><label for="address1"><?php _e('Address 1') ?></label></th>
        <td><input type="text" id="address1" class="large-text" name="address1" value="<?php echo htmlentities($address1); ?>" /></td>
    </tr>

    <tr>
        <th scope="row"><label for="address1"><?php _e('Address 2'); ?></label></th>
        <td><input type="text" id="address2" class="large-text" name="address2" value="<?php echo htmlentities($address2); ?>" /></td>
    </tr>

    <tr>
        <th scope="row"><label for="city"><?php _e('City'); ?></label></th>
        <td><input type="text" id="city" class="regular-text" name="city" value="<?php echo htmlentities($city); ?>" /></td>
    </tr>

    <tr>
        <th scope="row"><label for="region"><?php _e('State'); ?></label></th>
        <td><input type="text" id="region" class="regular-text" name="region" value="<?php echo htmlentities($region); ?>" /></td>
    </tr>

    <tr>
        <th scope="row"><label for="country"><?php _e('Country'); ?></label></th>
        <td><input type="text" id="country" class="regular-text" name="country" value="<?php echo htmlentities($country); ?>" /></td>
    </tr>

    <tr>
        <th scope="row"><label for="postal_code"><?php _e('Postal Code'); ?></label></th>
        <td><input type="text" id="postal_code" class="regular-text" name="postal_code" value="<?php echo htmlentities($postal_code); ?>" /></td>
    </tr>

    <tr>
        <th scope="row"><label for="latitude"><?php _e('Latitude'); ?></label></th>
        <td><input type="text" id="latitude" class="regular-text" name="latitude" value="<?php echo htmlentities($latitude); ?>" /></td>
    </tr>

    <tr>
        <th scope="row"><label for="longitude"><?php _e('Longitude'); ?></label></th>
        <td><input type="text" id="longitude" class="regular-text" name="longitude" value="<?php echo htmlentities($longitude); ?>" /></td>
    </tr>

    <tr>
        <th>&nbsp;</th>
        <td>
            <input type='submit' class='button-secondary' id='google-map-query' value='Update Coordinates' />
            <span id='map-query-status' style='font-weight: normal; font-size: 0.8em; margin-left: 10px;'></span>
        </td>
    </tr>

</table>
