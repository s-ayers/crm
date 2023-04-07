<?php

namespace Drupal\crm;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Service description.
 */
class Relationship {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;


  /**
   * The relationship storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $relationshipStorage;

  /**
   * Constructs a Relationship object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->relationshipStorage = $entity_type_manager
      ->getStorage('crm_relationship');
  }

  /**
   * Method description.
   */
  public function getRelationshipIdByContactId(int $contact_id) {
    $query = $this->relationshipStorage->getQuery();
    $contact_a_ids = $query
      ->condition('status', 1)
      ->condition('contact_a', $contact_id)
      ->accessCheck(TRUE)
      ->execute();

    $query = $this->relationshipStorage->getQuery();
    $contact_b_ids = $query
      ->condition('status', 1)
      ->condition('contact_b', $contact_id)
      ->accessCheck(TRUE)
      ->execute();

    return array_merge($contact_a_ids, $contact_b_ids);
  }

}
