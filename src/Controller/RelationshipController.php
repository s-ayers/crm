<?php

namespace Drupal\crm\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Crm routes.
 */
class RelationshipController extends ControllerBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The controller constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Builds the response.
   */
  public function build() {

    $build['active'] = [
      '#type' => 'table',
      '#header' => $this->buildHeader(),
      '#title' => $this->t('Active Relationships'),
      '#rows' => $this->getActiveRelationships(),
      '#empty' => $this->t('There are no active relationships.'),
      '#cache' => [],
    ];

    $build['inactive'] = [
      '#type' => 'table',
      '#header' => $this->buildHeader(),
      '#title' => $this->t('Inactive Relationships'),
      '#rows' => $this->getInactiveRelationships(),
      '#empty' => $this->t('There are no inactive relationships.'),
      '#cache' => [],
    ];

    $build['#cache']['max-age'] = 0;

    return $build;
  }

  /**
   * Builds the header.
   *
   * @return array
   *   An array of header cells.
   */
  protected function buildHeader() {
    $header['type'] = $this->t('Relationship');
    $header['contact'] = $this->t('Contact');
    $header['start_date'] = $this->t('Start Date');
    $header['end_date'] = $this->t('End Date');
    $header['city'] = $this->t('City');
    $header['state'] = $this->t('State');
    $header['email'] = $this->t('Email');
    $header['phone'] = $this->t('Phone');

    $header['operations'] = $this->t('Operations');
    return $header;
  }

  /**
   * Gets the active relationships.
   *
   * @return array
   *   An array of active relationships.
   */
  protected function getActiveRelationships() {
    $rows = [];
    $relationships = $this->entityTypeManager->getStorage('crm_relationship')->loadByProperties(['status' => 1]);
    foreach ($relationships as $relationship) {
      $rows[] = [
        'type' => $relationship->bundle->entity->label(),
        'contact' => $relationship->get('contact_b')->entity->label(),
        'start_date' => $relationship->get('start')->value,
        'end_date' => $relationship->get('end')->value,
        'city' => 'New Lenox',
        'state' => 'IL',
        'email' => 'webmaster@openknowledge.works',
        'phone' => '815-485-0000',
        'operations' => [
          'data' => [
            '#type' => 'operations',
            '#links' => [
              'edit' => [
                'title' => $this->t('Edit'),
                'url' => $relationship->toUrl('edit-form'),
              ],
              'delete' => [
                'title' => $this->t('Delete'),
                'url' => $relationship->toUrl('delete-form'),
              ],
            ],
          ],
        ],
      ];
    }

    return $rows;
  }

  /**
   * Gets the inactive relationships.
   *
   * @return array
   *   An array of inactive relationships.
   */
  protected function getInactiveRelationships() {
    $rows = [];
    $relationships = $this->entityTypeManager->getStorage('crm_relationship')->loadByProperties(['status' => 0]);
    foreach ($relationships as $relationship) {
      $rows[] = [
        'type' => $relationship->bundle->entity->label(),
        'contact' => $relationship->get('contact_b')->entity->label(),
        'start_date' => $relationship->get('start')->value,
        'end_date' => $relationship->get('end')->value,
        'city' => 'New Lenox',
        'state' => 'IL',
        'email' => 'webmaster@openknowledge.works',
        'phone' => '815-485-0000',
        'operations' => [
          'data' => [
            '#type' => 'operations',
            '#links' => [
              'edit' => [
                'title' => $this->t('Edit'),
                'url' => $relationship->toUrl('edit-form'),
              ],
              'delete' => [
                'title' => $this->t('Delete'),
                'url' => $relationship->toUrl('delete-form'),
              ],
            ],
          ],
        ],
      ];
    }

    return $rows;
  }
}
