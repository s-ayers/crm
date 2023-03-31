<?php

namespace Drupal\crm_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'crm_email_table' formatter.
 *
 * @FieldFormatter(
 *   id = "crm_email_table",
 *   label = @Translation("Table"),
 *   field_types = {"crm_email"}
 * )
 */
class EmailTableFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $header[] = '#';
    $header[] = $this->t('Email');
    $header[] = $this->t('Location');
    $header[] = $this->t('Primary');
    $header[] = $this->t('Billing');
    $header[] = $this->t('Hold');
    $header[] = $this->t('Bulk');
    $header[] = $this->t('Hold Date');
    $header[] = $this->t('Reset Date');

    $table = [
      '#type' => 'table',
      '#header' => $header,
    ];

    foreach ($items as $delta => $item) {
      $row = [];

      $row[]['#markup'] = $delta + 1;

      $row[]['#markup'] = $item->email;

      $row[]['#markup'] = $item->location_id;

      $row[]['#markup'] = $item->primary ? $this->t('Yes') : $this->t('No');

      $row[]['#markup'] = $item->billing ? $this->t('Yes') : $this->t('No');

      $row[]['#markup'] = $item->hold ? $this->t('Yes') : $this->t('No');

      $row[]['#markup'] = $item->bulk ? $this->t('Yes') : $this->t('No');

      if ($item->hold_date) {
        $date = DrupalDateTime::createFromFormat('Y-m-d\TH:i:s', $item->hold_date);
        $date_formatter = \Drupal::service('date.formatter');
        $timestamp = $date->getTimestamp();
        $formatted_date = $date_formatter->format($timestamp, 'long');
        $iso_date = $date_formatter->format($timestamp, 'custom', 'Y-m-d\TH:i:s') . 'Z';
        $row[] = [
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
        ];
      }
      else {
        $row[]['#markup'] = '';
      }

      if ($item->reset_date) {
        $date = DrupalDateTime::createFromFormat('Y-m-d\TH:i:s', $item->reset_date);
        $date_formatter = \Drupal::service('date.formatter');
        $timestamp = $date->getTimestamp();
        $formatted_date = $date_formatter->format($timestamp, 'long');
        $iso_date = $date_formatter->format($timestamp, 'custom', 'Y-m-d\TH:i:s') . 'Z';
        $row[] = [
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
        ];
      }
      else {
        $row[]['#markup'] = '';
      }

      $table[$delta] = $row;
    }

    return [$table];
  }

}
