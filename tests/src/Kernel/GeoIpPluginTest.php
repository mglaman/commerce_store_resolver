<?php

namespace Drupal\Tests\commerce_store_resolver\Kernel;

use Drupal\KernelTests\KernelTestBase;

class GeoIpPluginTest extends KernelTestBase {

  protected static $modules = [
    'commerce_store_resolver',
  ];

  public function testGeoIpRemoved() {
    // Test to verify the GeoIP module is removed since geoip does not exist.
    $manager = $this->container->get('plugin.manager.store_resolver_method');
    $this->assertFalse($manager->hasDefinition('geoip'));
  }

}
