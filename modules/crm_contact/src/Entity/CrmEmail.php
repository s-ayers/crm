<?php

namespace Drupal\crm\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\crm\CrmEmailInterface;

/**
 * Defines the email entity class.
 *
 * @ContentEntityType(
 *   id = "crm_email",
 *   label = @Translation("Email"),
 *   label_collection = @Translation("Emails"),
 *   label_singular = @Translation("email"),
 *   label_plural = @Translation("emails"),
 *   label_count = @PluralTranslation(
 *     singular = "@count emails",
 *     plural = "@count emails",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\crm\CrmEmailListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\crm\Form\CrmEmailForm",
 *       "edit" = "Drupal\crm\Form\CrmEmailForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "crm_email",
 *   revision_table = "crm_email_revision",
 *   show_revision_ui = TRUE,
 *   admin_permission = "administer crm email",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   revision_metadata_keys = {
 *      "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log",
 *   },
 *   links = {
 *     "collection" = "/admin/content/crm-email",
 *     "add-form" = "/crm/email/add",
 *     "canonical" = "/crm/email/{crm_email}",
 *     "edit-form" = "/crm/email/{crm_email}/edit",
 *     "delete-form" = "/crm/email/{crm_email}/delete",
 *   },
 *   field_ui_base_route = "entity.crm_email.settings",
 * )
 */
class CrmEmail extends RevisionableContentEntityBase implements CrmEmailInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setRevisionable(TRUE)
      ->setLabel(t('Label'))
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

    $fields['mail'] = BaseFieldDefinition::create('email')
      ->setLabel(t("The email address"))
      ->setDescription(t('The email of the person that is sending the contact message.'))
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

    return $fields;
  }

}
