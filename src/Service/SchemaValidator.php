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

class SchemaValidator {

  protected $schemaLoader;

  public function __construct(SchemaLoader $schemaLoader) {
    $this->schemaLoader = $schemaLoader;
  }

  public function validate($schema_id, array $data) {
    $errors = [];

    $schema = $this->schemaLoader->getSchema($schema_id);

    if (!$schema) {
      return [];
    }

    if (!empty($schema['required'])) {
      foreach ($schema['required'] as $field) {

        if (!isset($data[$field]) || $data[$field] === NULL || $data[$field] === '') {
          $errors[] = "Required field '" + $field + "' is missing.";
        }

        if (isset($data[$field]) && is_array($data[$field]) && empty($data[$field])) {
          $errors[] = "Required field '" + $field + "' cannot be empty.";
        }
      }
    }

    return $errors;
  }
}
