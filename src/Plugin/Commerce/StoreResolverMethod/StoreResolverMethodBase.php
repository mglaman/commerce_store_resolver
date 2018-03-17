<?php

namespace Drupal\commerce_store_resolver\Plugin\Commerce\StoreResolverMethod;

use Drupal\commerce_store_resolver\Plugin\Commerce\StoreResolverMethod\StoreResolverMethodInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class StoreResolverMethodBase extends PluginBase implements StoreResolverMethodInterface, ContainerFactoryPluginInterface {

  /**
   * The store storage.
   *
   * @var \Drupal\commerce_store\StoreStorageInterface
   */
  protected $storeStorage;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->storeStorage = $entity_type_manager->getStorage('commerce_store');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

}
