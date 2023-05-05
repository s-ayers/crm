<?php

namespace Drupal\crm_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\crm_field\Plugin\Field\FieldType\CRMtelephoneItem;

/**
 * Plugin implementation of the 'crm_telephone_table' formatter.
 *
 * @FieldFormatter(
 *   id = "crm_telephone_table",
 *   label = @Translation("Table"),
 *   field_types = {"crm_telephone"}
 * )
 */
class CRMTelephoneTableFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $header[] = '#';
    $header[] = $this->t('Phone');
    $header[] = $this->t('Extension');
    $header[] = $this->t('Type');
    $header[] = $this->t('Location');
    $header[] = $this->t('Primary');
    $header[] = $this->t('Mobile Provider');

    $table = [
      '#type' => 'table',
      '#header' => $header,
    ];

    foreach ($items as $delta => $item) {
      $row = [];

      $row[]['#markup'] = $delta + 1;

      $row[]['#markup'] = $item->phone;

      $row[]['#markup'] = $item->phone_ext;

      if ($item->type) {
        $allowed_values = CRMtelephoneItem::allowedTypeValues();
        $row[]['#markup'] = $allowed_values[$item->type];
      }
      else {
        $row[]['#markup'] = '';
      }

      $row[]['#markup'] = $item->location_id;

      $row[]['#markup'] = $item->primary ? $this->t('Yes') : $this->t('No');

      if ($item->mobile_provider_id) {
        $allowed_values = CRMtelephoneItem::allowedMobileProviderValues();
        $row[]['#markup'] = $allowed_values[$item->mobile_provider_id];
      }
      else {
        $row[]['#markup'] = '';
      }

      $table[$delta] = $row;
    }

    return [$table];
  }

}
