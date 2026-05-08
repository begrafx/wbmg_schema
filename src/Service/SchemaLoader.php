<?php

namespace Drupal\wbmg_schema\Service;

use Drupal\Core\Extension\ModuleExtensionList;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class SchemaLoader
 *
 * Version: 0.2.0
 *
 * Loads WBMG schema definitions from YAML.
 *
 * Responsibilities:
 * - Load schema definitions
 * - Validate top-level schema structure
 * - Provide deterministic schema access
 *
 * Non-responsibilities:
 * - Validation logic
 * - Entity resolution
 * - Business rules
 */
class SchemaLoader {

  /**
   * Loaded schema definitions.
   *
   * @var array
   */
  protected array $schemas = [];

  /**
   * Constructor.
   */
  public function __construct(ModuleExtensionList $module_extension_list) {

    $module_path = $module_extension_list->getPath('wbmg_schema');

    $schema_file = DRUPAL_ROOT . '/' . $module_path . '/config/schema/wbmg_schema.schema.yml';

    if (!file_exists($schema_file)) {
      throw new \RuntimeException(
        "WBMG Schema file not found: {$schema_file}"
      );
    }

    try {
      $parsed = Yaml::parseFile($schema_file);
    }
    catch (ParseException $e) {
      throw new \RuntimeException(
        'WBMG Schema YAML parsing failed: ' . $e->getMessage()
      );
    }

    if (!is_array($parsed)) {
      throw new \RuntimeException(
        'WBMG Schema file is invalid or empty.'
      );
    }

    $this->schemas = $this->validateSchemaStructure($parsed);
  }

  /**
   * Get schema definition by ID.
   *
   * @param string $id
   *   Schema ID.
   *
   * @return array|null
   *   Schema definition or NULL.
   */
  public function getSchema(string $id): ?array {
    return $this->schemas[$id] ?? NULL;
  }

  /**
   * Return all loaded schemas.
   *
   * @return array
   *   Loaded schemas.
   */
  public function getAllSchemas(): array {
    return $this->schemas;
  }

  /**
   * Validate top-level schema structure.
   *
   * This validates ONLY structural integrity.
   * Validation rules belong in SchemaValidator.
   *
   * @param array $schemas
   *   Parsed schema array.
   *
   * @return array
   *   Validated schemas.
   */
  protected function validateSchemaStructure(array $schemas): array {

    foreach ($schemas as $schema_id => $definition) {

      if (!is_array($definition)) {
        throw new \RuntimeException(
          "Schema '{$schema_id}' definition must be an array."
        );
      }

      // Optional sections must be arrays if present.
      $array_sections = [
        'required',
        'multi_value',
        'enums',
        'types',
      ];

      foreach ($array_sections as $section) {
        if (
          isset($definition[$section]) &&
          !is_array($definition[$section])
        ) {
          throw new \RuntimeException(
            "Schema '{$schema_id}' section '{$section}' must be an array."
          );
        }
      }
    }

    return $schemas;
  }

}