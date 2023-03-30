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
    $result = parent::save($form, $form_state);

    $entity = $this->getEntity();

    $message_arguments = ['%label' => $entity->toLink()->toString()];
    $logger_arguments = [
      '%label' => $entity->label(),
      'link' => $entity->toLink($this->t('View'))->toString(),
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

    $form_state->setRedirect('entity.crm_contact.canonical', ['crm_contact' => $entity->id()]);

    return $result;
  }

}
