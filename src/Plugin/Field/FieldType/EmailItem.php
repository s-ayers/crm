<?php

namespace Drupal\crm\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Email;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'crm_email' field type.
 *
 * @FieldType(
 *   id = "crm_email",
 *   label = @Translation("Email"),
 *   category = @Translation("General"),
 *   default_widget = "crm_email",
 *   default_formatter = "crm_email_default"
 * )
 */
class EmailItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    $settings = ['foo' => 'example'];
    return $settings + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $settings = $this->getSettings();

    $element['foo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Foo'),
      '#default_value' => $settings['foo'],
      '#disabled' => $has_data,
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    $settings = ['bar' => 'example'];
    return $settings + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getSettings();

    $element['bar'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Foo'),
      '#default_value' => $settings['bar'],
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    if ($this->email !== NULL) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {

    $properties['email'] = DataDefinition::create('email')
      ->setLabel(t('Email'));
    $properties['location_id'] = DataDefinition::create('string')
      ->setLabel(t('Location'));
    $properties['primary'] = DataDefinition::create('boolean')
      ->setLabel(t('Primary'));
    $properties['billing'] = DataDefinition::create('boolean')
      ->setLabel(t('Billing'));
    $properties['hold'] = DataDefinition::create('boolean')
      ->setLabel(t('Hold'));
    $properties['bulk'] = DataDefinition::create('boolean')
      ->setLabel(t('Bulk'));
    $properties['hold_date'] = DataDefinition::create('datetime_iso8601')
      ->setLabel(t('Hold Date'));
    $properties['reset_date'] = DataDefinition::create('datetime_iso8601')
      ->setLabel(t('Reset Date'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();

    $options['email']['NotBlank'] = [];

    $options['email']['Length']['max'] = Email::EMAIL_MAX_LENGTH;

    $options['location_id']['NotBlank'] = [];

    $constraint_manager = \Drupal::typedDataManager()->getValidationConstraintManager();
    $constraints[] = $constraint_manager->create('ComplexData', $options);
    // @todo Add more constraints here.
    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $columns = [
      'email' => [
        'type' => 'varchar',
        'length' => Email::EMAIL_MAX_LENGTH,
      ],
      'location_id' => [
        'type' => 'varchar',
        'length' => 255,
      ],
      'primary' => [
        'type' => 'int',
        'size' => 'tiny',
      ],
      'billing' => [
        'type' => 'int',
        'size' => 'tiny',
      ],
      'hold' => [
        'type' => 'int',
        'size' => 'tiny',
      ],
      'bulk' => [
        'type' => 'int',
        'size' => 'tiny',
      ],
      'hold_date' => [
        'type' => 'varchar',
        'length' => 20,
      ],
      'reset_date' => [
        'type' => 'varchar',
        'length' => 20,
      ],
    ];

    $schema = [
      'columns' => $columns,
      // @DCG Add indexes here if necessary.
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {

    $random = new Random();

    $values['email'] = strtolower($random->name()) . '@example.com';

    $values['location_id'] = $random->word(mt_rand(1, 255));

    $values['primary'] = (bool) mt_rand(0, 1);

    $values['billing'] = (bool) mt_rand(0, 1);

    $values['hold'] = (bool) mt_rand(0, 1);

    $values['bulk'] = (bool) mt_rand(0, 1);

    $timestamp = \Drupal::time()->getRequestTime() - mt_rand(0, 86400 * 365);
    $values['hold_date'] = gmdate('Y-m-d\TH:i:s', $timestamp);

    $timestamp = \Drupal::time()->getRequestTime() - mt_rand(0, 86400 * 365);
    $values['reset_date'] = gmdate('Y-m-d\TH:i:s', $timestamp);

    return $values;
  }

}
