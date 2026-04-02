<?php
/**
 * @file
 * WBMG Schema Module
 *
 * Version: 0.1.5
 * Date: 2026-04-01
 * Description:
 *   Core schema validation system for Angelos.
 */


namespace Drupal\wbmg_schema\Service;

class SchemaLoader {

  protected $schemas;

  public function __construct() {
    $module_path = \Drupal::service('extension.list.module')->getPath('wbmg_schema');
    $file = DRUPAL_ROOT . '/' . $module_path . '/config/schema/wbmg_schema.schema.yml';

    if (file_exists($file)) {
      $this->schemas = yaml_parse_file($file);
    } else {
      $this->schemas = [];
    }
  }

  public function getSchema($id) {
    return $this->schemas[$id] ?? NULL;
  }
}
