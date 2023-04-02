<?php

namespace Drupal\crm_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\crm_field\Plugin\Field\FieldType\CRMWebsiteItem;

/**
 * Plugin implementation of the 'crm_website_default' formatter.
 *
 * @FieldFormatter(
 *   id = "crm_website_default",
 *   label = @Translation("Default"),
 *   field_types = {"crm_website"}
 * )
 */
class CRMWebsiteDefaultFormatter extends FormatterBase {

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

      if ($item->url) {
        $element[$delta]['url'] = [
          '#type' => 'item',
          '#title' => $this->t('Url'),
          'content' => [
            '#type' => 'link',
            '#title' => $item->url,
            '#url' => Url::fromUri($item->url),
          ],
        ];
      }

      if ($item->type) {
        $allowed_values = CRMWebsiteItem::allowedTypeValues();
        $element[$delta]['type'] = [
          '#type' => 'item',
          '#title' => $this->t('Type'),
          '#markup' => $allowed_values[$item->type],
        ];
      }

    }

    return $element;
  }

}
