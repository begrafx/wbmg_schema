<?php

namespace Drupal\wbmg_schema\Service;

/**
 * Class SchemaValidator
 *
 * Version: 0.2.0
 *
 * Validates entity data against YAML-defined schema.
 * WBMG-Dev compliant: spec-driven, no business logic, extensible.
 */
class SchemaValidator {

  /**
   * @var \Drupal\wbmg_schema\Service\SchemaLoader
   */
  protected $schemaLoader;

  /**
   * Constructor.
   */
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

    // --------------------------------------------------
    // Schema existence check (no silent failure)
    // --------------------------------------------------
    if (!$schema || !is_array($schema)) {
      return ["Schema '{$schema_id}' is missing or invalid."];
    }

    // --------------------------------------------------
    // Required Fields Validation
    // --------------------------------------------------
    if (!empty($schema['required'])) {
      foreach ($schema['required'] as $field) {

        // Field not present at all
        if (!array_key_exists($field, $data)) {
          $errors[] = "[Field: {$field}] Required field is missing.";
          continue;
        }

        $value = $data[$field];

        // Null or empty string
        if ($value === NULL || $value === '') {
          $errors[] = "[Field: {$field}] Required field cannot be empty.";
          continue;
        }

        // Empty array (multi-value edge case)
        if (is_array($value) && empty($value)) {
          $errors[] = "[Field: {$field}] Required field cannot be an empty array.";
        }
      }
    }

    // --------------------------------------------------
    // Multi-value Field Validation
    // --------------------------------------------------
    if (!empty($schema['multi_value'])) {
      foreach ($schema['multi_value'] as $field) {

        if (array_key_exists($field, $data) && $data[$field] !== NULL && !is_array($data[$field])) {
          $errors[] = "[Field: {$field}] Must be an array (multi-value field).";
        }
      }
    }

    // --------------------------------------------------
    // Enum Validation (strict comparison)
    // --------------------------------------------------
    if (!empty($schema['enums'])) {
      foreach ($schema['enums'] as $field => $allowed_values) {

        if (!array_key_exists($field, $data) || $data[$field] === NULL || $data[$field] === '') {
          continue;
        }

        $value = $data[$field];

        if (is_array($value)) {
          foreach ($value as $item) {
            if (!in_array($item, $allowed_values, TRUE)) {
              $errors[] = "[Field: {$field}] Invalid value '{$item}'.";
            }
          }
        }
        else {
          if (!in_array($value, $allowed_values, TRUE)) {
              $errors[] = "[Field: {$field}] Invalid value '{$value}'. Allowed: " . implode(', ', $allowed_values);
          }
        }
      }
    }

    // --------------------------------------------------
    // Basic Type Validation (Extensible)
    // --------------------------------------------------
    if (!empty($schema['types'])) {
      foreach ($schema['types'] as $field => $type) {

        if (!array_key_exists($field, $data) || $data[$field] === NULL) {
          continue;
        }

        $value = $data[$field];

        switch ($type) {

          case 'string':
            if (!is_string($value)) {
              $errors[] = "[Field: {$field}] Must be a string.";
            }
            break;

          case 'array':
            if (!is_array($value)) {
              $errors[] = "[Field: {$field}] Must be an array.";
            }
            break;

          case 'boolean':
            if (!is_bool($value)) {
              $errors[] = "[Field: {$field}] Must be a boolean.";
            }
            break;

          case 'integer':
            if (!is_int($value)) {
              $errors[] = "[Field: {$field}] Must be an integer.";
            }
            break;

          // Future-ready hooks:
          // case 'datetime':
          // case 'uuid':
          // case 'float':

        }
      }
    }

    return $errors;
  }

}