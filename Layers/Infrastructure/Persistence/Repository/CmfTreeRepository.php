<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Cmf
 * @package    Repository
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-03-09
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Gedmo\Tool\Wrapper\EntityWrapper;
use Gedmo\Translatable\Mapping\Event\Adapter\ORM as TranslatableAdapterORM;
use Doctrine\DBAL\Types\Type;
use Sfynx\CoreBundle\Layers\Infrastructure\Repository\TreeRepository;

/**
 * Translation Repository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @subpackage   Cmf
 * @package    Repository
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class CmfTreeRepository extends TreeRepository
{
    /**
     * Return all routes names of all childs of a tree node.
     *
     * @param string $name
     * @param string $type        ['array', 'string']
     * @return Object
     */
    public function findChildsRouteByParentId($id, $locale, $type = 'array')
    {
        $routesnames = null;
        if (!empty($id)){
            $node   = $this->findNodeOr404($id, $locale,'object');
            $query  = $this->childrenQuery($node);
            $childs = $query->getResult();
            if ( method_exists($node, 'getPage') && ($node->getPage() InstanceOf \PiApp\AdminBundle\Entity\Page) ) {
                $routesnames[]     = $node->getPage()->getRouteName();
            }
            if (is_array($childs)){
                foreach($childs as $key => $child){
                    if (method_exists($child, 'getPage')  && ($child->getPage() instanceof \PiApp\AdminBundle\Entity\Page) ){
                        $routesnames[]  = $child->getPage()->getRouteName();
                    }
                }
            }
            if ($type == 'string'){
                if (!(null === $routesnames))
                    $routesnames = implode(':', $routesnames);
            }
        }

        return $routesnames;
    }

}
