<?php

namespace Drupal\crm_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'crm_external_identifier_key_value' formatter.
 *
 * @FieldFormatter(
 *   id = "crm_external_identifier_key_value",
 *   label = @Translation("Key-value"),
 *   field_types = {"crm_external_identifier"}
 * )
 */
class ExternalIdentifierKeyValueFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $element = [];

    foreach ($items as $delta => $item) {

      $table = [
        '#type' => 'table',
      ];

      // Source.
      if ($item->source) {
        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Source'),
              ],
            ],
            [
              'data' => [
                '#markup' => $item->source,
              ],
            ],
          ],
          'no_striping' => TRUE,
        ];
      }

      // Identifier.
      if ($item->identifier) {
        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Identifier'),
              ],
            ],
            [
              'data' => [
                '#markup' => $item->identifier,
              ],
            ],
          ],
          'no_striping' => TRUE,
        ];
      }

      $element[$delta] = $table;

    }

    return $element;
  }

}
