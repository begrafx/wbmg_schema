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
   * Returns all fields for this schema (Drupal machine names).
   */
  public function getFields() {
    return [
      'title' => 'string',           // base field
      'field_speaker' => 'string',   // Speaker
      'field_date' => 'datetime',    // Publish Date
      'field_scripture' => 'string', // Scripture
      'field_sermon_date' => 'datetime', // Sermon Date
      'field_audio' => 'entity',     // Audio media reference
      'field_video' => 'entity',     // Video media reference
      'field_series' => 'string',    // Series
      'field_external_id' => 'string', // External ID
    ];
  }

  /**
   * Returns required fields for this schema.
   */
  public function getRequiredFields() {
    return [
      'title',
      'field_date',  // Use Drupal machine name
    ];
  }

}