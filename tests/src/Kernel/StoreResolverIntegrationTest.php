<?php

namespace Drupal\Tests\commerce_store_resolver\Kernel;

use Drupal\commerce_store\Entity\Store;
use Drupal\commerce_store\Entity\StoreInterface;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Symfony\Component\HttpFoundation\Request;

class StoreResolverIntegrationTest extends EntityKernelTestBase {

  public static $modules = [
    'system',
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

  public function testIntegration() {
    // US set to default.
    // Fake CloudFlare header.
    $request = Request::create('');
    $request->server->set('HTTP_CF_IPCOUNTRY', 'CA');
    $this->container->get('request_stack')->push($request);

    $store_resolver = $this->container->get('commerce_store.chain_store_resolver');
    $store = $store_resolver->resolve();
    $this->assertInstanceOf(StoreInterface::class, $store);
    $this->assertEquals($store->id(), $this->store2->id());
  }

}
