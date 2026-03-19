<?php

namespace Drupal\wbmg_schema\Schema;

class SchemaValidator {

  /**
   * Validate data against a schema.
   *
   * @param string $schema_id
   * @param array $data
   *
   * @return array
   *   Array of error messages
   */
  public function validate($schema_id, array $data) {
    $errors = [];

    /** @var \Drupal\wbmg_schema\Schema\SchemaManager $manager */
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

      // 🔥 THIS IS THE FIX
      if (
        !isset($data[$field]) ||
        $value === NULL ||
        $value === '' ||
        (is_array($value) && empty($value))
      ) {
        $errors[] = "Field '$field' is required.";
      }
    }

    return $errors;
  }

}