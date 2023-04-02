<?php

namespace Drupal\crm_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\crm_field\Plugin\Field\FieldType\CRMWebsiteItem;

/**
 * Plugin implementation of the 'crm_website_table' formatter.
 *
 * @FieldFormatter(
 *   id = "crm_website_table",
 *   label = @Translation("Table"),
 *   field_types = {"crm_website"}
 * )
 */
class CRMWebsiteTableFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $header[] = '#';
    $header[] = $this->t('Url');
    $header[] = $this->t('Type');

    $table = [
      '#type' => 'table',
      '#header' => $header,
    ];

    foreach ($items as $delta => $item) {
      $row = [];

      $row[]['#markup'] = $delta + 1;

      $row[]['#markup'] = $item->url;

      if ($item->type) {
        $allowed_values = CRMWebsiteItem::allowedTypeValues();
        $row[]['#markup'] = $allowed_values[$item->type];
      }
      else {
        $row[]['#markup'] = '';
      }

      $table[$delta] = $row;
    }

    return [$table];
  }

}
