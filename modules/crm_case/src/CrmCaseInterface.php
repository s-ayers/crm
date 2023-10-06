<?php

namespace Drupal\crm_case;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a crm case entity type.
 */
interface CrmCaseInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
