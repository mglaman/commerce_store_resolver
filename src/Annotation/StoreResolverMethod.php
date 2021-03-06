<?php

namespace Drupal\commerce_store_resolver\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines the store resolver method plugin annotation object.
 *
 * Plugin namespace: Plugin\Commerce\StoreResolverMethod.
 *
 * @see plugin_api
 *
 * @Annotation
 */
class StoreResolverMethod extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The payment type label.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $label;

  /**
   * The description of the language negotiation plugin.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $description;

  /**
   * The default weight of the language negotiation plugin.
   *
   * @var int
   */
  public $weight;

  /**
   * A required module that must exist for the plugin to be considered.
   *
   * @var string optional
   */
  public $module;

}
