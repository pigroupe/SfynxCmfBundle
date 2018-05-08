<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Cmf
 * @package    Jquery
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-29
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiJquery;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Sfynx\ToolBundle\Twig\Extension\PiJqueryExtension;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ExtensionException;

/**
 * Tab jquery ui
 *
 * @subpackage   Cmf
 * @package    Jquery
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiTabManager extends PiJqueryExtension
{
    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     * @param TranslatorInterface $translator The service translator
     */
    public function __construct(ContainerInterface $container, TranslatorInterface $translator)
    {
        parent::__construct($container, $translator);
    }

    /**
     * Sets init.
     *
     * @access protected
     * @return void
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    protected function init($options = null) {
    }

    /**
      * Set wizard onglet.
      *
      * @param    $options    tableau d'options.
      * @access protected
      * @return void
      *
      * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
      */
    protected function render($options = null)
    {
        // Options management
        if (!isset($options['id']) || empty($options['id']))
            throw ExtensionException::optionValueNotSpecified('id', __CLASS__);
        if ( !isset($options['tabs-urls']) || empty($options['tabs-urls']) || !is_array($options['tabs-urls']) )
            throw ExtensionException::optionValueNotSpecified('tabs-urls', __CLASS__);

        $all_url     = null;
        $all_title    = null;
        foreach($options['tabs-urls'] as $key => $infos){

            if (!isset($infos['title']) || empty($infos['title']))
                throw ExtensionException::optionValueNotSpecified('title', __CLASS__);
            if (!isset($infos['route_name']) || empty($infos['route_name']))
                throw ExtensionException::optionValueNotSpecified('route_name', __CLASS__);
            if (!isset($infos['params']) || empty($infos['params']))
                throw ExtensionException::optionValueNotSpecified('params', __CLASS__);

            $all[$key]['url']    = $this->container->get('sfynx.tool.route.factory')->generate($infos['route_name'], $infos['params'] );
            $all[$key]['title']    = $infos['title'];
        }

        // We open the buffer.
        ob_start ();
        ?>
                $(document).ready(function () {
                        var tab_obj = $("#<?php echo $options['id']; ?>").tabs({
                        });

                        <?php if ($options['vertical'] == true) : ?>
                        tab_obj.addClass( "ui-tabs-vertical ui-helper-clearfix" );
                        $( "#<?php echo $options['id']; ?> li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
                        <?php endif ?>
                });
        <?php
        // We retrieve the contents of the buffer.
        $_content_js = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();

        // We open the buffer.
        ob_start ();
        ?>
            <div id="<?php echo $options['id']; ?>">
                <ul>
                    <?php foreach($all as $key => $info) : ?>
                        <li><a href="<?php echo $info['url']; ?>"><?php echo $this->translator->trans($info['title']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php
        // We retrieve the contents of the buffer.
        $_content_html = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();

        return  $this->renderScript($_content_js, $_content_html, 'cmf/tab/');
    }
}
