<?php

namespace Drupal\wbmg_schema\Service;

class Validator {

  protected $schemaLoader;

  public function __construct(SchemaLoader $schemaLoader) {
    $this->schemaLoader = $schemaLoader;
  }

  /**
   * Validate node data against schema.
   */
  public function validate($bundle, array $data) {
    $errors = [];

    $schema = $this->schemaLoader->getSchema($bundle);

    if (!$schema) {
      return [];
    }

    // Required fields
    if (!empty($schema['required'])) {
      foreach ($schema['required'] as $field) {

        if (!isset($data[$field]) || $data[$field] === NULL || $data[$field] === '') {
          $errors[] = "Required field '{$field}' is missing.";
        }

        if (isset($data[$field]) && is_array($data[$field]) && empty($data[$field])) {
          $errors[] = "Required field '{$field}' cannot be empty.";
        }
      }
    }

    return $errors;
  }

}