<?php

namespace Drupal\crm_case\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\crm_case\CrmCaseEncounterInterface;

/**
 * Defines the encounter entity type.
 *
 * @ConfigEntityType(
 *   id = "crm_case_encounter",
 *   label = @Translation("Encounter"),
 *   label_collection = @Translation("Encounters"),
 *   label_singular = @Translation("encounter"),
 *   label_plural = @Translation("encounters"),
 *   label_count = @PluralTranslation(
 *     singular = "@count encounter",
 *     plural = "@count encounters",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\crm_case\CrmCaseEncounterListBuilder",
 *     "form" = {
 *       "add" = "Drupal\crm_case\Form\CrmCaseEncounterForm",
 *       "edit" = "Drupal\crm_case\Form\CrmCaseEncounterForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "crm_case_encounter",
 *   admin_permission = "administer crm_case_encounter",
 *   links = {
 *     "collection" = "/admin/structure/crm-case-encounter",
 *     "add-form" = "/admin/structure/crm-case-encounter/add",
 *     "edit-form" = "/admin/structure/crm-case-encounter/{crm_case_encounter}",
 *     "delete-form" = "/admin/structure/crm-case-encounter/{crm_case_encounter}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "status",
 *     "weight",
 *     "default"
 *   }
 * )
 */
class CrmCaseEncounter extends ConfigEntityBase implements CrmCaseEncounterInterface {

  /**
   * The encounter ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The encounter label.
   *
   * @var string
   */
  protected $label;

  /**
   * The encounter status.
   *
   * @var bool
   */
  protected $status;

  /**
   * The crm_case_encounter description.
   *
   * @var string
   */
  protected $description;

  /**
   * The crm_case_encounter sort weight.
   *
   * @var int
   */
  protected $weight;

  /**
   * The crm_case_encounter default.
   *
   * @var bool
   */
  protected $default;

}
