<?php

namespace Drupal\crm;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a contact entity type.
 */
interface CrmContactInterface extends ContentEntityInterface, EntityChangedInterface {

}
