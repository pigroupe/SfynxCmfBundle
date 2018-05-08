<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   CMF
 * @package    DataFixtures
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-23
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Sfynx\CmfBundle\Layers\Domain\Entity\Page;
use Sfynx\CmfBundle\Layers\Infrastructure\Persistence\Repository\PageRepository;

/**
 * Page DataFixtures.
 *
 * @subpackage   CMF
 * @package    DataFixtures
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PagesFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load language fixtures
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-01-23
     */
    public function load(ObjectManager $manager)
    {
        $field1 = new Page();
        $field1->setRouteName('home_page');
        $field1->setUrl('');
        $field1->setLayout($this->getReference('layout-pi-sfynx'));
        $field1->setUser($this->getReference('user-admin'));
        $field1->setMetaContentType(PageRepository::TYPE_TEXT_HTML);
        $field1->setCacheable(false);
        $field1->setLifetime("0");
        $field1->setPublic(false);
        $field1->setEnabled(true);
        $manager->persist($field1);

        $field2 = new Page();
        $field2->setRouteName('error_404');
        $field2->setUrl('404error');
        $field2->setLayout($this->getReference('layout-pi-error'));
        $field2->setUser($this->getReference('user-admin'));
        $field2->setMetaContentType(PageRepository::TYPE_TEXT_HTML);
        $field2->setCacheable(false);
        $field2->setLifetime("86400");
        $field2->setPublic(false);
        $field2->setEnabled(true);
        $manager->persist($field2);

        $field3 = new Page();
        $field3->setRouteName('page_route_name_reset');
        $field3->setUrl('reset');
        $field3->setLayout($this->getReference('layout-pi-sfynx'));
        $field3->setUser($this->getReference('user-admin'));
        $field3->setMetaContentType(PageRepository::TYPE_TEXT_HTML);
        $field3->setCacheable(false);
        $field3->setLifetime("0");
        $field3->setPublic(false);
        $field3->setEnabled(true);
        $manager->persist($field3);

        $manager->flush();

        $this->addReference('page-homepage', $field1);
        $this->addReference('page-error', $field2);
        $this->addReference('page-reset', $field3);
    }

    /**
     * Retrieve the order number of current fixture
     *
     * @return integer
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-01-23
     */
    public function getOrder()
    {
        // The order in which fixtures will be loaded
        return 3;
    }
}
