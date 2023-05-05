<?php

namespace Drupal\crm_field\Plugin\Field\FieldWidget;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Defines the 'crm_email' field widget.
 *
 * @FieldWidget(
 *   id = "crm_email",
 *   label = @Translation("CRM Email"),
 *   field_types = {"crm_email"},
 * )
 */
class EmailWidget extends WidgetBase {

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

    $element['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#default_value' => isset($items[$delta]->email) ? $items[$delta]->email : NULL,
    ];

    $element['location_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Location'),
      '#default_value' => isset($items[$delta]->location_id) ? $items[$delta]->location_id : NULL,
      '#options' => $this->getLocations(),
    ];

    $element['primary'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Primary'),
      '#default_value' => isset($items[$delta]->primary) ? $items[$delta]->primary : NULL,
    ];

    $element['hold'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hold'),
      '#default_value' => isset($items[$delta]->hold) ? $items[$delta]->hold : NULL,
    ];

    $element['bulk'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Bulk'),
      '#default_value' => isset($items[$delta]->bulk) ? $items[$delta]->bulk : NULL,
    ];

    $element['hold_date'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Hold Date'),
      '#default_value' => NULL,
      '#state' => [
        'visible' => [
          ':input[name="field_email[' . $delta . '][hold]"]' => ['checked' => TRUE],
        ],
      ],
    ];
    if (isset($items[$delta]->hold_date)) {
      $element['hold_date']['#default_value'] = DrupalDateTime::createFromFormat(
        'Y-m-d\TH:i:s',
        $items[$delta]->hold_date,
        'UTC'
      );
    }

    $element['reset_date'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Reset Date'),
      '#default_value' => NULL,
    ];
    if (isset($items[$delta]->reset_date)) {
      $element['reset_date']['#default_value'] = DrupalDateTime::createFromFormat(
        'Y-m-d\TH:i:s',
        $items[$delta]->reset_date,
        'UTC'
      );
    }

    $element['#theme_wrappers'] = ['container', 'form_element'];
    $element['#attributes']['class'][] = 'crm-email-elements';
    $element['#attached']['library'][] = 'crm/crm_email';

    return $element;
  }

  /**
   * Get locations.
   *
   * @return array
   *   Locations.
   */
  protected function getLocations() {
    $locations = [];
    $locations['home'] = $this->t('Home');
    $locations['work'] = $this->t('Work');
    $locations['other'] = $this->t('Other');

    return $locations;
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
      if ($value['email'] === '') {
        $values[$delta]['email'] = NULL;
      }
      if ($value['location_id'] === '') {
        $values[$delta]['location_id'] = NULL;
      }
      if ($value['primary'] === '') {
        $values[$delta]['primary'] = NULL;
      }
      if ($value['hold'] === '') {
        $values[$delta]['hold'] = NULL;
      }
      if ($value['bulk'] === '') {
        $values[$delta]['bulk'] = NULL;
      }
      if ($value['hold_date'] === '') {
        $values[$delta]['hold_date'] = NULL;
      }
      if ($value['hold_date'] instanceof DrupalDateTime) {
        $values[$delta]['hold_date'] = $value['hold_date']->format('Y-m-d\TH:i:s');
      }
      if ($value['reset_date'] === '') {
        $values[$delta]['reset_date'] = NULL;
      }
      if ($value['reset_date'] instanceof DrupalDateTime) {
        $values[$delta]['reset_date'] = $value['reset_date']->format('Y-m-d\TH:i:s');
      }
    }
    return $values;
  }

}
