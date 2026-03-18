<?php

namespace Drupal\wbmg_schema\Schema;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

class SchemaManager extends DefaultPluginManager {

  public function __construct(
    \Traversable $namespaces,
    CacheBackendInterface $cache_backend,
    ModuleHandlerInterface $module_handler
  ) {
    parent::__construct(
      'Plugin/WbmgSchema',
      $namespaces,
      $module_handler,
      'Drupal\wbmg_schema\Plugin\WbmgSchema\BaseSchema',
      'Drupal\wbmg_schema\Annotation\WbmgSchema'
    );

    $this->alterInfo('wbmg_schema_info');
    $this->setCacheBackend($cache_backend, 'wbmg_schema_plugins');
  }

}