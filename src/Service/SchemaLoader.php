<?php

namespace Drupal\wbmg_schema\Service;

use Drupal\Core\Serialization\Yaml;

class SchemaLoader {

  protected $schemas;

  /**
   * Load all schemas (cached).
   */
  public function getAllSchemas() {
    if (!isset($this->schemas)) {
      $path = DRUPAL_ROOT . '/modules/custom/wbmg_schema/config/wbmg_schema.schema.yml';

      if (!file_exists($path)) {
        $this->schemas = [];
      }
      else {
        $this->schemas = Yaml::decode(file_get_contents($path)) ?? [];
      }
    }

    return $this->schemas;
  }

  /**
   * Get schema for a specific content type.
   */
  public function getSchema($bundle) {
    $schemas = $this->getAllSchemas();
    return $schemas[$bundle] ?? NULL;
  }

}