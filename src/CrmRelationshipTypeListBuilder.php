<?php

namespace Drupal\crm;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of crm relationship type entities.
 *
 * @see \Drupal\crm\Entity\CrmRelationshipType
 */
class CrmRelationshipTypeListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['title'] = $this->t('Label');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['title'] = [
      'data' => $entity->label(),
      'class' => ['menu-label'],
    ];

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build = parent::render();

    $build['table']['#empty'] = $this->t(
      'No crm relationship types available. <a href=":link">Add crm relationship type</a>.',
      [':link' => Url::fromRoute('entity.crm_relationship_type.add_form')->toString()]
    );

    return $build;
  }

}
