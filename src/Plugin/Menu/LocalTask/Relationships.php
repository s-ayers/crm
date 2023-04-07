<?php

namespace Drupal\crm\Plugin\Menu\LocalTask;

use Drupal\Core\Menu\LocalTaskDefault;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\comment\CommentStorageInterface;
use Drupal\crm\Relationship;
use Drupal\Core\Path\CurrentPathStack;

/**
 * Provides a local task that shows the amount of comments.
 */
class Relationships extends LocalTaskDefault implements ContainerFactoryPluginInterface {
  use StringTranslationTrait;

  /**
   * The comment storage service.
   *
   * @var \Drupal\crm\Relationship
   */
  protected $relationshipService;

  /**
   * The current path service.
   *
   * @var \Drupal\Core\Path\CurrentPathStack
   */
  protected $pathService;

  /**
   * Construct the UnapprovedComments object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\crm\Relationship $relationship_service
   *   The crm relationship service.
   * @param \Drupal\Core\Path\CurrentPathStack $path_current
   *   The current path service.
   */
  public function __construct(
                              array $configuration,
                              $plugin_id,
                              array $plugin_definition,
                              Relationship $relationship_service,
                              $path_current) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->relationshipService = $relationship_service;
    $this->pathService = $path_current;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('crm.relationship'),
      $container->get('request_stack')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle(Request $request = NULL) {
    $contact_id = \Drupal::routeMatch()->getParameter('crm_contact');
    if ($contact_id instanceof \Drupal\crm\CrmContactInterface) {
      $contact_id = $contact_id->id();
    }
    $ids = $this->relationshipService->getRelationshipIdByContactId($contact_id);
    $count = count($ids);

    return $this->t('Relationships (@count)', ['@count' => $count]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
