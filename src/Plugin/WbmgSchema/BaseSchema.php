<?php

namespace Drupal\wbmg_schema\Plugin\WbmgSchema;

use Drupal\Component\Plugin\PluginBase;

abstract class BaseSchema extends PluginBase {

  /**
   * Returns schema field definitions.
   */
  public function getFields() {
    return [];
  }

  /**
   * Returns required fields.
   */
  public function getRequiredFields() {
    return [];
  }

}