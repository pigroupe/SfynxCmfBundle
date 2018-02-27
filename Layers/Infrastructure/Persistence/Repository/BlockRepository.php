<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @package    Cmf
 * @subpackage Repository
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-01-06
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
 * @package    Cmf
 * @subpackage Repository
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-01-06
 */
class BlockRepository extends EntityRepository
{
    use TraitTranslation;
}