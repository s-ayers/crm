<?php

namespace Drupal\Tests\crm\Functional\Plugin\Menu\LocalTask;

use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\crm\Functional\ContactTestBase;
use Drupal\crm\Entity\CrmContact;

/**
 * Tests the Comments local task plugin.
 *
 * @group crm
 */
class CommentsTest extends ContactTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['crm', 'comment'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Test that the local task title displays the correct number of notes.
   */
  public function testLocalTaskTitle() {
    // Create a new CRM contact entity.
    $contact = $this->createCrmContact();

    // Create some comments on the contact entity.
    $this->createComment($contact, 'Note 1');
    $this->createComment($contact, 'Note 2');
    $this->createComment($contact, 'Note 3');

    // Visit the contact page.
    $this->drupalGet('/crm/contact/' . $contact->id());

    // Check that the Comments local task displays the correct number of notes.
    $this->assertSession()->pageTextContains('Notes (3)');
  }

  /**
   * Helper method to create a new CRM contact entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   The newly created entity.
   */
  protected function createCrmContact() {
    $values = [
      'name' => $this->randomString(),
      'type' => 'organization',
    ];
    return $this->container
      ->get('entity_type.manager')
      ->getStorage('crm_contact')
      ->create($values)
      ->save();
  }

  /**
   * Helper method to create a new comment on a CRM contact entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $contact
   *   The CRM contact entity.
   * @param string $text
   *   The comment text.
   */
  protected function createComment($contact, $text) {
    $values = [
      'entity_type' => 'crm_contact',
      'entity_id' => $contact->id(),
      'comment_type' => 'comment',
      'comment_body' => [
        'value' => $text,
      ],
      'field_name' => 'field_notes',
    ];
    $comment = $this->container->get('entity_type.manager')->getStorage('comment')->create($values);
    $comment->save();
  }

}
