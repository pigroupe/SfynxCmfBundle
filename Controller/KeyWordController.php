<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Controllers
 * @package    Controller
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sfynx\CmfBundle\Controller\CmfabstractController;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ControllerException;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Sfynx\CmfBundle\Layers\Domain\Entity\KeyWord;
use Sfynx\CmfBundle\Application\Validation\Type\KeyWordType;

/**
 * KeyWord controller.
 *
 * @subpackage   Admin_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class KeyWordController extends CmfabstractController
{
    protected $_entityName = "SfynxCmfBundle:KeyWord";

    /**
     * Lists all KeyWord entities.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function indexAction()
    {
        $em       = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('SfynxCmfBundle:KeyWord')->findAll();

        return $this->render('SfynxCmfBundle:KeyWord:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Enabled KeyWord entities.
     *
     * @Route("/admin/keyWord/enabled", name="admin_keyword_enabledentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function enabledajaxAction()
    {
        return parent::enabledajaxAction();
    }

    /**
     * Disable KeyWord  entities.
     *
     * @Route("/admin/keyWord/disable", name="admin_keyword_disablentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function disableajaxAction()
    {
        return parent::disableajaxAction();
    }

    /**
     * Finds and displays a KeyWord entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function showAction($id)
    {
        $em        = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SfynxCmfBundle:KeyWord')->find($id);

        if (!$entity) {
            throw ControllerException::NotFoundEntity('KeyWord');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SfynxCmfBundle:KeyWord:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new KeyWord entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function newAction()
    {
        $em        = $this->getDoctrine()->getManager();
        $entity = new KeyWord();
        $form   = $this->createForm(new KeyWordType($em), $entity, array('show_legend' => false));

        return $this->render('SfynxCmfBundle:KeyWord:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new KeyWord entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function createAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $entity  = new KeyWord();
        $request = $this->getRequest();
        $form    = $this->createForm(new KeyWordType($em), $entity, array('show_legend' => false));
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_keyword_show', array('id' => $entity->getId())));

        }

        return $this->render('SfynxCmfBundle:KeyWord:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing KeyWord entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function editAction($id)
    {
        $em        = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SfynxCmfBundle:KeyWord')->find($id);

        if (!$entity) {
            throw ControllerException::NotFoundEntity('KeyWord');
        }

        $editForm = $this->createForm(new KeyWordType($em), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SfynxCmfBundle:KeyWord:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing KeyWord entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function updateAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SfynxCmfBundle:KeyWord')->find($id);

        if (!$entity) {
            throw ControllerException::NotFoundEntity('KeyWord');
        }

        $editForm   = $this->createForm(new KeyWordType($em), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_keyword_edit', array('id' => $id)));
        }

        return $this->render('SfynxCmfBundle:KeyWord:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a KeyWord entity.
     *
     * @Secure(roles="ROLE_SUPER_ADMIN")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SfynxCmfBundle:KeyWord')->find($id);

            if (!$entity) {
                throw ControllerException::NotFoundEntity('KeyWord');
            }

            try {
                $em->remove($entity);
                $em->flush();
            } catch (\Exception $e) {
                $this->container->get('request_stack')->getCurrentRequest()->getSession()->getFlashBag()->clear();
                $this->container->get('request_stack')->getCurrentRequest()->getSession()->getFlashBag()->add('notice', 'pi.session.flash.wrong.undelete');
            }
        }

        return $this->redirect($this->generateUrl('admin_keyword'));
    }

    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
