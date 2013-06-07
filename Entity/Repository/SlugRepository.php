<?php

/**
 * This file is part of the DinecatSmartRoutingBundle package.
 * @copyright   2013 Dinecat, http://dinecat.com/
 * @license     http://dinecat.com/en/licenses/MIT.html MIT License
 * @link        https://github.com/dinecat/SmartRoutingBundle
 */

namespace Dinecat\SmartRoutingBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Slug repository class.
 * @package     DinecatSmartRoutingBundle
 * @subpackage  Entity
 * @author      Mykola Zyk <relo.san.pub@gmail.com>
 *
 * @method  \Dinecat\SmartRoutingBundle\Entity\Slug     findOneBy(array $criteria)
 * @method  \Dinecat\SmartRoutingBundle\Entity\Slug[]   findBy(array $criteria, array $orderBy = null, $offset = null, $limit = null)
 */
class SlugRepository extends EntityRepository
{
}
