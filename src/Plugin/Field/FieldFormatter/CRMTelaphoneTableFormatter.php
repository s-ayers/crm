<?php

namespace Drupal\crm\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\crm\Plugin\Field\FieldType\CRMTelaphoneItem;

/**
 * Plugin implementation of the 'crm_telaphone_table' formatter.
 *
 * @FieldFormatter(
 *   id = "crm_telaphone_table",
 *   label = @Translation("Table"),
 *   field_types = {"crm_telaphone"}
 * )
 */
class CRMTelaphoneTableFormatter extends FormatterBase {

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
    $header[] = $this->t('Billing');
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
        $allowed_values = CRMTelaphoneItem::allowedTypeValues();
        $row[]['#markup'] = $allowed_values[$item->type];
      }
      else {
        $row[]['#markup'] = '';
      }

      $row[]['#markup'] = $item->location_id;

      $row[]['#markup'] = $item->primary ? $this->t('Yes') : $this->t('No');

      $row[]['#markup'] = $item->billing ? $this->t('Yes') : $this->t('No');

      if ($item->mobile_provider_id) {
        $allowed_values = CRMTelaphoneItem::allowedMobileProviderValues();
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
