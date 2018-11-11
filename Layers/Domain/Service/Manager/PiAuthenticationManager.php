<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Managers
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-12-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response as Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;

use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\PiAuthenticationManagerInterface;
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\Generalisation\PiCoreManager;
use Sfynx\AuthBundle\Domain\Service\User\Generalisation\Interfaces\UserManagerInterface;
use Sfynx\ToolBundle\Builder\RouteTranslatorFactoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;

/**
 * Description of the Authentication Widget manager
 *
 * @category   Sfynx\CmfBundle\Layers
 * @package    Domain
 * @subpackage Service\Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiAuthenticationManager extends PiCoreManager implements PiAuthenticationManagerInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var RouteTranslatorFactoryInterface
     */
    protected $routeFactory;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * Constructor.
     *
     * @param RequestInterface $request
     * @param ranslatorInterface $translator
     * @param RouteTranslatorFactoryInterface $routeFactory
     * @param ContainerInterface $container
     */
    public function __construct(
        RequestInterface $request,
        TranslatorInterface $translator,
        RouteTranslatorFactoryInterface $routeFactory,
        ContainerInterface $container
    ) {
        $this->request = $request;
        $this->translator = $translator;
        $this->routeFactory = $routeFactory;
        $this->session = $this->request->getSession();

        parent::__construct($request, $container);
    }

    /**
     * Call the tree render source method.
     *
     * @param string $id
     * @param string $lang
     * @param string $params
     * @return string
     * @access public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-04-19
     */
    public function renderSource($id, $lang = '', $params = null)
    {
        str_replace('~', '~', $id, $count);
        if ($count == 1) {
            list($entity, $method) = explode('~', $this->_Decode($id));
            if (!is_array($params)) {
                $params = $this->paramsDecode($params);
            } else {
                $this->recursive_map($params);
            }
            if (empty($lang)) {
                $lang   = $this->request->getLocale();
            }
            $params['locale'] = $lang;
            if ($method == "_connexion_default") {
                return $this->defaultConnexion($params);
            } elseif ($method == "_reset_default") {
                return $this->resetConnexion($params);
            }
            throw new \InvalidArgumentException("you have not configure correctly the attibute id");
        }
        throw new \InvalidArgumentException("you have not configure correctly the attibute id");
    }

    /**
     * Return the build tree result of a gedmo tree entity, with class options.
     *
     * @param string    $template
     * @access    public
     * @return string
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function defaultConnexion($params = null)
    {
        $template = $this->container->getParameter('sfynx.template.theme.login') . "Security/login.html.twig";
        $referer_url = "";
        $roles = "";
        $error = '';

        if (isset($params['template']) && !empty($params['template'])) {
            $template = $params['template'];
        }
        if (empty($params['locale'])) {
            $params['locale'] = $this->request->getLocale();
        }
        if (isset($params['referer_redirection']) && !empty($params['referer_redirection']) && ($params['referer_redirection'] == "true")) {
            $referer_url = $this->request->headers->get('referer');
        }
        if (isset($params['roles']) && !empty($params['roles'])) {
            $roles = $params['roles'];
        }

        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        // get the error if any (works with forward and redirect -- see below)
        if ($this->request->getAttributes()->has(Security::AUTHENTICATION_ERROR)) {
            $error = $this->request->getAttributes()->get(Security::AUTHENTICATION_ERROR);
        } elseif (null !== $this->session && $this->session->has(Security::AUTHENTICATION_ERROR)) {
            $error = $this->session->get(Security::AUTHENTICATION_ERROR);
            $this->session->remove(Security::AUTHENTICATION_ERROR);
        }
        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $this->session) ? '' : $this->session->get(Security::LAST_USERNAME);
        // we register the username in session used in dispatcherLoginFailureResponse
        $this->session->set('login-username', $lastUsername);
        // we test if the number of attempts allowed connections number with the username have been exceeded.
        if (!empty($lastUsername)) {
            $key = $this->container->get('sfynx.auth.dispatcher.login_failure.change_response')->getKeyValue();
            if ($key == "stop-client") {
                $this->session->set('login-username', '');
                $this->session->remove(Security::LAST_USERNAME);
                if ($this->request->isXmlHttpRequest()) {
                    $response = new Response(json_encode('error'));
                    $response->headers->set('Content-Type', 'application/json');

                    return $response;
                }
                $new_url = $this->container->get('router')->generate('sfynx_auth_security_login');
                $this->session->getFlashBag()->add('errorform', "you exceeded the number of attempts allowed connections!");

                return new RedirectResponse($new_url);
            }
        }

        $csrfToken = $this->container->has('security.csrf.token_manager')
            ? $this->container->get('security.csrf.token_manager')->getToken('authenticate')
            : null;

        $response =  $this->container->get('templating')->renderResponse($template, [
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token'    => $csrfToken,
            'referer_url'   => $referer_url,
            'roles'         => $roles,
            'route'         => $this->routeFactory->getMatchParamOfRoute('_route', $this->request->getLocale())
        ]);
        // we delete all permission flash
        $this->getFlashBag()->get('permission');

        return $response->getContent();
    }

    /**
     * Reset user password
     *
     * @param null|array $params
     *
     * @return Response
     */
    public function resetConnexion($params = null)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.resetting.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->container->get('sfynx.auth.manager.user');

        if (isset($params['template']) && !empty($params['template'])) {
            $template = $params['template'];
        } else {
            $template = $this->container->getParameter('sfynx.template.theme.login') . "Resetting:reset_content.html.twig";
        }
        if (isset($params['url_redirection']) && !empty($params['url_redirection'])) {
            $url_redirection = $params['url_redirection'];
        } elseif(isset($params['path_url_redirection']) && !empty($params['path_url_redirection'])) {
            $url_redirection = $this->container->get('sfynx.tool.route.factory')
                        ->generate($params['path_url_redirection'], ['locale'=> $this->request->getLocale()]);
        } else {
            $url_redirection = $this->container->get('router')->generate("home_page");
        }
        $token = $this->request->query->get('token');
        // if a user is connected, we generate automatically the token if it is not given in parameter.
        if (empty($token)
                && $this->tokenStorage->isUsernamePasswordToken()
        ) {
            $user  = $this->tokenStorage->getUser();
            $token = $this->container
                ->get('sfynx.auth.manager.user')
                ->tokenUser($user);
        } else {
            $user  = $userManager->findUserByConfirmationToken($token);
        }
        if (null === $user) {
            header('Location: '. $url_redirection);
            exit;
        }
        $form = $formFactory->createForm();
        $form->setData($user);
        if ('POST' === $this->request->getMethod()) {
            $form->bind($this->request);
            if ($form->isValid()) {
                $userManager->update($user);
                $flash = $this->translator->trans('pi.session.flash.resetting.success');
                $this->session->getFlashBag()->add('success', $flash);
                header('Location: '. $url_redirection);
                exit;
            } else {
                $flash = $this->translator->trans('pi.session.flash.resetting.error');
                $this->session->getFlashBag()->add('error', $flash);
            }
        }
        if (isset($params['clearflashes'])) {
            $this->getFlashBag()->clear();
        } else {
            $this->getFlashBag()->get('permission');
        }

        return $this->container->get('templating')->renderResponse($template, [
                'token' => $token,
                'form'  => $form->createView(),
                'route' => $this->routeFactory->getMatchParamOfRoute('_route', $this->request->getLocale())
        ])->getContent();
    }

    /**
     * Gets the flash bag.
     *
     * @return \Symfony\Component\HttpFoundation\Session\Flash\FlashBag
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function getFlashBag()
    {
        return $this->session->getFlashBag();
    }
}
