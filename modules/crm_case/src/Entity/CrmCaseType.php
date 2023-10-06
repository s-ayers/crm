<?php

namespace Drupal\crm_case\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Crm Case type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "crm_case_type",
 *   label = @Translation("Crm Case type"),
 *   label_collection = @Translation("Crm Case types"),
 *   label_singular = @Translation("crm case type"),
 *   label_plural = @Translation("crm cases types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count crm cases type",
 *     plural = "@count crm cases types",
 *   ),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\crm_case\Form\CrmCaseTypeForm",
 *       "edit" = "Drupal\crm_case\Form\CrmCaseTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "list_builder" = "Drupal\crm_case\CrmCaseTypeListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   admin_permission = "administer crm case types",
 *   bundle_of = "crm_case",
 *   config_prefix = "crm_case_type",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/crm_case_types/add",
 *     "edit-form" = "/admin/structure/crm_case_types/manage/{crm_case_type}",
 *     "delete-form" = "/admin/structure/crm_case_types/manage/{crm_case_type}/delete",
 *     "collection" = "/admin/structure/crm_case_types"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *   }
 * )
 */
class CrmCaseType extends ConfigEntityBundleBase {

  /**
   * The machine name of this crm case type.
   *
   * @var string
   */
  protected $id;

  /**
   * The human-readable name of the crm case type.
   *
   * @var string
   */
  protected $label;

}
