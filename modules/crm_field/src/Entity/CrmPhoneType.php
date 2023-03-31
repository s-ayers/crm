<?php

namespace Drupal\crm_field\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\crm_field\CrmPhoneTypeInterface;

/**
 * Defines the phone type entity type.
 *
 * @ConfigEntityType(
 *   id = "crm_phone_type",
 *   label = @Translation("Phone Type"),
 *   label_collection = @Translation("Phone Types"),
 *   label_singular = @Translation("phone type"),
 *   label_plural = @Translation("phone types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count phone type",
 *     plural = "@count phone types",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\crm_field\CrmPhoneTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\crm_field\Form\CrmPhoneTypeForm",
 *       "edit" = "Drupal\crm_field\Form\CrmPhoneTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "crm_phone_type",
 *   admin_permission = "administer crm_phone_type",
 *   links = {
 *     "collection" = "/admin/structure/crm-phone-type",
 *     "add-form" = "/admin/structure/crm-phone-type/add",
 *     "edit-form" = "/admin/structure/crm-phone-type/{crm_phone_type}",
 *     "delete-form" = "/admin/structure/crm-phone-type/{crm_phone_type}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description"
 *   }
 * )
 */
class CrmPhoneType extends ConfigEntityBase implements CrmPhoneTypeInterface {

  /**
   * The phone type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The phone type label.
   *
   * @var string
   */
  protected $label;

  /**
   * The phone type status.
   *
   * @var bool
   */
  protected $status;

  /**
   * The crm_phone_type description.
   *
   * @var string
   */
  protected $description;

}
