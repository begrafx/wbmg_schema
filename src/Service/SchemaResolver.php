<?php
/**
 * @file
 * WBMG Schema Module
 *
 * Version: 0.1.5
 * Date: 2026-04-01
 * Description:
 *   Core schema validation system for Angelos.
 */


namespace Drupal\wbmg_schema\Service;

use Drupal\Core\Entity\EntityInterface;

class SchemaResolver {

  public function getSchemaId(EntityInterface $entity) {
    return $entity->bundle();
  }
}
