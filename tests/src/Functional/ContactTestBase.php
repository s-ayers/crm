<?php

namespace Drupal\Tests\crm\Functional;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Database\Database;
use Drupal\Core\Session\AccountInterface;
use Drupal\crm\CrmContactInterface;
use Drupal\Tests\BrowserTestBase;

/**
 * Sets up page and article content types.
 */
abstract class ContactTestBase extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['crm', 'datetime'];

  /**
   * The node access control handler.
   *
   * @var \Drupal\Core\Entity\EntityAccessControlHandlerInterface
   */
  protected $accessHandler;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create Basic page and Article node types.
    // if ($this->profile != 'standard') {
    //   $this->drupalCreateContentType([
    //     'type' => 'page',
    //     'name' => 'Basic page',
    //     'display_submitted' => FALSE,
    //   ]);
    //   $this->drupalCreateContentType(['type' => 'article', 'name' => 'Article']);
    // }
    $this->accessHandler = \Drupal::entityTypeManager()->getAccessControlHandler('crm_contact');
  }

  /**
   * Asserts that node access correctly grants or denies access.
   *
   * @param array $ops
   *   An associative array of the expected node access grants for the node
   *   and account, with each key as the name of an operation (e.g. 'view',
   *   'delete') and each value a Boolean indicating whether access to that
   *   operation should be granted.
   * @param \Drupal\node\NodeInterface $node
   *   The node object to check.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user account for which to check access.
   *
   * @internal
   */
  public function assertContactAccess(array $ops, CrmContactInterface $contact, AccountInterface $account) {
    foreach ($ops as $op => $result) {
      $this->assertEquals($this->accessHandler->access($contact, $op, $account), $result, $this->contactAccessAssertMessage($op, $result, $node->language()->getId()));
    }
  }

  /**
   * Asserts that node create access correctly grants or denies access.
   *
   * @param string $bundle
   *   The node bundle to check access to.
   * @param bool $result
   *   Whether access should be granted or not.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user account for which to check access.
   * @param string|null $langcode
   *   (optional) The language code indicating which translation of the node
   *   to check. If NULL, the untranslated (fallback) access is checked.
   *
   * @internal
   */
  public function assertNodeCreateAccess(string $bundle, bool $result, AccountInterface $account, ?string $langcode = NULL) {
    $this->assertEquals($this->accessHandler->createAccess($bundle, $account, ['langcode' => $langcode]), $result, $this->contactAccessAssertMessage('create', $result, $langcode));
  }

  /**
   * Constructs an assert message to display which node access was tested.
   *
   * @param string $operation
   *   The operation to check access for.
   * @param bool $result
   *   Whether access should be granted or not.
   * @param string|null $langcode
   *   (optional) The language code indicating which translation of the node
   *   to check. If NULL, the untranslated (fallback) access is checked.
   *
   * @return string
   *   An assert message string which contains information in plain English
   *   about the node access permission test that was performed.
   */
  public function contactAccessAssertMessage($operation, $result, $langcode = NULL) {
    return new FormattableMarkup(
      'Contact access returns @result with operation %op, language code %langcode.',
      [
        '@result' => $result ? 'true' : 'false',
        '%op' => $operation,
        '%langcode' => !empty($langcode) ? $langcode : 'empty',
      ]
    );
  }

    /**
   * Returns the contact by name.
   */
  protected function drupalGetContactByName(string $name) {
    $contact = \Drupal::entityTypeManager()
      ->getStorage('crm_contact')
      ->loadByProperties(['name' => $name]);
    return reset($contact);
  }

  /**
   * Gets the watchdog IDs of the records with the rollback exception message.
   *
   * @return int[]
   *   Array containing the IDs of the log records with the rollback exception
   *   message.
   */
  protected static function getWatchdogIdsForTestExceptionRollback() {
    // PostgreSQL doesn't support bytea LIKE queries, so we need to unserialize
    // first to check for the rollback exception message.
    $matches = [];
    $query = Database::getConnection()->select('watchdog', 'w')
      ->fields('w', ['wid', 'variables'])
      ->execute();

    foreach ($query as $row) {
      $variables = (array) unserialize($row->variables);
      if (isset($variables['@message']) && $variables['@message'] === 'Test exception for rollback.') {
        $matches[] = $row->wid;
      }
    }
    return $matches;
  }

}
