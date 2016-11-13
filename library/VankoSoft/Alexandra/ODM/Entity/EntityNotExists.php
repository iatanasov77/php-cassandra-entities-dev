<?php

namespace VankoSoft\Alexandra\ODM\Entity;

/**
 * @brief	EntityNotExists class is used as a fallback entity from the all repositories.
 * @details When we needed of lazy loading of any entity , we use null for uninitialized result,
 *			concrete entity type for success finded entity and this class for entity not found.
 */
class EntityNotExists extends BaseEntity
{

}
