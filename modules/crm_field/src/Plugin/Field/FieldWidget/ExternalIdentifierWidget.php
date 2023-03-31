<?php

namespace Drupal\crm_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Defines the 'crm_external_identifier' field widget.
 *
 * @FieldWidget(
 *   id = "crm_external_identifier",
 *   label = @Translation("External Identifier"),
 *   field_types = {"crm_external_identifier"},
 * )
 */
class ExternalIdentifierWidget extends WidgetBase {

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
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element['source'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Source'),
      '#default_value' => isset($items[$delta]->source) ? $items[$delta]->source : NULL,
      '#size' => 20,
    ];

    $element['identifier'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Identifier'),
      '#default_value' => isset($items[$delta]->identifier) ? $items[$delta]->identifier : NULL,
      '#size' => 20,
    ];

    $element['#theme_wrappers'] = ['container', 'form_element'];
    $element['#attributes']['class'][] = 'container-inline';
    $element['#attributes']['class'][] = 'crm-external-identifier-elements';
    $element['#attached']['library'][] = 'crm_field/crm_external_identifier';

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function errorElement(array $element, ConstraintViolationInterface $violation, array $form, FormStateInterface $form_state) {
    return isset($violation->arrayPropertyPath[0]) ? $element[$violation->arrayPropertyPath[0]] : $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $value) {
      if ($value['source'] === '') {
        $values[$delta]['source'] = NULL;
      }
      if ($value['identifier'] === '') {
        $values[$delta]['identifier'] = NULL;
      }
    }
    return $values;
  }

}
