<?php

namespace Drupal\crm_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'crm_external_identifier_table' formatter.
 *
 * @FieldFormatter(
 *   id = "crm_external_identifier_table",
 *   label = @Translation("Table"),
 *   field_types = {"crm_external_identifier"}
 * )
 */
class ExternalIdentifierTableFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $header[] = '#';
    $header[] = $this->t('Source');
    $header[] = $this->t('Identifier');

    $table = [
      '#type' => 'table',
      '#header' => $header,
    ];

    foreach ($items as $delta => $item) {
      $row = [];

      $row[]['#markup'] = $delta + 1;

      $row[]['#markup'] = $item->source;

      $row[]['#markup'] = $item->identifier;

      $table[$delta] = $row;
    }

    return [$table];
  }

}
