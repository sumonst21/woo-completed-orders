<form method="post" id="wco-settings-form" action="" enctype="multipart/form-data">
	<input type="hidden" name="wco_settings_form" value="1" />

	<h2>WooCommerce Completed Orders Hook: Settings</h2>

    <table class="form-table">

        <tbody>

            <tr valign="top">
				<th scope="row">
					<label for="woocommerce_default_country">Base location</label>
                    <span class="woocommerce-help-tip"></span>
                </th>

                <td>
                    <input type="text" name="api_url" class="regular-text" value="<?php echo get_option('wco_api_url'); ?>" placeholder="http://yoursite/api/path" />
                </td>
            </tr>

        </tbody>
    </table>

	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Save" />
	</p>

</form>
