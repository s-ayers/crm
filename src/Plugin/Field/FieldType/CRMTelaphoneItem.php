<?php

namespace Drupal\crm\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'crm_telaphone' field type.
 *
 * @FieldType(
 *   id = "crm_telaphone",
 *   label = @Translation("CRM Telaphone"),
 *   category = @Translation("General"),
 *   default_widget = "crm_telaphone",
 *   default_formatter = "crm_telaphone_default"
 * )
 */
class CRMTelaphoneItem extends FieldItemBase {

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
    if ($this->phone !== NULL) {
      return FALSE;
    }
    elseif ($this->phone_ext !== NULL) {
      return FALSE;
    }
    elseif ($this->type !== NULL) {
      return FALSE;
    }
    elseif ($this->location_id !== NULL) {
      return FALSE;
    }
    elseif ($this->primary == 1) {
      return FALSE;
    }
    elseif ($this->billing == 1) {
      return FALSE;
    }
    elseif ($this->mobile_provider_id !== NULL) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {

    $properties['phone'] = DataDefinition::create('string')
      ->setLabel(t('Phone'));
    $properties['phone_ext'] = DataDefinition::create('string')
      ->setLabel(t('Extension'));
    $properties['type'] = DataDefinition::create('string')
      ->setLabel(t('Type'));
    $properties['location_id'] = DataDefinition::create('string')
      ->setLabel(t('Location'));
    $properties['primary'] = DataDefinition::create('boolean')
      ->setLabel(t('Primary'));
    $properties['billing'] = DataDefinition::create('boolean')
      ->setLabel(t('Billing'));
    $properties['mobile_provider_id'] = DataDefinition::create('string')
      ->setLabel(t('Mobile Provider'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();

    $options['phone']['NotBlank'] = [];

    $options['type']['AllowedValues'] = array_keys(CRMTelaphoneItem::allowedTypeValues());

    $options['type']['NotBlank'] = [];

    $options['location_id']['NotBlank'] = [];

    $options['mobile_provider_id']['AllowedValues'] = array_keys(CRMTelaphoneItem::allowedMobileProviderValues());

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
      'phone' => [
        'type' => 'varchar',
        'length' => 255,
      ],
      'phone_ext' => [
        'type' => 'varchar',
        'length' => 255,
      ],
      'type' => [
        'type' => 'varchar',
        'length' => 255,
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
      'mobile_provider_id' => [
        'type' => 'varchar',
        'length' => 255,
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

    $values['phone'] = mt_rand(pow(10, 8), pow(10, 9) - 1);

    $values['phone_ext'] = $random->word(mt_rand(1, 255));

    $values['type'] = array_rand(self::allowedTypeValues());

    $values['location_id'] = $random->word(mt_rand(1, 255));

    $values['primary'] = (bool) mt_rand(0, 1);

    $values['billing'] = (bool) mt_rand(0, 1);

    $values['mobile_provider_id'] = array_rand(self::allowedMobileProviderValues());

    return $values;
  }

  /**
   * Returns allowed values for 'type' sub-field.
   *
   * @return array
   *   The list of allowed values.
   */
  public static function allowedTypeValues() {
    return [
      'alpha' => t('Alpha'),
      'beta' => t('Beta'),
      'gamma' => t('Gamma'),
    ];
  }

  /**
   * Returns allowed values for 'mobile_provider_id' sub-field.
   *
   * @return array
   *   The list of allowed values.
   */
  public static function allowedMobileProviderValues() {
    return [
      'alpha' => t('Alpha'),
      'beta' => t('Beta'),
      'gamma' => t('Gamma'),
    ];
  }

}
