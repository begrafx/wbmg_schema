<?php

namespace Drupal\wbmg_schema\Service;

use Drupal\Component\Serialization\Yaml;

/**
 * Class SchemaLoader
 *
 * Version: 0.2.0
 *
 * Loads and provides access to WBMG schema definitions.
 */
class SchemaLoader {

  /**
   * Cached schema definitions.
   *
   * @var array
   */
  protected $schemas = [];

  /**
   * Constructor.
   */
  public function __construct() {

    $module_path = \Drupal::service('extension.list.module')->getPath('wbmg_schema');
    $file = DRUPAL_ROOT . '/' . $module_path . '/config/schema/wbmg_schema.schema.yml';

    if (!file_exists($file)) {
      \Drupal::logger('wbmg_schema')->error('Schema file not found: @file', ['@file' => $file]);
      return;
    }

    try {
      $contents = file_get_contents($file);
      $parsed = Yaml::decode($contents);

      if (!is_array($parsed)) {
        \Drupal::logger('wbmg_schema')->error('Schema file is invalid or empty.');
        return;
      }

      // Optional normalization hook (future-safe)
      $this->schemas = $this->normalizeSchemas($parsed);
    }
    catch (\Throwable $e) {
      \Drupal::logger('wbmg_schema')->error(
        'Failed to load schema file: @error',
        ['@error' => $e->getMessage()]
      );
    }
  }

  /**
   * Get a schema by ID.
   *
   * @param string $id
   * @return array|null
   */
  public function getSchema($id) {
    return $this->schemas[$id] ?? NULL;
  }

  /**
   * Normalize schema structure (future extension point).
   *
   * @param array $schemas
   * @return array
   */
  protected function normalizeSchemas(array $schemas) {

    foreach ($schemas as $id => &$schema) {

      // Ensure expected keys exist
      $schema += [
        'required' => [],
        'optional' => [],
        'multi_value' => [],
        'enums' => [],
        'types' => [],
      ];

      // Force arrays where expected
      foreach (['required', 'optional', 'multi_value'] as $key) {
        if (!is_array($schema[$key])) {
          $schema[$key] = [];
        }
      }

      if (!is_array($schema['enums'])) {
        $schema['enums'] = [];
      }

      if (!is_array($schema['types'])) {
        $schema['types'] = [];
      }
    }

    return $schemas;
  }

}