<?php

namespace Drupal\wbmg_schema\Schema;

class SchemaValidator {

  public function validate($schema_id, array $data) {
    $errors = [];

    $manager = \Drupal::service('wbmg_schema.manager');
    $definition = $manager->getDefinition($schema_id);

    if (!$definition) {
      return ["Schema '$schema_id' not found."];
    }

    $class = $definition['class'];
    $schema = new $class();

    $required_fields = $schema->getRequiredFields();

    foreach ($required_fields as $field) {

      $value = $data[$field] ?? NULL;

      // 🔥 FINAL FIX — robust empty check
      if (
        !array_key_exists($field, $data) ||
        $value === NULL ||
        (is_string($value) && trim($value) === '') ||
        (is_array($value) && count(array_filter($value)) === 0)
      ) {
        $errors[] = "Field '$field' is required.";
      }
    }

    return $errors;
  }

}