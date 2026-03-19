<?php

namespace Drupal\wbmg_schema\Plugin\WbmgSchema;

use Drupal\wbmg_schema\Plugin\BaseSchema;
use Drupal\wbmg_schema\Annotation\WbmgSchema;

/**
 * @WbmgSchema(
 *   id = "sermon",
 *   label = "Sermon"
 * )
 */
class SermonSchema extends BaseSchema {

  /**
   * Fields and their types.
   */
  public function getFields(): array {
    return [
      'title' => 'string',           // base field
      'field_speaker' => 'string',
      'field_date' => 'datetime',    // required
      'field_scripture' => 'string',
      'field_audio' => 'entity',
      'field_video' => 'entity',
      'field_series' => 'string',
      'field_external_id' => 'string',
      'field_sermon_date' => 'datetime',
    ];
  }

  /**
   * Required fields.
   */
  public function getRequiredFields(): array {
    return [
      'title',
      'field_date',
    ];
  }

}