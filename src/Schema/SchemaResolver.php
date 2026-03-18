<?php

namespace Drupal\wbmg_schema\Schema;

use Drupal\Core\Entity\EntityInterface;

class SchemaResolver {

  protected $configFactory;

  public function __construct($config_factory) {
    $this->configFactory = $config_factory;
  }

  public function getSchemaId(EntityInterface $entity): ?string {
    $bundle = $entity->bundle();
    $type = $entity->getEntityTypeId();

    $config = $this->configFactory->get('wbmg_schema.mappings');

    return $config->get("$type.$bundle.schema");
  }

}