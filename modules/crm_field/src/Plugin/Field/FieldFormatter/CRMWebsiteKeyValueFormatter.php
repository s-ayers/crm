<?php

namespace Drupal\crm_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\crm_field\Plugin\Field\FieldType\CRMWebsiteItem;

/**
 * Plugin implementation of the 'crm_website_key_value' formatter.
 *
 * @FieldFormatter(
 *   id = "crm_website_key_value",
 *   label = @Translation("Key-value"),
 *   field_types = {"crm_website"}
 * )
 */
class CRMWebsiteKeyValueFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $element = [];

    foreach ($items as $delta => $item) {

      $table = [
        '#type' => 'table',
      ];

      // Url.
      if ($item->url) {
        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Url'),
              ],
            ],
            [
              'data' => [
                '#markup' => $item->url,
              ],
            ],
          ],
          'no_striping' => TRUE,
        ];
      }

      // Type.
      if ($item->type) {
        $allowed_values = CRMWebsiteItem::allowedTypeValues();

        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Type'),
              ],
            ],
            [
              'data' => [
                '#markup' => $allowed_values[$item->type],
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
