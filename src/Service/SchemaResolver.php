<?php

namespace Drupal\wbmg_schema\Service;

use Drupal\Core\Entity\EntityInterface;

/**
 * Class SchemaResolver
 *
 * Version: 0.2.0
 *
 * Resolves which schema applies to a given entity.
 */
class SchemaResolver {

  /**
   * Resolve schema ID for an entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @return string|null
   */
  public function getSchemaId(EntityInterface $entity) {

    // --------------------------------------------------
    // Default behavior: use bundle
    // --------------------------------------------------
    $bundle = $entity->bundle();

    if (!empty($bundle)) {
      return $bundle;
    }

    // --------------------------------------------------
    // Fallback: entity type ID (rare edge case)
    // --------------------------------------------------
    $type = $entity->getEntityTypeId();

    if (!empty($type)) {
      \Drupal::logger('wbmg_schema')->warning(
        'Falling back to entity type "@type" for schema resolution.',
        ['@type' => $type]
      );
      return $type;
    }

    // --------------------------------------------------
    // Final fallback: no schema
    // --------------------------------------------------
    \Drupal::logger('wbmg_schema')->error(
      'Unable to resolve schema for entity of class @class.',
      ['@class' => get_class($entity)]
    );

    return NULL;
  }

}