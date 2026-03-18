<?php

namespace Drupal\wbmg_schema\Plugin\WbmgSchema;

use Drupal\wbmg_schema\Plugin\WbmgSchema\BaseSchema;

/**
 * @WbmgSchema(
 *   id = "sermon",
 *   label = "Sermon"
 * )
 */
class SermonSchema extends BaseSchema {

  /**
   * Returns the list of fields in this schema.
   *
   * Keys must match Drupal field machine names.
   *
   * @return array
   *   Array of field_name => type
   */
  public function getFields() {
    return [
      // Base field
      'title' => 'string',

      // Custom fields (use actual Drupal field machine names)
      'field_speaker' => 'string',
      'field_date' => 'datetime',
      'field_scripture' => 'string',
    ];
  }

  /**
   * Returns an array of required fields for this schema.
   *
   * Keys must match Drupal field machine names.
   *
   * @return array
   *   Array of required field machine names
   */
  public function getRequiredFields() {
    return [
      'title',
      'field_date',
    ];
  }

}