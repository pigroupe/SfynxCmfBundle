<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage Widget
 * @package Content
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-10
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Content;

use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation\HandlerWidgetInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ExtensionException;

/**
 * Content Widget plugin
 *
 * @subpackage Widget
 * @package Content
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class MediaHandler extends AbstractHandler implements HandlerWidgetInterface
{
    /** @var string */
    const ACTION = 'media';

    /**
     * {@inheritdoc}
     */
    public static function getAvailable()
    {
        return null;
    }

    /**
     * Sets the render of the media action.
     *
     * <code>
     *    <?xml version="1.0"?>
     *    <config>
     *        <widgets>
     *            <cachable>true</cachable>
     *            <css/>
     *            <content>
     *                <id>1</id>
     *                <media>
     *                    <format>default_small</format>
     *                    <align>right</align>
     *                    <class>maclass</class>
     *                    <link>MonImage</link>
     *                </media>
     *            </content>
     *        </widgets>
     *    </config>
     * </code>
     *
     * {@inheritdoc}
     */
    public function handler($options = null)
    {
        // if the configXml field of the widget isn't configured correctly.
        try {
            $xmlConfig    = new \Zend_Config_Xml($this->getConfigXml());
        } catch (\Exception $e) {
            return "  \n";
        }

        // if the gedmo widget is defined correctly as a "media"
        if (($this->action == "media")
            && $xmlConfig->widgets->get('content')
            && $xmlConfig->widgets->content->get('media')
        ) {
            if ( $xmlConfig->widgets->content->get('id') ) {
                $idMedia        = $xmlConfig->widgets->content->id;
                $format = "default_small"; // reference, default_small
                $align_end     = "";
                $classDiv_start = "";
                $classDiv_end   = "";

                if ($xmlConfig->widgets->content->media->get('format')) {
                    $format = $xmlConfig->widgets->content->media->format;
                }

                if (!(null === $xmlConfig->widgets->content->media->align)) {
                    if (in_array($xmlConfig->widgets->content->media->align, ['left', 'right'])) {
                        $align_start    = "<section style=' display: inline-block; margin:0; padding: 0; position: relative; float:" . $xmlConfig->widgets->content->media->align . "' > \n";
                        $align_end        = "</section> \n";
                    } elseif ($xmlConfig->widgets->content->media->align == "center") {
                        $align_start    = "<section style='clear:both; display: block; position: relative; text-align:center; margin: 0 auto;' > \n";
                        $align_end        = "</section> \n";
                    }
                }

                if (!empty($xmlConfig->widgets->content->media->class)) {
                    $classDiv_start    = "<section class='".$xmlConfig->widgets->content->media->class."' > \n";
                    $classDiv_end    = "</section> \n";
                }

                if ((null === $xmlConfig->widgets->content->media->link)) {
                    try {
                        $img_balise = $this->container->get('sonata.media.twig.extension')->media($idMedia, $format, array('alt'=>''));
                        return  $classDiv_start . $align_start . $img_balise . $align_end . $classDiv_end;
                    } catch (\Exception $e) {
                        return "the media doesn't exist";
                    }
                } elseif (!empty($xmlConfig->widgets->content->media->link)) {
                    try {
                        //$url = $this->container->get('sfynx.tool.route.factory')->generate('sonata_media_download', array('id'=>$idMedia, 'format'=>$format));
                        $url = $this->container->get('sonata.media.twig.extension')->path($idMedia, $format);
                        return  $classDiv_start . $align_start
                                . "<a href='$url' target='_blanc' title='' > \n"
                                    . $xmlConfig->widgets->content->media->link  . " \n"
                                . '</a>' . " \n"
                                . $align_end . $classDiv_end;
                    } catch (\Exception $e) {
                        return "the media doesn't exist";
                    }
                }
            }
            throw ExtensionException::optionValueNotSpecified("content", __CLASS__);
        }
        throw ExtensionException::optionValueNotSpecified("content", __CLASS__);
    }
}
