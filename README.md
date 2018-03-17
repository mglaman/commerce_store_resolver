# Commerce Store Resolver

Contributed project to store various store resolving methods.

* https://www.drupal.org/project/commerce_country_store
* https://www.drupal.org/project/commerce_store_domain
* Store via cookie value

Create a module which acts as a repository for Store resolving techniques. In order for this to work, it would have to 
be a per-store bundle configuration with per-store bundle resolvers.

Challenge: priority of store resolvers, in the event someone used non-default `online` bundle. Probability low, worth 
mentioning but not covering.

Overview

* StoreResolverMethod plugin. Act like language negotiator plugins
* StoreResolverMethod plugins are enabled on the store bundle
* StoreResolverMethod plugins have a weight and execution order
* StoreResolverMethod plugins can add base fields to store entities (language code, domain, etc.)

The idea is to provide a user interface for "default store" selection like the language interface, and consolidate 
community efforts.
