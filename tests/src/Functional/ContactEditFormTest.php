<?php

namespace Drupal\Tests\crm\Functional;

use Drupal\crm\CrmContactInterface;
use Drupal\user\Entity\User;

/**
 * Create a contact and test contact edit functionality.
 *
 * @group crm
 */
class ContactEditFormTest extends ContactTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * A normal logged in user.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $webUser;

  /**
   * A user with permission to bypass content access checks.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * The node storage.
   *
   * @var \Drupal\node\contactStorageInterface
   */
  protected $contactStorage;

  /**
   * Modules to enable.
   *
   * @var string[]
   */
  protected static $modules = ['block', 'crm', 'datetime'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->webUser = $this->drupalCreateUser([
      'edit contact',
      'create contact',
      'view contact',
    ]);
    $this->adminUser = $this->drupalCreateUser([
      // 'bypass node access',
      'administer contact types',
      'edit contact',
      'create contact',
      'view contact',
    ]);
    $this->drupalPlaceBlock('local_tasks_block');

    $this->contactStorage = $this->container->get('entity_type.manager')->getStorage('crm_contact');
  }

  /**
   * Checks contact edit functionality.
   */
  public function testContactEdit() {
    $this->drupalLogin($this->webUser);

    $name_key = 'name[0][value]';
    // Create contact to edit.
    $edit = [];
    $edit[$name_key] = $this->randomMachineName(8);
    $this->drupalGet('crm/contact/add/organization');
    $this->submitForm($edit, 'Save');

    // Check that the contact exists in the database.
    $contact = $this->drupalGetContactByName($edit[$name_key]);
    $this->assertNotEmpty($contact, 'Contact found in database.');

    // Check that "edit" link points to correct page.
    $this->clickLink('Edit');
    $this->assertSession()->addressEquals($contact->toUrl('edit-form'));

    // Check that the name and body fields are displayed with the correct values.
    // @todo Ideally assertLink would support HTML, but it doesn't.
    $this->assertSession()->responseContains('Edit<span class="visually-hidden">(active tab)</span>');
    $this->assertSession()->fieldValueEquals($name_key, $edit[$name_key]);

    // Edit the content of the node.
    $edit = [];
    $edit[$name_key] = $this->randomMachineName(8);
    // Stay on the current page, without reloading.
    $this->submitForm($edit, 'Save');

    // Check that the name and body fields are displayed with the updated values.
    $this->assertSession()->pageTextContains($edit[$name_key]);

    // Log in as a second administrator user.
    $second_web_user = $this->drupalCreateUser([
      'create contact',
      'view contact',
      'edit contact',
    ]);
    $this->drupalLogin($second_web_user);
    // Edit the same node, creating a new revision.
    $this->drupalGet("crm/contact/" . $contact->id() . "/edit");
    $edit = [];
    $edit['name[0][value]'] = $this->randomMachineName(8);
    $edit['revision'] = TRUE;
    $this->submitForm($edit, 'Save');

    // Ensure that the node revision has been created.
    $revised_contact = $this->drupalGetContactByName($edit['name[0][value]'], TRUE);
    $this->assertNotSame($contact->getRevisionId(), $revised_contact->getRevisionId(), 'A new revision has been created.');
    // Ensure that the node author is preserved when it was not changed in the
    // edit form.
    $this->assertSame($contact->getOwnerId(), $revised_contact->getOwnerId(), 'The node author has been preserved.');
    // Ensure that the revision authors are different since the revisions were
    // made by different users.
    $contact_storage = \Drupal::service('entity_type.manager')->getStorage('crm_contact');
    $first_node_version = $contact_storage->loadRevision($contact->getRevisionId());
    $second_node_version = $contact_storage->loadRevision($revised_contact->getRevisionId());
    $this->assertNotSame($first_node_version->getRevisionUser()->id(), $second_node_version->getRevisionUser()->id(), 'Each revision has a distinct user.');

    // Check if the node revision checkbox is rendered on node edit form.
    $this->drupalGet('crm/contact/' . $contact->id() . '/edit');
    $this->assertSession()->fieldExists('edit-revision', NULL);

    // Check that details form element opens when there are errors on child
    // elements.
    $this->drupalGet('crm/contact/' . $contact->id() . '/edit');
    $edit = [];
    // This invalid date will trigger an error.
    $edit['created[0][value][date]'] = $this->randomMachineName(8);
    // Get the current amount of open details elements.
    $open_details_elements = count($this->cssSelect('details[open="open"]'));
    $this->submitForm($edit, 'Save');
    // The node author details must be open.
    $this->assertSession()->responseContains('<details class="node-form-author js-form-wrapper form-wrapper" data-drupal-selector="edit-author" id="edit-author" open="open">');
    // Only one extra details element should now be open.
    $open_details_elements++;
    $this->assertCount($open_details_elements, $this->cssSelect('details[open="open"]'), 'Exactly one extra open &lt;details&gt; element found.');

    // Edit the same node, save it and verify it's unpublished after unchecking
    // the 'Published' boolean_checkbox and clicking 'Save'.
    $this->drupalGet("crm/contact/" . $contact->id() . "/edit");
    $edit = ['status[value]' => FALSE];
    $this->submitForm($edit, 'Save');
    $this->contactStorage->resetCache([$contact->id()]);
    $contact = $this->contactStorage->load($contact->id());
    $this->assertFalse($contact->isPublished(), 'Node is unpublished');
  }

  /**
   * Tests the contact meta information.
   */
  public function testContactMetaInformation() {
    // Check that regular users (i.e. without the 'administer nodes' permission)
    // can not see the meta information.
    $this->drupalLogin($this->webUser);
    $this->drupalGet('crm/contact/add/organization');
    $this->assertSession()->pageTextNotContains('Not saved yet');

    // Create node to edit.
    $edit['name[0][value]'] = $this->randomMachineName(8);
    $this->submitForm($edit, 'Save');

    $contact = $this->drupalGetContactByName($edit['name[0][value]']);
    $this->drupalGet("crm/contact/" . $contact->id() . "/edit");
    $this->assertSession()->pageTextNotContains('Published');
    $this->assertSession()->pageTextNotContains($this->container->get('date.formatter')->format($contact->get('changed')->value, 'short'));

    // Check that users with the 'administer nodes' permission can see the meta
    // information.
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('crm/contact/add/organization');
    // $this->assertSession()->pageTextContains('Not saved yet');

    // Create node to edit.
    $edit['name[0][value]'] = $this->randomMachineName(8);
    $this->submitForm($edit, 'Save');

    $contact = $this->drupalGetContactByName($edit['name[0][value]']);
    $this->drupalGet("crm/contact/" . $contact->id() . "/edit");
    $this->assertSession()->pageTextContains('Enabled');
    // $this->assertSession()->pageTextContains($this->container
    //   ->get('date.formatter')
    //   ->format($contact->get('changed')->value, 'short'));
  }

  /**
   * Checks that the "authored by" works correctly with various values.
   *
   * @param \Drupal\node\NodeInterface $contact
   *   A node object.
   * @param string $form_element_name
   *   The name of the form element to populate.
   */
  protected function checkVariousAuthoredByValues(NodeInterface $contact, $form_element_name) {
    // Try to change the 'authored by' field to an invalid user name.
    $edit = [
      $form_element_name => 'invalid-name',
    ];
    $this->drupalGet('crm/contact/' . $contact->id() . '/edit');
    $this->submitForm($edit, 'Save');
    $this->assertSession()->pageTextContains('There are no users matching "invalid-name".');

    // Change the authored by field to an empty string, which should assign
    // authorship to the anonymous user (uid 0).
    $edit[$form_element_name] = '';
    $this->drupalGet('crm/contact/' . $contact->id() . '/edit');
    $this->submitForm($edit, 'Save');
    $this->contactStorage->resetCache([$contact->id()]);
    $contact = $this->contactStorage->load($contact->id());
    $uid = $contact->getOwnerId();
    // Most SQL database drivers stringify fetches but entities are not
    // necessarily stored in a SQL database. At the same time, NULL/FALSE/""
    // won't do.
    $this->assertTrue($uid === 0 || $uid === '0', 'Node authored by anonymous user.');

    // Go back to the edit form and check that the correct value is displayed
    // in the author widget.
    $this->drupalGet('crm/contact/' . $contact->id() . '/edit');
    $anonymous_user = User::getAnonymousUser();
    $expected = $anonymous_user->label() . ' (' . $anonymous_user->id() . ')';
    $this->assertSession()->fieldValueEquals($form_element_name, $expected);

    // Change the authored by field to another user's name (that is not
    // logged in).
    $edit[$form_element_name] = $this->webUser->getAccountName();
    $this->submitForm($edit, 'Save');
    $this->contactStorage->resetCache([$contact->id()]);
    $contact = $this->contactStorage->load($contact->id());
    $this->assertSame($this->webUser->id(), $contact->getOwnerId(), 'Node authored by normal user.');
  }

}
