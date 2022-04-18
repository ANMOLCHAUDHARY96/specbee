<?php

namespace Drupal\insd_specbee\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\insd_specbee\Services\Timezone;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base Class for InsdGlobal Config form.
 */
class SpecbeeConfigForm extends ConfigFormBase {
  const SPECBEE_CONFIG = 'spec_global_configurations.settings';

  /**
   * Drupal\insd_specbee\Services\Timezone definition.
   *
   * @var Drupal\insd_specbee\Services\Timezone
   */
  protected $timezone;


  /**
   * Variable used to store entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * MC Global configuration constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity Type Manager.
   */
  public function __construct(Timezone $timezone,
    EntityTypeManagerInterface $entityTypeManager) {
    $this->timezone = $timezone;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('insd_specbee.timezone_listing'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      self::SPECBEE_CONFIG,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'insead_global_configurations';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(self::SPECBEE_CONFIG);
    $form['country'] = [
      '#title' => $this->t('Country'),
      '#type' => 'textfield',
      '#default_value' => $config->get('country'),
      '#description' => $this->t('Select the Country.'),
    ];
    $form['city'] = [
      '#title' => $this->t('city'),
      '#type' => 'textfield',
      '#default_value' => $config->get('city'),
      '#description' => $this->t('Select the City.'),
    ];
    $form['timezone'] = [
      '#title' => $this->t('Timezone'),
      '#type' => 'select',
      '#default_value' => $config->get('timezone'),
      '#options' => [
        'America/Chicago' => $this->t('America/Chicago'),
        'America/New_York' => $this->t('America/New_York'),
        'Asia/Tokyo' => $this->t('Asia/Tokyo'),
        'Asia/Dubai' => $this->t('Asia/Dubai'),
        'Asia/Kolkata' => $this->t('Asia/Kolkata'),
        'Europe/Amsterdam' => $this->t('Europe/Amsterdam'),
        'Europe/Oslo' => $this->t('Europe/Oslo'),
        'Europe/London' => $this->t('Europe/London'),

      ],
      '#description' => $this->t('Select the Timezone.'),
    ];
    $form_state->setCached(FALSE);
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $config = $this->config(self::SPECBEE_CONFIG);
    $config->set('country', $values['country']);
    $config->set('city', $values['city']);
    $config->set('timezone', $values['timezone']);
    $config->save();
    $this->messenger()->addStatus($this->getCurrentDateTime());
  }

  const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

  /**
   * Function to get current date time.
   *
   *  $form
   *   Form state.
   *
   * @return mixed
   *   Return current date time.
   */

  public function getCurrentDateTime() {
    $config = $this->config(self::SPECBEE_CONFIG);
    $format = self::DATE_TIME_FORMAT;
    $timezone = $config->get('timezone');
    $datetime = $this->timezone->getDateTimeByTimeZone($format, $timezone);
    return $datetime;
  }

}
