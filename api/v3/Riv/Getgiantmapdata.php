<?php
use CRM_Reuzen_ExtensionUtil as E;

function _civicrm_api3_riv_Getgiantmapdata_spec(&$spec) {
}

function civicrm_api3_riv_Getgiantmapdata($params) {
  $returnArr = array();
  
  try {
    $sql = "
      select
        a.postal_code
        , a.city
        , c.id
        , c.display_name
      from
        civicrm_contact c
      inner join
        civicrm_address a on a.contact_id = c.id and a.is_primary = 1
      where
        c.contact_type = 'Individual'
        and c.contact_sub_type = concat(0x01, 'Reus', 0x01)
        and a.postal_code is not null
        and c.is_deceased = 0
        and c.is_deleted = 0
      order BY 
        a.postal_code
    ";

    $dao = CRM_Core_DAO::executeQuery($sql);

    while ($dao->fetch()) {
      $returnArr[] = array(
        'postal_code' => $dao->postal_code,
        'city' => $dao->city,
        'giant_id' => $dao->id,
        'giant_name' => $dao->display_name,
      );
    }
  }
  catch (Exception $e) {
    throw new API_Exception('Could not retrieve contact: ' . $e->getMessage(), 999);
  }

  return civicrm_api3_create_success($returnArr, $params);
}
