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

  public function getFields() {
    return [
      'title' => 'string',
      'field_speaker' => 'string',
      'field_sermon_date' => 'datetime',   // optional
      'field_date' => 'datetime',          // required Publish Date
      'field_scripture' => 'string',
      'field_audio' => 'entity',           // optional Media reference
      'field_video' => 'entity',           // optional Media reference
      'field_series' => 'string',
      'field_external_id' => 'string',
    ];
  }

  public function getRequiredFields() {
    return [
      'title',
      'field_date',  // matches “Publish Date” required field
    ];
  }

}