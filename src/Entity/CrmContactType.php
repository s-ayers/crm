<?php

namespace Drupal\crm\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Contact type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "crm_contact_type",
 *   label = @Translation("Contact type"),
 *   label_collection = @Translation("Contact types"),
 *   label_singular = @Translation("contact type"),
 *   label_plural = @Translation("contacts types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count contacts type",
 *     plural = "@count contacts types",
 *   ),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\crm\Form\CrmContactTypeForm",
 *       "edit" = "Drupal\crm\Form\CrmContactTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "list_builder" = "Drupal\crm\CrmContactTypeListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   admin_permission = "administer contact types",
 *   bundle_of = "crm_contact",
 *   config_prefix = "crm_contact_type",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/crm/contact_types/add",
 *     "edit-form" = "/admin/structure/crm/contact_types/manage/{crm_contact_type}",
 *     "delete-form" = "/admin/structure/crm/contact_types/manage/{crm_contact_type}/delete",
 *     "collection" = "/admin/structure/crm/contact_types"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *   }
 * )
 */
class CrmContactType extends ConfigEntityBundleBase {

  /**
   * The machine name of this contact type.
   *
   * @var string
   */
  protected $id;

  /**
   * The human-readable name of the contact type.
   *
   * @var string
   */
  protected $label;

}
