<?php

namespace Drupal\Tests\crm\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\crm\Entity\CRMRelationship;

/**
 * Tests the RelationshipController.
 *
 * @group crm
 */
class RelationshipControllerTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['crm'];

  /**
   * Tests the RelationshipController.
   */
  public function testRelationshipController() {
    // Create a CRM relationship entity.
    $relationship = CRMRelationship::create([
      'type' => 'employee',
      'status' => 1,
      'start' => strtotime('2023-01-01'),
      'end' => strtotime('2023-12-31'),
    ]);
    $relationship->save();

    // Visit the path handled by the RelationshipController.
    $this->drupalGet('crm/relationships');
    $this->assertSession()->statusCodeEquals(200);

    // Assert that the active relationship is displayed in the table.
    $this->assertSession()->responseContains($relationship->bundle->entity->label());
    $this->assertSession()->responseContains($relationship->get('contact_b')->entity->label());
    $this->assertSession()->responseContains('New Lenox');
    $this->assertSession()->responseContains('IL');
    $this->assertSession()->responseContains('webmaster@openknowledge.works');
    $this->assertSession()->responseContains('815-485-0000');

    // Assert that the edit and delete links are present.
    $this->assertLinkByHref($relationship->toUrl('edit-form')->toString());
    $this->assertLinkByHref($relationship->toUrl('delete-form')->toString());

    // Update the relationship status to inactive.
    $relationship->set('status', 0)->save();

    // Visit the page again and assert that the inactive relationship is displayed.
    $this->drupalGet('crm/relationships');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->responseContains('There are no active relationships.');
    $this->assertSession()->responseContains($relationship->bundle->entity->label());
    $this->assertSession()->responseContains($relationship->get('contact_b')->entity->label());

    // Assert that the edit and delete links are present for the inactive relationship.
    $this->assertLinkByHref($relationship->toUrl('edit-form')->toString());
    $this->assertLinkByHref($relationship->toUrl('delete-form')->toString());
  }

}
