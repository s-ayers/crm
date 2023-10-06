<?php

namespace Drupal\crm\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the contact entity edit forms.
 */
class CrmContactForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {


    $contact = $this->getEntity();
    // Set new Revision.
    $contact->setNewRevision();

    $result = parent::save($form, $form_state);

    $message_arguments = ['%label' => $contact->toLink()->toString()];
    $logger_arguments = [
      '%label' => $contact->label(),
      'link' => $contact->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New contact %label has been created.', $message_arguments));
        $this->logger('crm_contact')->notice('Created new contact %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The contact %label has been updated.', $message_arguments));
        $this->logger('crm_contact')->notice('Updated contact %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.crm_contact.canonical', [
      'crm_contact' => $contact->id(),
    ]);

    if ($contact->id()) {
      $form_state->setValue('id', $contact->id());
      $form_state->set('id', $contact->id());
      if ($contact->access('view')) {
        $form_state->setRedirect(
          'entity.crm_contact.canonical',
          ['crm_contact' => $contact->id()]
        );
      }
      else {
        $form_state->setRedirect('<front>');
      }

    }

    return $result;
  }

}
