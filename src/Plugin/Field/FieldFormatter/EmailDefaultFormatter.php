<?php

namespace Drupal\crm\Plugin\Field\FieldFormatter;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'crm_email_default' formatter.
 *
 * @FieldFormatter(
 *   id = "crm_email_default",
 *   label = @Translation("Default"),
 *   field_types = {"crm_email"}
 * )
 */
class EmailDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return ['foo' => 'bar'] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getSettings();
    $element['foo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Foo'),
      '#default_value' => $settings['foo'],
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $settings = $this->getSettings();
    $summary[] = $this->t('Foo: @foo', ['@foo' => $settings['foo']]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {

      $element[$delta]['email'] = [
        '#type' => 'item',
        '#title' => $this->t('Email'),
        'content' => [
          '#type' => 'link',
          '#title' => $item->email,
          '#url' => Url::fromUri('mailto:' . $item->email),
        ],
      ];

      if ($item->location_id) {
        $element[$delta]['location_id'] = [
          '#type' => 'item',
          '#title' => $this->t('Location'),
          '#markup' => $item->location_id,
        ];
      }

      if ($item->primary) {
        $element[$delta]['primary'] = [
          '#type' => 'item',
          '#title' => $this->t('Primary'),
          '#markup' => $item->primary ? $this->t('Yes') : $this->t('No'),
        ];
      }

      if ($item->billing) {
        $element[$delta]['billing'] = [
          '#type' => 'item',
          '#title' => $this->t('Billing'),
          '#markup' => $item->billing ? $this->t('Yes') : $this->t('No'),
        ];
      }

      if ($item->hold) {
        $element[$delta]['hold'] = [
          '#type' => 'item',
          '#title' => $this->t('Hold'),
          '#markup' => $item->hold ? $this->t('Yes') : $this->t('No'),
        ];
      }

      if ($item->bulk) {
        $element[$delta]['bulk'] = [
          '#type' => 'item',
          '#title' => $this->t('Bulk'),
          '#markup' => $item->bulk ? $this->t('Yes') : $this->t('No'),
        ];
      }

      if ($item->hold_date) {
        $date = DrupalDateTime::createFromFormat('Y-m-d\TH:i:s', $item->hold_date);
        // @DCG: Consider injecting the date formatter service.
        // @codingStandardsIgnoreStart
        $date_formatter = \Drupal::service('date.formatter');
        // @codingStandardsIgnoreStart
        $timestamp = $date->getTimestamp();
        $formatted_date = $date_formatter->format($timestamp, 'long');
        $iso_date = $date_formatter->format($timestamp, 'custom', 'Y-m-d\TH:i:s') . 'Z';
        $element[$delta]['hold_date'] = [
          '#type' => 'item',
          '#title' => $this->t('Hold Date'),
          'content' => [
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
        ];
      }

      if ($item->reset_date) {
        $date = DrupalDateTime::createFromFormat('Y-m-d\TH:i:s', $item->reset_date);
        // @DCG: Consider injecting the date formatter service.
        // @codingStandardsIgnoreStart
        $date_formatter = \Drupal::service('date.formatter');
        // @codingStandardsIgnoreStart
        $timestamp = $date->getTimestamp();
        $formatted_date = $date_formatter->format($timestamp, 'long');
        $iso_date = $date_formatter->format($timestamp, 'custom', 'Y-m-d\TH:i:s') . 'Z';
        $element[$delta]['reset_date'] = [
          '#type' => 'item',
          '#title' => $this->t('Reset Date'),
          'content' => [
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
        ];
      }

    }

    return $element;
  }

}
