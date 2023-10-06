<?php

namespace Drupal\Tests\crm\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests the CRM contact type form.
 *
 * @group crm
 */
class CrmContactTypeFormTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['crm'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->drupalLogin($this->createUser([
      'administer contact types',
    ]));
  }

  /**
   * Tests the CRM contact type form.
   */
  public function testCrmContactTypeForm() {
    // Create a contact type.
    $this->drupalGet('admin/structure/crm/contact-types/add');
    $this->assertSession()->statusCodeEquals(200);

    $edit = [
      'label' => 'New Contact Type',
      'id' => 'new_contact_type',
    ];
    $this->submitForm($edit, 'Save contact type');

    $this->assertSession()->pageTextContains('The contact type New Contact Type has been added.');
    $new_contact_type = \Drupal::service('entity_type.manager')
      ->getStorage('crm_contact_type')
      ->load('new_contact_type');

    $this->assertNotNull($new_contact_type, 'The contact type New Contact Type has been added.');

    // Edit the contact type.
    $this->drupalGet('admin/structure/crm/contact-types/manage/new_contact_type');
    $this->assertSession()->statusCodeEquals(200);

    $edit = [
      'label' => 'Updated Contact Type',
    ];
    $this->submitForm($edit, 'Save contact type');

    $this->assertSession()->pageTextContains('The contact type Updated Contact Type has been updated.');

    // Delete the contact type.
    $this->drupalGet('admin/structure/crm/contact-types/manage/new_contact_type/delete');
    $this->assertSession()->statusCodeEquals(200);

    $this->submitForm([], 'Delete');
    $this->assertSession()->pageTextContains('The contact type Updated Contact Type has been deleted.');
  }


}
