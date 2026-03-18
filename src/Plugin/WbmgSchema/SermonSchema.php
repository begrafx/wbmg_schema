<?php

namespace Drupal\wbmg_schema\Plugin\WbmgSchema;

use Drupal\wbmg_schema\Annotation\WbmgSchema;

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
      'speaker' => 'string',
      'date' => 'datetime',
      'scripture' => 'string',
    ];
  }

  public function getRequiredFields() {
    return ['title', 'date'];
  }

}