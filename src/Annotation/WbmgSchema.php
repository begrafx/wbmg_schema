<?php

namespace Drupal\wbmg_schema\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a WBMG Schema plugin annotation.
 *
 * @Annotation
 */
class WbmgSchema extends Plugin {

  /**
   * The schema ID.
   *
   * @var string
   */
  public $id;

  /**
   * The schema label.
   *
   * @var string
   */
  public $label;

}