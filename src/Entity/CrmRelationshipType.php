<?php

namespace Drupal\crm\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the CRM Relationship type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "crm_relationship_type",
 *   label = @Translation("CRM Relationship type"),
 *   label_collection = @Translation("CRM Relationship types"),
 *   label_singular = @Translation("crm relationship type"),
 *   label_plural = @Translation("crm relationships types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count crm relationships type",
 *     plural = "@count crm relationships types",
 *   ),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\crm\Form\CrmRelationshipTypeForm",
 *       "edit" = "Drupal\crm\Form\CrmRelationshipTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "list_builder" = "Drupal\crm\CrmRelationshipTypeListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   admin_permission = "administer crm relationship types",
 *   bundle_of = "crm_relationship",
 *   config_prefix = "crm_relationship_type",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/crm/relationship_types/add",
 *     "edit-form" = "/admin/structure/crm/relationship_types/manage/{crm_relationship_type}",
 *     "delete-form" = "/admin/structure/crm/relationship_types/manage/{crm_relationship_type}/delete",
 *     "collection" = "/admin/structure/crm/relationship_types"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *     "contact_type_a",
 *     "contact_type_b",
 *     "label_b_a",
 *     "description",
 *   }
 * )
 */
class CrmRelationshipType extends ConfigEntityBundleBase {

  /**
   * The machine name of this crm relationship type.
   *
   * @var string
   */
  protected $id;

  /**
   * The human-readable name of the crm relationship type.
   *
   * @var string
   */
  protected $label;

  /**
   * The human-readable name of the crm relationship type.
   *
   * @var string
   */
  protected $label_b_a;

  /**
   * The human-readable name of the crm relationship type.
   *
   * @var string
   */
  protected $contact_type_a;

  /**
   * The human-readable name of the crm relationship type.
   *
   * @var string
   */
  protected $contact_type_b;

  /**
   * The description of the crm relationship type.
   *
   * @var string
   */
  protected $description;

}
