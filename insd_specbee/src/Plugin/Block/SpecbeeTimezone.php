<?php

namespace Drupal\insd_specbee\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\insd_specbee\Services\Timezone;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Text Resize Block' block.
 *
 * @Block(
 *  id = "specbee_timezone",
 *  admin_label = @Translation("Specbee Timezone")
 * )
 */
class SpecbeeTimezone extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\insd_specbee\Services\Timezone definition.
   *
   * @var Drupal\insd_specbee\Services\Timezone
   */
  protected $timezone;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    Timezone $timezone
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->timezone = $timezone;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('insd_specbee.timezone_listing')

    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\insd_specbee\Form\SpecbeeConfigForm');
    return [
      '#title' => $form,
    ];
  }

}
