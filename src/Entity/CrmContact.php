<?php

namespace Drupal\crm\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\crm\CrmContactInterface;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Defines the contact entity class.
 *
 * @ContentEntityType(
 *   id = "crm_contact",
 *   label = @Translation("Contact"),
 *   label_collection = @Translation("Contacts"),
 *   label_singular = @Translation("contact"),
 *   label_plural = @Translation("contacts"),
 *   label_count = @PluralTranslation(
 *     singular = "@count contacts",
 *     plural = "@count contacts",
 *   ),
 *   bundle_label = @Translation("Contact type"),
 *   handlers = {
 *     "list_builder" = "Drupal\crm\CrmContactListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\crm\CrmContactAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\crm\Form\CrmContactForm",
 *       "edit" = "Drupal\crm\Form\CrmContactForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "crm_contact",
 *   revision_table = "crm_contact_revision",
 *   show_revision_ui = TRUE,
 *   admin_permission = "administer crm contact types",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "bundle" = "bundle",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *   },
 *   revision_metadata_keys = {
 *    "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log",
 *   },
 *   links = {
 *     "collection" = "/admin/content/crm-contact",
 *     "add-form" = "/crm/contact/add/{crm_contact_type}",
 *     "add-page" = "/crm/contact/add",
 *     "canonical" = "/crm/contact/{crm_contact}",
 *     "edit-form" = "/crm/contact/{crm_contact}/edit",
 *     "delete-form" = "/crm/contact/{crm_contact}/delete",
 *   },
 *   bundle_entity_type = "crm_contact_type",
 *   field_ui_base_route = "entity.crm_contact_type.edit_form",
 * )
 */
class CrmContact extends RevisionableContentEntityBase implements CrmContactInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    // If type is individual, set the label field to the name field.
    if ($this->bundle() == 'individual') {
      $formatted_name = NULL;
      $name_array = $this->get('full_name')->getValue();
      if ($name_array != NULL) {
        dpm($name_array);
        dpm(__LINE__);
        $name_formatter = \Drupal::service('name.formatter');
        $formatted_name = $name_formatter->format($name_array, 'default', ['markup' => 'raw']);
        dpm($formatted_name);
        // $this->set('label', $formatted_name);
      }
    }

  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setRevisionable(TRUE)
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['sort_name'] = BaseFieldDefinition::create('string')
      ->setRevisionable(TRUE)
      ->setLabel(t('Sort Name'))
      // ->setRequired(TRUE)
      ->setSetting('max_length', 255);
      // ->setDisplayOptions('form', [
      //   'type' => 'string_textfield',
      //   'weight' => -5,
      // ])
      // ->setDisplayConfigurable('form', TRUE)
      // ->setDisplayOptions('view', [
      //   'label' => 'hidden',
      //   'type' => 'string',
      //   'weight' => -5,
      // ])
      // ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setRevisionable(TRUE)
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => FALSE,
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 0,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created on'))
      ->setDescription(t('The time that the contact was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the contact was last edited.'));

    return $fields;
  }

}
