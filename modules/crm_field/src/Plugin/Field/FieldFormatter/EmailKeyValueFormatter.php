<?php

namespace Drupal\crm_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'crm_email_key_value' formatter.
 *
 * @FieldFormatter(
 *   id = "crm_email_key_value",
 *   label = @Translation("Key-value"),
 *   field_types = {"crm_email"}
 * )
 */
class EmailKeyValueFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $element = [];

    foreach ($items as $delta => $item) {

      $table = [
        '#type' => 'table',
      ];

      // Email.
      if ($item->email) {
        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Email'),
              ],
            ],
            [
              'data' => [
                '#markup' => $item->email,
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

      // Billing.
      if ($item->billing) {
        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Billing'),
              ],
            ],
            [
              'data' => [
                '#markup' => $item->billing ? $this->t('Yes') : $this->t('No'),
              ],
            ],
          ],
          'no_striping' => TRUE,
        ];
      }

      // Hold.
      if ($item->hold) {
        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Hold'),
              ],
            ],
            [
              'data' => [
                '#markup' => $item->hold ? $this->t('Yes') : $this->t('No'),
              ],
            ],
          ],
          'no_striping' => TRUE,
        ];
      }

      // Bulk.
      if ($item->bulk) {
        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Bulk'),
              ],
            ],
            [
              'data' => [
                '#markup' => $item->bulk ? $this->t('Yes') : $this->t('No'),
              ],
            ],
          ],
          'no_striping' => TRUE,
        ];
      }

      // Hold Date.
      if ($item->hold_date) {
        $date = DrupalDateTime::createFromFormat('Y-m-d\TH:i:s', $item->hold_date);
        $date_formatter = \Drupal::service('date.formatter');
        $timestamp = $date->getTimestamp();
        $formatted_date = $date_formatter->format($timestamp, 'long');
        $iso_date = $date_formatter->format($timestamp, 'custom', 'Y-m-d\TH:i:s') . 'Z';

        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Hold Date'),
              ],
            ],
            [
              'data' => [
                '#theme' => 'time',
                '#text' => $formatted_date,
                '#html' => FALSE,
                '#attributes' => [
                  'datetime' => $iso_date,
                ],
                '#cache' => [
                  'contexts' => [
                    'timezone',
                  ],
                ],
              ],
            ],
          ],
          'no_striping' => TRUE,
        ];
      }

      // Reset Date.
      if ($item->reset_date) {
        $date = DrupalDateTime::createFromFormat('Y-m-d\TH:i:s', $item->reset_date);
        $date_formatter = \Drupal::service('date.formatter');
        $timestamp = $date->getTimestamp();
        $formatted_date = $date_formatter->format($timestamp, 'long');
        $iso_date = $date_formatter->format($timestamp, 'custom', 'Y-m-d\TH:i:s') . 'Z';

        $table['#rows'][] = [
          'data' => [
            [
              'header' => TRUE,
              'data' => [
                '#markup' => $this->t('Reset Date'),
              ],
            ],
            [
              'data' => [
                '#theme' => 'time',
                '#text' => $formatted_date,
                '#html' => FALSE,
                '#attributes' => [
                  'datetime' => $iso_date,
                ],
                '#cache' => [
                  'contexts' => [
                    'timezone',
                  ],
                ],
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
