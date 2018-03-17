<?php

namespace Drupal\commerce_store_resolver\Plugin\Commerce\StoreResolverMethod;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @StoreResolverMethod(
 *   id = "domain",
 *   label = "Domain",
 *   weight = 0
 * )
 *
 * Deprecates module `commerce_country_domain`
 */
class Domain extends StoreResolverMethodBase {

  protected $requestStack;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, RequestStack $request_stack) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager);
    $this->requestStack = $request_stack;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('request_stack')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function resolve() {
    $current_request = $this->requestStack->getCurrentRequest();
    $current_host = $current_request->getHost();
    $query = $this->storeStorage->getQuery();
    $query->condition('domain', $current_host);
    $store_ids = $query->execute();
    if (!empty($store_ids)) {
      $store_id = reset($store_ids);
      return $this->storeStorage->load($store_id);
    }
  }

}
