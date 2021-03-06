<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  abstract class abstract_zoneable_module extends abstract_module {

    public function __construct() {
      parent::__construct();

      if ($this->enabled) {
        $this->update_status();
      }
    }

    abstract public function update_status();

    public function update_status_by($address) {
      $zone_id = $this->base_constant('ZONE') ?? 0;
      if ( $this->enabled && isset($address['zone_id'], $address['country']['id']) && ((int)$zone_id > 0) ) {
        $check_query = tep_db_query("SELECT zone_id FROM zones_to_geo_zones WHERE geo_zone_id = " . (int)$zone_id . " AND zone_country_id = " . (int)$address['country']['id'] . " ORDER BY zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if (($check['zone_id'] < 1) || ($check['zone_id'] == $address['zone_id'])) {
            return;
          }
        }

        $this->enabled = false;
      }
    }

  }

