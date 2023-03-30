<?php

namespace Drupal\crm\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\crm\Plugin\Field\FieldType\CRMTelaphoneItem;

/**
 * Plugin implementation of the 'crm_telaphone_default' formatter.
 *
 * @FieldFormatter(
 *   id = "crm_telaphone_default",
 *   label = @Translation("Default"),
 *   field_types = {"crm_telaphone"}
 * )
 */
class CRMTelaphoneDefaultFormatter extends FormatterBase {

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

      if ($item->phone) {
        $element[$delta]['phone'] = [
          '#type' => 'item',
          '#title' => $this->t('Phone'),
          'content' => [
            '#type' => 'link',
            '#title' => $item->phone,
            '#url' => Url::fromUri('tel:' . rawurlencode(preg_replace('/\s+/', '', $item->phone))),
          ],
        ];
      }

      if ($item->phone_ext) {
        $element[$delta]['phone_ext'] = [
          '#type' => 'item',
          '#title' => $this->t('Extension'),
          '#markup' => $item->phone_ext,
        ];
      }

      if ($item->type) {
        $allowed_values = CRMTelaphoneItem::allowedTypeValues();
        $element[$delta]['type'] = [
          '#type' => 'item',
          '#title' => $this->t('Type'),
          '#markup' => $allowed_values[$item->type],
        ];
      }

      if ($item->location_id) {
        $element[$delta]['location_id'] = [
          '#type' => 'item',
          '#title' => $this->t('Location'),
          '#markup' => $item->location_id,
        ];
      }

      $element[$delta]['primary'] = [
        '#type' => 'item',
        '#title' => $this->t('Primary'),
        '#markup' => $item->primary ? $this->t('Yes') : $this->t('No'),
      ];

      $element[$delta]['billing'] = [
        '#type' => 'item',
        '#title' => $this->t('Billing'),
        '#markup' => $item->billing ? $this->t('Yes') : $this->t('No'),
      ];

      if ($item->mobile_provider_id) {
        $allowed_values = CRMTelaphoneItem::allowedMobileProviderValues();
        $element[$delta]['mobile_provider_id'] = [
          '#type' => 'item',
          '#title' => $this->t('Mobile Provider'),
          '#markup' => $allowed_values[$item->mobile_provider_id],
        ];
      }

    }

    return $element;
  }

}
