<div class="search-container type2">
    <div class="dt-sc-tabs-container">
        <ul class="dt-sc-tabs-frame">
            <li><a href="#" class="current"><?php _e('Hotels', 'iamd_text_domain'); ?></a></li>
            <li><a href="#"><?php _e('Packages', 'iamd_text_domain'); ?></a></li>
            <li><a href="#"><?php _e('Places', 'iamd_text_domain'); ?></a></li>
        </ul>
        <div class="dt-sc-tabs-frame-content"><?php
			//Hotels Search Module...
			$action = dt_theme_page_permalink_by_its_template('tpl-hotels-search.php');
			$smodule = dt_theme_option("smodule"); ?>
            <form name="frmhotelsearch" action="<?php echo $action; ?>" method="post"><?php

				if(array_key_exists("enable-title-module-for-hotels", $smodule )): ?>
	                <p><input type="text" name="txthotelname" placeholder="<?php _e('Type Hotel name here...', 'iamd_text_domain'); ?>" /></p><?php
				endif;
				
				if(array_key_exists("enable-location-for-hotels", $smodule )): ?>
                    <p><select name="cmbcity">
                        <option value=""><?php _e('Choose City', 'iamd_text_domain'); ?></option><?php
						$hotel_cities = array_key_exists("location-for-hotels", $smodule) ? $smodule["location-for-hotels"] : array();
						$hotel_cities = array_filter($hotel_cities);
						$hotel_cities = array_unique($hotel_cities);
						foreach ( $hotel_cities as $hotel_city ) {
							$selected = "";
							echo  "<option value='{$hotel_city}' {$selected} >{$hotel_city}</option>";
						} ?>
                    </select></p><?php
				endif;
				
				if(array_key_exists("enable-type-module-for-hotels", $smodule )): ?>
                    <p><select name="cmbcat">
                        <option value=""><?php _e('Choose Category', 'iamd_text_domain'); ?></option><?php
						$hotel_types = get_categories("taxonomy=hotel_entries&hide_empty=1");
						foreach ( $hotel_types as $hotel_type ) {
							$id = esc_attr( $hotel_type->slug );
							$title = esc_html( $hotel_type->name );
							$selected = "";
							echo  "<option value='{$id}' {$selected} >{$title}</option>";
						} ?>
                    </select></p><?php
				endif;
				
				if(array_key_exists("enable-min-price-for-hotels", $smodule )): ?>
                    <p class="select-price"><select name="cmbminprice">
                        <option value=""><?php _e('Min Price', 'iamd_text_domain'); ?></option><?php
                            $min_prices = array_key_exists("min-price-for-hotels", $smodule) ? $smodule["min-price-for-hotels"] : array();
                            $min_prices = array_filter($min_prices);
                            $min_prices = array_unique($min_prices);
                            foreach ( $min_prices as $min_price ) {
                                $selected = "";
                                echo  "<option value='{$min_price}' {$selected} >{$min_price}</option>";
                            } ?>
                    </select></p><?php
				endif;
				
				if(array_key_exists("enable-max-price-for-hotels", $smodule )): ?>
                    <p class="select-price price-last"><select name="cmbmaxprice">
                        <option value=""><?php _e('Max Price', 'iamd_text_domain'); ?></option><?php
                            $max_prices = array_key_exists("max-price-for-hotels", $smodule) ? $smodule["max-price-for-hotels"] : array();
                            $max_prices = array_filter($max_prices);
                            $max_prices = array_unique($max_prices);
                            foreach ( $max_prices as $max_price ) {
                                $selected = "";
                                echo  "<option value='{$max_price}' {$selected} >{$max_price}</option>";
                            } ?>
                    </select></p><?php
				endif;
				
				if(array_key_exists("enable-offer-for-hotels", $smodule )): ?>
                    <p><select name="cmboffers">
                        <option value=""><?php _e('Choose Offer', 'iamd_text_domain'); ?></option><?php
                            $offers = array_key_exists("offer-for-hotels", $smodule) ? $smodule["offer-for-hotels"] : array();
                            $offers = array_filter($offers);
                            $offers = array_unique($offers);
                            foreach ( $offers as $offer ) {
                                $selected = "";
                                echo  "<option value='{$offer}' {$selected} >{$offer}</option>";
                            } ?>
                    </select></p><?php
				endif; ?>
                <input name="subsearch" type="submit" value="<?php _e('Find Hotels', 'iamd_text_domain'); ?>" />
            </form>
        </div>
        <div class="dt-sc-tabs-frame-content"><?php
			//Packages Search Module...
			$action = dt_theme_page_permalink_by_its_template('tpl-packages-search.php');
			$smodule = dt_theme_option("smodule"); ?>
            <form name="frmpackagesearch" action="<?php echo $action; ?>" method="post"><?php

				if(array_key_exists("enable-title-module-for-packages", $smodule )): ?>
	                <p><input type="text" name="txtpackagename" placeholder="<?php _e('Type Package name here...', 'iamd_text_domain'); ?>" /></p><?php
				endif;
				
				if(array_key_exists("enable-location-for-packages", $smodule )): ?>
                    <p><select name="cmbcity">
                        <option value=""><?php _e('Choose City', 'iamd_text_domain'); ?></option><?php
						$package_cities = array_key_exists("location-for-packages", $smodule) ? $smodule["location-for-packages"] : array();
						$package_cities = array_filter($package_cities);
						$package_cities = array_unique($package_cities);
						foreach ( $package_cities as $package_city ) {
							$selected = "";
							echo  "<option value='{$package_city}' {$selected} >{$package_city}</option>";
						} ?>
                    </select></p><?php
				endif;
				
				if(array_key_exists("enable-type-module-for-packages", $smodule )): ?>
                    <p><select name="cmbcat">
                        <option value=""><?php _e('Choose Category', 'iamd_text_domain'); ?></option><?php
						$package_types = get_categories("taxonomy=product_cat&hide_empty=1");
						foreach ( $package_types as $package_type ) {
							$id = esc_attr( $package_type->slug );
							$title = esc_html( $package_type->name );
							$selected = "";
							echo  "<option value='{$id}' {$selected} >{$title}</option>";
						} ?>
                    </select></p><?php
				endif;
				
				if(array_key_exists("enable-min-price-for-packages", $smodule )): ?>
                    <p class="select-price"><select name="cmbminprice">
                        <option value=""><?php _e('Min Price', 'iamd_text_domain'); ?></option><?php
                            $min_prices = array_key_exists("min-price-for-packages", $smodule) ? $smodule["min-price-for-packages"] : array();
                            $min_prices = array_filter($min_prices);
                            $min_prices = array_unique($min_prices);
                            foreach ( $min_prices as $min_price ) {
                                $selected = "";
                                echo  "<option value='{$min_price}' {$selected} >{$min_price}</option>";
                            } ?>
                    </select></p><?php
				endif;
				
				if(array_key_exists("enable-max-price-for-packages", $smodule )): ?>
                    <p class="select-price price-last"><select name="cmbmaxprice">
                        <option value=""><?php _e('Max Price', 'iamd_text_domain'); ?></option><?php
                            $max_prices = array_key_exists("max-price-for-packages", $smodule) ? $smodule["max-price-for-packages"] : array();
                            $max_prices = array_filter($max_prices);
                            $max_prices = array_unique($max_prices);
                            foreach ( $max_prices as $max_price ) {
                                $selected = "";
                                echo  "<option value='{$max_price}' {$selected} >{$max_price}</option>";
                            } ?>
                    </select></p><?php
				endif;
				
				if(array_key_exists("enable-persons-for-packages", $smodule )): ?>
                    <p><select name="cmbpersons">
                        <option value=""><?php _e('Choose No.of Persons', 'iamd_text_domain'); ?></option><?php
                            $persons = array_key_exists("persons-for-packages", $smodule) ? $smodule["persons-for-packages"] : array();
                            $persons = array_filter($persons);
                            $persons = array_unique($persons);
                            foreach ( $persons as $person ) {
                                $selected = "";
                                echo  "<option value='{$person}' {$selected} >{$person}</option>";
                            } ?>
                    </select></p><?php
				endif; ?>
                <input name="subsearch" type="submit" value="<?php _e('Find Packages', 'iamd_text_domain'); ?>" />
            </form>
        </div>
        <div class="dt-sc-tabs-frame-content"><?php
			//Places Search Module...
			$action = dt_theme_page_permalink_by_its_template('tpl-places-search.php');
			$smodule = dt_theme_option("smodule"); ?>
            <form name="frmplacesearch" action="<?php echo $action; ?>" method="post"><?php

				if(array_key_exists("enable-title-module-for-places", $smodule )): ?>
	                <p><input type="text" name="txtplacename" placeholder="<?php _e('Type Place name here...', 'iamd_text_domain'); ?>" /></p><?php
				endif;
				
				if(array_key_exists("enable-location-for-places", $smodule )): ?>
                    <p><select name="cmbcity">
                        <option value=""><?php _e('Choose City', 'iamd_text_domain'); ?></option><?php
						$places_cities = array_key_exists("location-for-places", $smodule) ? $smodule["location-for-places"] : array();
						$places_cities = array_filter($places_cities);
						$places_cities = array_unique($places_cities);
						foreach ( $places_cities as $place_city ) {
							$selected = "";
							echo  "<option value='{$place_city}' {$selected} >{$place_city}</option>";
						} ?>
                    </select></p><?php
				endif;
				
				if(array_key_exists("enable-type-module-for-places", $smodule )): ?>
                    <p><select name="cmbcat">
                        <option value=""><?php _e('Choose Category', 'iamd_text_domain'); ?></option><?php
						$place_types = get_categories("taxonomy=place_entries&hide_empty=1");
						foreach ( $place_types as $place_type ) {
							$id = esc_attr( $place_type->slug );
							$title = esc_html( $place_type->name );
							$selected = "";
							echo  "<option value='{$id}' {$selected} >{$title}</option>";
						} ?>
                    </select></p><?php
				endif; ?>
                <input name="subsearch" type="submit" value="<?php _e('Find Places', 'iamd_text_domain'); ?>" />
            </form>
        </div>
    </div>
</div>