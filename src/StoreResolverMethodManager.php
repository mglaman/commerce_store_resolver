<?php

namespace Drupal\commerce_store_resolver;

use Drupal\commerce_store\Entity\StoreInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

class StoreResolverMethodManager extends DefaultPluginManager implements StoreResolverMethodManagerInterface {

  /**
   * Constructs a new PaymentMethodTypeManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   The cache backend.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/Commerce/StoreResolverMethod', $namespaces, $module_handler, 'Drupal\commerce_store_resolver\Plugin\Commerce\StoreResolverMethod\StoreResolverMethodInterface', 'Drupal\commerce_store_resolver\Annotation\StoreResolverMethod');

    $this->alterInfo('commerce_store_resolver_method_type_info');
    $this->setCacheBackend($cache_backend, 'commerce_store_resolver_plugins');
  }

  /**
   * {@inheritdoc}
   */
  public function resolve() {
    $definitions = $this->getDefinitions();
    foreach ($definitions as $definition) {
      /** @var \Drupal\commerce_store_resolver\Plugin\Commerce\StoreResolverMethod\StoreResolverMethodInterface $instance */
      $instance = $this->createInstance($definition['id']);
      $resolved_store = $instance->resolve();
      if ($resolved_store instanceof StoreInterface) {
        return $resolved_store;
      }
    }
  }

}
