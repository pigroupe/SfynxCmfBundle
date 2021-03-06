<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Cmf
 * @package    Repository
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository;

use Doctrine\ORM\EntityRepository;
use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Orm\Traits\TraitTranslation;

/**
 * Block Repository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @subpackage   Cmf
 * @package    Repository
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class HistoricalStatusRepository extends EntityRepository
{
    use TraitTranslation;
}