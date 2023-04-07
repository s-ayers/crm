<?php

namespace Drupal\crm\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\crm\CrmRelationshipInterface;

/**
 * Defines the crm relationship entity class.
 *
 * @ContentEntityType(
 *   id = "crm_relationship",
 *   label = @Translation("CRM Relationship"),
 *   label_collection = @Translation("CRM Relationships"),
 *   label_singular = @Translation("crm relationship"),
 *   label_plural = @Translation("crm relationships"),
 *   label_count = @PluralTranslation(
 *     singular = "@count crm relationships",
 *     plural = "@count crm relationships",
 *   ),
 *   bundle_label = @Translation("CRM Relationship type"),
 *   handlers = {
 *     "list_builder" = "Drupal\crm\CrmRelationshipListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\crm\CrmRelationshipAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\crm\Form\CrmRelationshipForm",
 *       "edit" = "Drupal\crm\Form\CrmRelationshipForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "crm_relationship",
 *   data_table = "crm_relationship_field_data",
 *   revision_table = "crm_relationship_revision",
 *   revision_data_table = "crm_relationship_field_revision",
 *   show_revision_ui = TRUE,
 *   translatable = TRUE,
 *   admin_permission = "administer crm relationship types",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "langcode" = "langcode",
 *     "bundle" = "bundle",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   revision_metadata_keys = {
 *    "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log",
 *   },
 *   links = {
 *     "collection" = "/admin/content/crm/relationship",
 *     "add-form" = "/crm/relationship/add/{crm_relationship_type}",
 *     "add-page" = "/crm/relationship/add",
 *     "canonical" = "/crm/relationship/{crm_relationship}",
 *     "edit-form" = "/crm/relationship/{crm_relationship}/edit",
 *     "delete-form" = "/crm/relationship/{crm_relationship}/delete",
 *   },
 *   bundle_entity_type = "crm_relationship_type",
 *   field_ui_base_route = "entity.crm_relationship_type.edit_form",
 * )
 */
class CrmRelationship extends RevisionableContentEntityBase implements CrmRelationshipInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['contact_a'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('Contact A')
      ->setSetting('target_type', 'crm_contact')
      ->setDisplayConfigurable('form', TRUE)
      ->setRequired(TRUE);

    $fields['contact_b'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('Contact B')
      ->setSetting('target_type', 'crm_contact')
      ->setDisplayConfigurable('form', TRUE)
      ->setRequired(TRUE);

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

    $fields['start'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Start Date'))
      ->setDescription(t('When the relationship started.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'datetime_type' => 'date',
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'datetime_default',
        'settings' => [
          'format_type' => 'medium',
        ],
        'weight' => -9,
      ])
      ->setDisplayOptions('form', [
        'type' => 'datetime_default',
        'weight' => -9,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['end'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('End Date'))
      ->setDescription(t('When the relationship ends.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'datetime_type' => 'date',
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'datetime_default',
        'settings' => [
          'format_type' => 'medium',
        ],
        'weight' => -9,
      ])
      ->setDisplayOptions('form', [
        'type' => 'datetime_default',
        'weight' => -9,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created on'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the crm relationship was created.'))
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
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the crm relationship was last edited.'));

    return $fields;
  }

}
