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
 * PiwidgetimportManager Jquery
 *
 * @subpackage   Cmf
 * @package    Jquery
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiwidgetimportManager extends PiJqueryExtension
{
    /**
     * @var array
     * @static
     */
    static $menus = array('widgetimport');

    /**
     * @var array
     * @static
    */
    static $actions = array('widgetimport');

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
     * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    protected function init($options = null)
    {
        //css
        if ( empty($options) || ($options == 'widgetimport') ) {
            $this->container->get('sfynx.tool.twig.extension.layouthead')->addCssFile("bundles/sfynxtemplate/js/iGoogle/css/widgetimport/inettuts.js.css");
            $this->container->get('sfynx.tool.twig.extension.layouthead')->addCssFile("bundles/sfynxtemplate/js/iGoogle/css/widgetimport/inettuts.css");
        }
    }

    /**
      * Set progress text for Progress flash dialog.
      *
      * @param    $options    tableau d'options.
      * @access protected
      * @return void
      *
      * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
      */
    protected function render($options = null)
    {
        // Options management
        if (!isset($options['action']) || empty($options['action']) || (isset($options['action']) && !in_array(strtolower($options['action']), self::$actions)) ) {
            throw ExtensionException::optionValueNotSpecified('action', __CLASS__);
        }
        if (!isset($options['menu']) || empty($options['menu']) || (isset($options['menu']) && !in_array(strtolower($options['menu']), self::$menus)) ) {
            throw ExtensionException::optionValueNotSpecified('menu', __CLASS__);
        }
        // we set method names
        $method = strtolower($options['menu']) . "Menu";
        $action = strtolower($options['action']) . "Action";
        // we set result
        if (method_exists($this, $method)) {
            $result = $this->$method($options);
        } else {
            throw ExtensionException::MethodUnDefined($method);
        }

        return $this->$action($result, $options);
    }

    /**
     * Default render
     *
     * @param array        $result
     * @param array        $options
     * @access private
     * @return string
     *
     * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @author (c) Pedro Felix <contactpfelix@gmail.com>
     */
    protected function widgetimportAction($result, $options = null)
    {
        // set Options
        if ( isset($options['locale']) ) {
            $this->locale    = $options['locale'];
        }
        if ( !isset($options['template']) || empty($options['template']) ) {
            throw ExtensionException::optionValueNotSpecified('template', __CLASS__);
        } else {
            $template    = $options['template'];
        }
        // a = floor(a/b)*b + a%b;
        $nb = count($result);
        $q  = floor($nb/3);
        $r  = $nb % 3;
        $number_col_1     = $r === 0 ? $q : $q +1;
        $number_col_2     = $r === 2 ? $q + 1 : $q ;
        $number_col_3     = $q;

        $array_col_1 = null;
        $array_col_2 = null;
        $array_col_3 = null;
        $i =1;
        if (!(null === $result)) {
            foreach($result as $key => $value){
                if ($i <= $number_col_1){
                    $array_col_1[$key] = $value;
                }
                if (($i > $number_col_1) && ($i <= ($number_col_1 + $number_col_2)) ){
                    $array_col_2[$key] = $value;
                }
                if ($i > ($number_col_1 + $number_col_2)){
                    $array_col_3[$key] = $value;
                }
                $i++;
            }
        }
        $response        = $this->container->get('templating')->renderResponse("SfynxTemplateBundle:Template\\Widgetimport:$template", array(
                'form_col_1'    => $array_col_1,
                'form_col_2'    => $array_col_2,
                'form_col_3'    => $array_col_3,
        ));
        // We open the buffer.
        ob_start ();
        ?>
                var PIimportwidget = {

                    jQuery : $,

                    settings : {
                        columns : '.column',
                        widgetSelector: '.widget',
                        handleSelector: '.widget-head',
                        contentSelector: '.widget-content',
                        widgetDefault : {
                            movable: true,
                            removable: true,
                            collapsible: true,
                            editable: true,
                            colorClasses : ['color-yellow', 'color-red', 'color-blue', 'color-white', 'color-orange', 'color-green']
                        },
                        widgetIndividual : {
                            intro : {
                                movable: false,
                                removable: false,
                                collapsible: false,
                                editable: false
                            }
                        }
                    },

                    init : function () {
                        this.addWidgetControls();
                        this.makeSortable();
                    },

                    getWidgetSettings : function (id) {
                        var $ = this.jQuery,
                            settings = this.settings;
                        return (id&&settings.widgetIndividual[id]) ? $.extend({},settings.widgetDefault,settings.widgetIndividual[id]) : settings.widgetDefault;
                    },

                    addWidgetControls : function () {
                        var PIimportwidget = this,
                            $ = this.jQuery,
                            settings = this.settings;

                        $(settings.widgetSelector, $(settings.columns)).each(function () {
                            var thisWidgetSettings = PIimportwidget.getWidgetSettings(this.id);
                            if (thisWidgetSettings.removable) {
                                $('<a href="#" class="remove">CLOSE</a>').mousedown(function (e) {
                                    e.stopPropagation();
                                }).click(function () {
                                    if (confirm('This widget will be removed, ok?')) {
                                        $(this).parents(settings.widgetSelector).animate({
                                            opacity: 0
                                        },function () {
                                            $(this).wrap('<div/>').parent().slideUp(function () {
                                                $(this).remove();
                                            });
                                        });
                                    }
                                    return false;
                                }).appendTo($(settings.handleSelector, this));
                            }

                            if (thisWidgetSettings.editable) {
                                $('<a href="#" class="edit">EDIT</a>').mousedown(function (e) {
                                    e.stopPropagation();
                                }).toggle(function () {
                                    $(this).css({backgroundPosition: '-66px 0', width: '55px'})
                                        .parents(settings.widgetSelector)
                                            .find('.edit-box').show().find('input').focus();
                                    return false;
                                },function () {
                                    $(this).css({backgroundPosition: '', width: ''})
                                        .parents(settings.widgetSelector)
                                            .find('.edit-box').hide();
                                    return false;
                                }).appendTo($(settings.handleSelector,this));
                            }

                            if (thisWidgetSettings.collapsible) {
                                $('<a href="#" class="collapse">COLLAPSE</a>').mousedown(function (e) {
                                    e.stopPropagation();
                                }).toggle(function () {
                                    $(this).css({backgroundPosition: '-38px 0'})
                                        .parents(settings.widgetSelector)
                                            .find(settings.contentSelector).hide();
                                    return false;
                                },function () {
                                    $(this).css({backgroundPosition: ''})
                                        .parents(settings.widgetSelector)
                                            .find(settings.contentSelector).show();
                                    return false;
                                }).prependTo($(settings.handleSelector,this));
                            }
                        });

                    },

                    makeSortable : function () {
                        var PIimportwidget = this,
                            $ = this.jQuery,
                            settings = this.settings,
                            $sortableItems = (function () {
                                var notSortable = '';
                                $(settings.widgetSelector,$(settings.columns)).each(function (i) {
                                    if (!PIimportwidget.getWidgetSettings(this.id).movable) {
                                        if (!this.id) {
                                            this.id = 'widget-no-id-' + i;
                                        }
                                        //notSortable += '#' + this.id + ',';
                                    }
                                });
                                return $('> li:not(' + notSortable + ')', settings.columns);
                            })();

                        $sortableItems.find(settings.handleSelector).css({
                            cursor: 'move'
                        }).mousedown(function (e) {
                            $sortableItems.css({width:''});
                            $(this).parent().css({
                                width: $(this).parent().width() + 'px'
                            });
                        }).mouseup(function () {
                            if (!$(this).parent().hasClass('dragging')) {
                                $(this).parent().css({width:''});
                            } else {
                                $(settings.columns).sortable('disable');
                            }
                        });

                        $(settings.columns).sortable({
                            items: $sortableItems,
                            connectWith: $(settings.columns),
                            handle: settings.handleSelector,
                            placeholder: 'widget-placeholder',
                            forcePlaceholderSize: true,
                            revert: 300,
                            delay: 100,
                            opacity: 0.8,
                            containment: 'document',
                            start: function (e,ui) {
                                $(ui.helper).addClass('dragging');
                            },
                            stop: function (e,ui) {
                                $(ui.item).css({width:''}).removeClass('dragging');
                                $(settings.columns).sortable('enable');
                            }
                        });
                    }

                };

                PIimportwidget.init();
        <?php
        // We retrieve the contents of the buffer.
        $_content_js = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();

        // we get html content
        $_content_html = $response->getContent();
        //
        if ( !isset($options['noJs']) || !($options['noJs']) ) {
            return  $this->renderScript($_content_js, $_content_html, 'cmf/widgetimport/');
        } else {
            return  $this->renderScript($_content_js, $_content_html, 'cmf/widgetimport/', 'html');
        }
    }

    /**
     * Generate all form builders.
     *
     * <code>
     *        {% set options_widgetimport = {
     *                'action':'widgetimport',
     *                'menu': 'widgetimport',
     *                'template': 'default.html.twig',
     *                'locale': app.request.locale,
     *                'noJs' : false,
     *             }
     *        %}
     *        {{ renderJquery('TOOL', 'widgetimport', options_widgetimport )|raw }}
     * </code>
     *
     * @param    array $options
     * @access public
     * @return array
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    public function widgetimportMenu($options = null)
    {
        return $this->container->get('pi_app_admin.manager.formbuilder')->executeAllFormByContainer('WIDGET');
    }
}
