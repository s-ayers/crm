<?php

namespace Drupal\crm\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\crm\CrmLocationTypeInterface;

/**
 * Defines the location type entity type.
 *
 * @ConfigEntityType(
 *   id = "crm_location_type",
 *   label = @Translation("Location Type"),
 *   label_collection = @Translation("Location Types"),
 *   label_singular = @Translation("location type"),
 *   label_plural = @Translation("location types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count location type",
 *     plural = "@count location types",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\crm\CrmLocationTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\crm\Form\CrmLocationTypeForm",
 *       "edit" = "Drupal\crm\Form\CrmLocationTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "crm_location_type",
 *   admin_permission = "administer crm_location_type",
 *   links = {
 *     "collection" = "/admin/structure/crm-location-type",
 *     "add-form" = "/admin/structure/crm-location-type/add",
 *     "edit-form" = "/admin/structure/crm-location-type/{crm_location_type}",
 *     "delete-form" = "/admin/structure/crm-location-type/{crm_location_type}/delete"
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
class CrmLocationType extends ConfigEntityBase implements CrmLocationTypeInterface {

  /**
   * The location type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The location type label.
   *
   * @var string
   */
  protected $label;

  /**
   * The location type status.
   *
   * @var bool
   */
  protected $status;

  /**
   * The crm_location_type description.
   *
   * @var string
   */
  protected $description;

}
