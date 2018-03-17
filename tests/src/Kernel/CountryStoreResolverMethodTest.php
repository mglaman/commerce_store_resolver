<?php

namespace Drupal\Tests\commerce_store_resolver\Kernel;

use Drupal\commerce_store\Entity\Store;
use Drupal\commerce_store\Entity\StoreInterface;
use Drupal\commerce_store\StoreCreationTrait;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Symfony\Component\HttpFoundation\Request;

class CountryStoreResolverMethodTest extends EntityKernelTestBase {

  public static $modules = [
    'address',
    'datetime',
    'entity',
    'options',
    'inline_entity_form',
    'views',
    'commerce',
    'commerce_price',
    'commerce_store',
    'commerce_store_resolver',
  ];

  /**
   * The default store.
   *
   * @var \Drupal\commerce_store\Entity\StoreInterface
   */
  protected $store1;

  /**
   * The default store.
   *
   * @var \Drupal\commerce_store\Entity\StoreInterface
   */
  protected $store2;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installSchema('system', 'router');
    $this->installEntitySchema('commerce_currency');
    $this->installEntitySchema('commerce_store');
    $this->installConfig(['commerce_store']);

    $currency_importer = $this->container->get('commerce_price.currency_importer');
    $currency_importer->import('USD');
    $currency_importer->import('CAD');

    $store = Store::create([
      'type' => 'online',
      'uid' => 1,
      'name' => 'United States',
      'mail' => 'admin@example.com',
      'address' => [
        'country_code' => 'US',
        'address_line1' => $this->randomString(),
        'locality' => $this->randomString(5),
        'administrative_area' => 'WI',
        'postal_code' => '53597',
      ],
      'default_currency' => 'USD',
      'billing_countries' => [
        'US',
      ],
    ]);
    $store->save();
    $this->store1 = $store;

    $store = Store::create([
      'type' => 'online',
      'uid' => 1,
      'name' => 'Canada',
      'mail' => 'admin@example.com',
      'address' => [
        'country_code' => 'CA',
        'address_line1' => $this->randomString(),
        'locality' => $this->randomString(5),
        'administrative_area' => 'BC',
        'postal_code' => ' V1X 6Y5',
      ],
      'default_currency' => 'CAD',
      'billing_countries' => [
        'CA',
      ],
    ]);
    $store->save();
    $this->store2 = $store;
    $this->container->get('entity_type.manager')->getStorage('commerce_store')->markAsDefault($this->store1);
  }

  /**
   * Tests the resolver based on country code header.
   */
  public function testResolverUS() {
    // Fake CloudFlare header.
    $request = Request::create('');
    $request->server->set('HTTP_CF_IPCOUNTRY', 'US');
    // Push the request to the request stack so `current_route_match` works.
    $this->container->get('request_stack')->push($request);

    $manager = $this->container->get('plugin.manager.store_resolver_method');
    $store = $manager->resolve();

    $this->assertInstanceOf(StoreInterface::class, $store);
    $this->assertEquals($store->id(), $this->store1->id());
  }

  /**
   * Tests the resolver based on country code header.
   */
  public function testResolverCA() {
    // Fake CloudFlare header.
    $request = Request::create('');
    $request->server->set('HTTP_CF_IPCOUNTRY', 'CA');
    // Push the request to the request stack so `current_route_match` works.
    $this->container->get('request_stack')->push($request);

    $manager = $this->container->get('plugin.manager.store_resolver_method');
    $store = $manager->resolve();

    $this->assertInstanceOf(StoreInterface::class, $store);
    $this->assertEquals($store->id(), $this->store2->id());
  }

  public function testIfMissing() {
    $manager = $this->container->get('plugin.manager.store_resolver_method');
    $store = $manager->resolve();

    $this->assertNotInstanceOf(StoreInterface::class, $store);
  }

  /**
   * Tests the resolver based on country code header.
   */
  public function testResolverCloufFront() {
    // Fake CloudFlare header.
    $request = Request::create('');
    $request->server->set('HTTP_CLOUDFRONT_VIEWER_COUNTRY', 'US');
    // Push the request to the request stack so `current_route_match` works.
    $this->container->get('request_stack')->push($request);

    $manager = $this->container->get('plugin.manager.store_resolver_method');
    $store = $manager->resolve();

    $this->assertInstanceOf(StoreInterface::class, $store);
    $this->assertEquals($store->id(), $this->store1->id());
  }

}
