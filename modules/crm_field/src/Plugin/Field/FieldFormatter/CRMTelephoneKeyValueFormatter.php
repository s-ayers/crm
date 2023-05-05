<?php

namespace Drupal\crm_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\crm_field\Plugin\Field\FieldType\CRMtelephoneItem;

/**
 * Plugin implementation of the 'crm_telephone_key_value' formatter.
 *
 * @FieldFormatter(
 *   id = "crm_telephone_key_value",
 *   label = @Translation("Key-value"),
 *   field_types = {"crm_telephone"}
 * )
 */
class CRMTelephoneKeyValueFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $element = [];

    foreach ($items as $delta => $item) {

      $table = [
        '#type' => 'table',
      ];

      // Phone.
      if ($item->phone) {
        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Phone'),
              ],
            ],
            [
              'data' => [
                '#markup' => $item->phone,
              ],
            ],
          ],
          'no_striping' => TRUE,
        ];
      }

      // Extension.
      if ($item->phone_ext) {
        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Extension'),
              ],
            ],
            [
              'data' => [
                '#markup' => $item->phone_ext,
              ],
            ],
          ],
          'no_striping' => TRUE,
        ];
      }

      // Type.
      if ($item->type) {
        $allowed_values = CRMtelephoneItem::allowedTypeValues();

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

      // Location.
      if ($item->location_id) {
        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Location'),
              ],
            ],
            [
              'data' => [
                '#markup' => $item->location_id,
              ],
            ],
          ],
          'no_striping' => TRUE,
        ];
      }

      // Primary.
      if ($item->primary) {
        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Primary'),
              ],
            ],
            [
              'data' => [
                '#markup' => $item->primary ? $this->t('Yes') : $this->t('No'),
              ],
            ],
          ],
          'no_striping' => TRUE,
        ];
      }

      // Mobile Provider.
      if ($item->mobile_provider_id) {
        $allowed_values = CRMtelephoneItem::allowedMobileProviderValues();

        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Mobile Provider'),
              ],
            ],
            [
              'data' => [
                '#markup' => $allowed_values[$item->mobile_provider_id],
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
