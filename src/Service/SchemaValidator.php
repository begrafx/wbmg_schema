<?php

namespace Drupal\wbmg_schema\Service;

/**
 * Validates entity data against defined schemas.
 */
class SchemaValidator {

  /**
   * Schema definitions.
   *
   * @var array
   */
  protected $schemas;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->loadSchemas();
  }

  /**
   * Load schema definitions from YAML.
   */
  protected function loadSchemas() {
    $path = DRUPAL_ROOT . '/modules/custom/wbmg_schema/wbmg_schema.schema.yml';

    if (file_exists($path)) {
      $this->schemas = \Symfony\Component\Yaml\Yaml::parseFile($path);
    }
    else {
      $this->schemas = [];
    }
  }

  /**
   * Get schema by ID.
   */
  public function getSchema(string $schema_id): ?array {
    return $this->schemas[$schema_id] ?? NULL;
  }

  /**
   * Validate data against schema.
   *
   * @param string $schema_id
   * @param array $data
   *
   * @return array
   *   List of validation error messages.
   */
  public function validate(string $schema_id, array $data): array {
    $errors = [];

    $schema = $this->getSchema($schema_id);

    if (!$schema) {
      return $errors;
    }

    /**
     * ------------------------------
     * 1. FIELD EXISTENCE VALIDATION
     * ------------------------------
     * Ensure schema fields exist in entity data.
     */
    if (!empty($schema['required'])) {
      foreach ($schema['required'] as $field_name) {
        if (!array_key_exists($field_name, $data)) {
          $errors[] = "Field '{$field_name}' is defined in schema but does not exist on this entity.";
        }
      }
    }

    if (!empty($schema['optional'])) {
      foreach ($schema['optional'] as $field_name) {
        if (!array_key_exists($field_name, $data)) {
          $errors[] = "Optional field '{$field_name}' is defined in schema but does not exist on this entity.";
        }
      }
    }

    /**
     * ------------------------------
     * 2. REQUIRED FIELD VALIDATION
     * ------------------------------
     */
    if (!empty($schema['required'])) {
      foreach ($schema['required'] as $field_name) {

        // Skip if field doesn't exist (already reported above)
        if (!array_key_exists($field_name, $data)) {
          continue;
        }

        $value = $data[$field_name];

        if ($value === NULL || $value === '' || (is_array($value) && empty($value))) {
          $errors[] = "Field '{$field_name}' is required.";
        }
      }
    }

    /**
     * ------------------------------
     * 3. MULTI-VALUE VALIDATION
     * ------------------------------
     */
    if (!empty($schema['multi_value'])) {
      foreach ($schema['multi_value'] as $field_name) {

        if (!array_key_exists($field_name, $data)) {
          continue;
        }

        $value = $data[$field_name];

        if (!is_array($value) && $value !== NULL) {
          $errors[] = "Field '{$field_name}' must be a multi-value array.";
        }
      }
    }

    return $errors;
  }

}