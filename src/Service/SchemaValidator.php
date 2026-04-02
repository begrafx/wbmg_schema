<?php

namespace Drupal\wbmg_schema\Service;

/**
 * Class SchemaValidator
 *
 * Version: 0.2.0
 *
 * Validates entity data against YAML-defined schema.
 */
class SchemaValidator {

  protected $schemaLoader;

  public function __construct(SchemaLoader $schemaLoader) {
    $this->schemaLoader = $schemaLoader;
  }

  /**
   * Validate entity data against schema.
   *
   * @param string $schema_id
   * @param array $data
   *
   * @return array
   *   List of validation errors.
   */
  public function validate($schema_id, array $data) {
    $errors = [];

    $schema = $this->schemaLoader->getSchema($schema_id);

    if (!$schema) {
      return [];
    }

    /**
     * ----------------------------------------
     * Required Fields Validation
     * ----------------------------------------
     */
    if (!empty($schema['required'])) {
      foreach ($schema['required'] as $field) {

        // Missing field entirely
        if (!array_key_exists($field, $data)) {
          $errors[] = "Required field '{$field}' is missing.";
          continue;
        }

        $value = $data[$field];

        // Null or empty string
        if ($value === NULL || $value === '') {
          $errors[] = "Required field '{$field}' cannot be empty.";
          continue;
        }

        // Empty array (for multi-value)
        if (is_array($value) && empty($value)) {
          $errors[] = "Required field '{$field}' cannot be an empty array.";
        }
      }
    }

    /**
     * ----------------------------------------
     * Multi-value Validation
     * ----------------------------------------
     */
    if (!empty($schema['multi_value'])) {
      foreach ($schema['multi_value'] as $field) {
        if (isset($data[$field]) && !is_array($data[$field]) && $data[$field] !== NULL) {
          $errors[] = "Field '{$field}' must be an array (multi-value field).";
        }
      }
    }

    /**
     * ----------------------------------------
     * Enum Validation
     * ----------------------------------------
     */
    if (!empty($schema['enums'])) {
      foreach ($schema['enums'] as $field => $allowed_values) {

        if (!isset($data[$field]) || $data[$field] === NULL || $data[$field] === '') {
          continue;
        }

        $value = $data[$field];

        // Handle multi-value enums if needed later
        if (is_array($value)) {
          foreach ($value as $item) {
            if (!in_array($item, $allowed_values)) {
              $errors[] = "Invalid value '{$item}' for field '{$field}'.";
            }
          }
        }
        else {
          if (!in_array($value, $allowed_values)) {
            $errors[] = "Invalid value '{$value}' for field '{$field}'. Allowed values: " . implode(', ', $allowed_values);
          }
        }
      }
    }

    return $errors;
  }

}