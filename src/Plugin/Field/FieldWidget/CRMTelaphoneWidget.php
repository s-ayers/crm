<?php

namespace Drupal\crm\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\crm\Plugin\Field\FieldType\CRMTelaphoneItem;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Defines the 'crm_telaphone' field widget.
 *
 * @FieldWidget(
 *   id = "crm_telaphone",
 *   label = @Translation("CRM Telaphone"),
 *   field_types = {"crm_telaphone"},
 * )
 */
class CRMTelaphoneWidget extends WidgetBase {

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

    $element['phone'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone'),
      '#default_value' => isset($items[$delta]->phone) ? $items[$delta]->phone : NULL,
    ];

    $element['phone_ext'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Extension'),
      '#default_value' => isset($items[$delta]->phone_ext) ? $items[$delta]->phone_ext : NULL,
    ];

    $element['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Type'),
      '#options' => ['' => $this->t('- Select a value -')] + CRMTelaphoneItem::allowedTypeValues(),
      '#default_value' => isset($items[$delta]->type) ? $items[$delta]->type : NULL,
    ];

    $element['location_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Location'),
      '#default_value' => isset($items[$delta]->location_id) ? $items[$delta]->location_id : NULL,
    ];

    $element['primary'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Primary'),
      '#default_value' => isset($items[$delta]->primary) ? $items[$delta]->primary : NULL,
    ];

    $element['billing'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Billing'),
      '#default_value' => isset($items[$delta]->billing) ? $items[$delta]->billing : NULL,
    ];

    $element['mobile_provider_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Mobile Provider'),
      '#options' => ['' => $this->t('- None -')] + CRMTelaphoneItem::allowedMobileProviderValues(),
      '#default_value' => isset($items[$delta]->mobile_provider_id) ? $items[$delta]->mobile_provider_id : NULL,
    ];

    $element['#theme_wrappers'] = ['container', 'form_element'];
    $element['#attributes']['class'][] = 'crm-telaphone-elements';
    $element['#attached']['library'][] = 'crm/crm_telaphone';

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
      if ($value['phone'] === '') {
        $values[$delta]['phone'] = NULL;
      }
      if ($value['phone_ext'] === '') {
        $values[$delta]['phone_ext'] = NULL;
      }
      if ($value['type'] === '') {
        $values[$delta]['type'] = NULL;
      }
      if ($value['location_id'] === '') {
        $values[$delta]['location_id'] = NULL;
      }
      if ($value['primary'] === '') {
        $values[$delta]['primary'] = NULL;
      }
      if ($value['billing'] === '') {
        $values[$delta]['billing'] = NULL;
      }
      if ($value['mobile_provider_id'] === '') {
        $values[$delta]['mobile_provider_id'] = NULL;
      }
    }
    return $values;
  }

}
