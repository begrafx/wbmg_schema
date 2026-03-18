<?php

namespace Drupal\wbmg_schema\Schema;

class SchemaValidator {

  protected $schemaManager;

  public function __construct(SchemaManager $schemaManager) {
    $this->schemaManager = $schemaManager;
  }

  /**
   * Validate data against a schema.
   */
  public function validate(string $schema_id, array $data): array {
    $errors = [];

    if (!$this->schemaManager->hasDefinition($schema_id)) {
      $errors[] = "Schema '$schema_id' does not exist.";
      return $errors;
    }

    $schema = $this->schemaManager->createInstance($schema_id);

    // Check required fields
    foreach ($schema->getRequiredFields() as $field) {
      if (!isset($data[$field]) || $data[$field] === '') {
        $errors[] = "Field '$field' is required.";
      }
    }

    // Check field types
    foreach ($schema->getFields() as $field => $type) {
      if (!isset($data[$field])) {
        continue;
      }

      $value = $data[$field];

      switch ($type) {
        case 'string':
          if (!is_string($value)) {
            $errors[] = "Field '$field' must be a string.";
          }
          break;

        case 'datetime':
          if (strtotime($value) === false) {
            $errors[] = "Field '$field' must be a valid datetime.";
          }
          break;

        case 'integer':
          if (!is_int($value)) {
            $errors[] = "Field '$field' must be an integer.";
          }
          break;
      }
    }

    return $errors;
  }

}