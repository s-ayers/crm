<?php

namespace Drupal\crm\Form;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form handler for crm relationship type forms.
 */
class CrmRelationshipTypeForm extends BundleEntityFormBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $entity_type = $this->entity;
    if ($this->operation == 'edit') {
      $form['#title'] = $this->t('Edit %label crm relationship type', ['%label' => $entity_type->label()]);
    }

    $form['label'] = [
      '#title' => $this->t('Label'),
      '#type' => 'textfield',
      '#default_value' => $entity_type->label(),
      '#description' => $this->t('The human-readable name of this crm relationship type.'),
      '#required' => TRUE,
      '#size' => 30,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $entity_type->id(),
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
      '#machine_name' => [
        'exists' => ['Drupal\crm\Entity\CrmRelationshipType', 'load'],
        'source' => ['label'],
      ],
      '#description' => $this->t('A unique machine-readable name for this crm relationship type. It must only contain lowercase letters, numbers, and underscores.'),
    ];

    $form['contact_type_a'] = [
      '#title' => $this->t('Contact type A'),
      '#type' => 'select',
      '#options' => $this->getContactTypeOptions(),
      '#default_value' => $entity_type->get('contact_type_a'),
      '#description' => $this->t('The contact type for the first contact in the relationship.'),
      '#required' => TRUE,
    ];

    $form['contact_type_b'] = [
      '#title' => $this->t('Contact type B'),
      '#type' => 'select',
      '#options' => $this->getContactTypeOptions(),
      '#default_value' => $entity_type->get('contact_type_b'),
      '#description' => $this->t('The contact type for the second contact in the relationship.'),
      '#required' => TRUE,
    ];

    $form['label_b_a'] = [
      '#title' => $this->t('Label A to B'),
      '#type' => 'textfield',
      '#default_value' => $entity_type->get('label_b_a'),
      '#description' => $this->t('The human-readable name of this crm relationship type from contact A to contact B.'),
      '#required' => TRUE,
      '#size' => 30,
    ];

    $form['description'] = [
      '#title' => $this->t('Description'),
      '#type' => 'textarea',
      '#default_value' => $entity_type->get('description'),
      '#description' => $this->t('A description of this crm relationship type.'),
    ];

    return $this->protectBundleIdElement($form);
  }

  protected function getContactTypeOptions() {
    $crm_contact_type = \Drupal::service('entity_type.bundle.info')->getBundleInfo('crm_contact');
    $options = [];
    foreach ($crm_contact_type as $type => $label) {
      $options[$type] = $label['label'];
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);
    $actions['submit']['#value'] = $this->t('Save crm relationship type');
    $actions['delete']['#value'] = $this->t('Delete crm relationship type');
    return $actions;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity_type = $this->entity;
    $entity_type
      ->set('id', trim($entity_type->id()))
      ->set('label', trim($entity_type->label()));
    $status = $entity_type->save();

    $t_args = ['%name' => $entity_type->label()];
    if ($status == SAVED_UPDATED) {
      $message = $this->t('The crm relationship type %name has been updated.', $t_args);
    }
    elseif ($status == SAVED_NEW) {
      $message = $this->t('The crm relationship type %name has been added.', $t_args);
    }
    $this->messenger()->addStatus($message);

    $form_state->setRedirectUrl($entity_type->toUrl('collection'));
  }

}
