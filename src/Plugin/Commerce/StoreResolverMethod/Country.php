<?php

namespace Drupal\commerce_store_resolver\Plugin\Commerce\StoreResolverMethod;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @StoreResolverMethod(
 *   id = "country",
 *   label = "Country",
 *   weight = 10
 * )
 *
 * Deprecates module `commerce_country_store`
 */
class Country extends StoreResolverMethodBase {

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

    foreach ($this->getKeys() as $server_key) {
      if ($current_request->server->has($server_key)) {
        $country_code = Xss::filter($current_request->server->get($server_key));
        // @todo could not get entity query on billing_countries to work.
        $stores = $this->storeStorage->loadMultiple();
        /** @var \Drupal\commerce_store\Entity\StoreInterface $store */
        foreach ($stores as $store) {
          $available = $store->getBillingCountries();
          if (in_array($country_code, $available)) {
            return $store;
          }
        }
      }
    }
  }

  protected function getKeys() {
    // @todo allow custom. Fast.ly and Akamai make you provide/customize.
    return [
      'HTTP_CF_IPCOUNTRY',
      'HTTP_CLOUDFRONT_VIEWER_COUNTRY',
    ];
  }

}
