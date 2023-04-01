<?php

namespace Drupal\crm_field\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\crm_field\CrmWebsiteTypeInterface;

/**
 * Defines the website type entity type.
 *
 * @ConfigEntityType(
 *   id = "crm_website_type",
 *   label = @Translation("Website Type"),
 *   label_collection = @Translation("Website Types"),
 *   label_singular = @Translation("website type"),
 *   label_plural = @Translation("website types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count website type",
 *     plural = "@count website types",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\crm_field\CrmWebsiteTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\crm_field\Form\CrmWebsiteTypeForm",
 *       "edit" = "Drupal\crm_field\Form\CrmWebsiteTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "crm_website_type",
 *   admin_permission = "administer crm_website_type",
 *   links = {
 *     "collection" = "/admin/structure/crm-website-type",
 *     "add-form" = "/admin/structure/crm-website-type/add",
 *     "edit-form" = "/admin/structure/crm-website-type/{crm_website_type}",
 *     "delete-form" = "/admin/structure/crm-website-type/{crm_website_type}/delete"
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
class CrmWebsiteType extends ConfigEntityBase implements CrmWebsiteTypeInterface {

  /**
   * The website type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The website type label.
   *
   * @var string
   */
  protected $label;

  /**
   * The website type status.
   *
   * @var bool
   */
  protected $status;

  /**
   * The crm_website_type description.
   *
   * @var string
   */
  protected $description;

}
