<?php

namespace Drupal\Tests\commerce_store_resolver\Kernel;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceModifierInterface;
use Drupal\KernelTests\KernelTestBase;

class PluginWeightTest extends KernelTestBase implements ServiceModifierInterface  {

  protected static $modules = [
    'commerce_store_resolver',
  ];

  /**
   * @inheritDoc
   */
  public function alter(ContainerBuilder $container) {
    // Fake GeoIP is available.
    $modules = $container->getParameter('container.modules');
    $modules['geoip'] = [
      'type' => 'module',
      'pathname' => '',
      'filename' => '',
    ];
    $container->setParameter('container.modules', $modules);
  }

  public function testDefaultWeights() {
    $manager = $this->container->get('plugin.manager.store_resolver_method');
    $definitions = $manager->getDefinitions();

    $definition = array_shift($definitions);
    $this->assertEquals('domain', $definition['id']);
    $definition = array_shift($definitions);
    $this->assertEquals('country', $definition['id']);
    $definition = array_shift($definitions);
    $this->assertEquals('geoip', $definition['id']);
  }

}
