<?php

namespace Drupal\commerce_store_resolver\Plugin\Commerce\StoreResolverMethod;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @StoreResolverMethod(
 *   id = "geoip",
 *   label = "GeoIP",
 *   module = "geoip"
 * )
 *
 * Deprecates module `commerce_country_store`
 *
 * @todo actually implement geoip.
 */
class GeoIp extends StoreResolverMethodBase {

  /**
   * {@inheritdoc}
   */
  public function resolve() {
    return NULL;
  }

}
