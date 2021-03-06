<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Cmf
 * @package    Jquery
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-04-27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiJquery;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Sfynx\ToolBundle\Twig\Extension\PiJqueryExtension;

/**
 * Widget Admin Jquery UI plugin
 *
 * @subpackage   Cmf
 * @package    Jquery
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiWidgetAdminManager extends PiJqueryExtension
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
     * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    protected function init($options = null)
    {
        // template management
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addJsFile("bundles/sfynxtemplate/js/jquery/jquery.tmpl.min.js");
        // viewer management
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addJsFile("bundles/sfynxtemplate/js/viewer/js/jquery.mousewheel.min.js");
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addJsFile("bundles/sfynxtemplate/js/viewer/js/jquery.iviewer.js");
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addCssFile("bundles/sfynxtemplate/js/viewer/css/jquery.iviewer.css");
        // Dialog extend for dialog ui
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addJsFile("bundles/sfynxtemplate/js/ui/dialogextend/build/jquery.dialogextend.min.js");
    }

    /**
      * Set progress text for Progress flash dialog.
      *
      * @param    $options    tableau d'options.
      * @access protected
      * @return void
      *
      * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
      * @author (c) Pedro Felix <contactpfelix@gmail.com>
      */
    protected function render($options = null)
    {
        // We open the buffer.
        ob_start ();
        ?>
                jQuery(document).ready(function() {

                    // we add the admin block Template to all widget
                    $("sfynx[id^='block__']").each(function(index) {
                        id_block = $(this).data("id");

                        var movies = [
                                      { id: id_block},
                                    ];

                        /* Render the adminblockTemplate with the "movies" data */
                        $("#adminblockTemplate").tmpl( movies ).prependTo(this);
                        /* Allow to draggable the block */
                        $(this).attr('data-drag', 'dragmap_block');
                    });
                    // we add the admin widget Template to all widget
                    $("sfynx[id^='widget__']").each(function(index) {
                        id_widget = $(this).data("id");
                        var movies = [
                                      { id: id_widget},
                                    ];
                        /* Render the adminwidgetTemplate with the "movies" data */
                        $("#adminwidgetTemplate").tmpl( movies ).prependTo(this);
                        /* Allow to sortable the block */
                        $(this).attr('data-drag', 'dragmap_widget');
                    });

                    /********************************
                     * start page action with click
                     ********************************/
                    $("span[class^='page_action_']").click( function() {
                        var _class    = $(this).attr('class');
                        var height = jQuery(window).height();

                        if (_class == "page_action_archivage"){
                            // start ajax
                            $.ajax({
                                url: "<?php echo $this->container->get('router')->generate('public_indexation_page', array('action'=>'archiving')); ?>",
                                data: "",
                                datatype: "json",
                                cache: false,
                                "beforeSend": function ( xhr ) {
                                    //xhr.overrideMimeType("text/plain; charset=x-user-defined");
                                },
                                "statusCode": {
                                    404: function() {
                                    }
                                }
                            }).done(function ( response ) {
                                //$('#page-action-dialog').html(response);
                                $('#page-action-dialog').html("<?php echo $this->translator->trans("pi.page.indexation.success"); ?>");
                                $('#page-action-dialog').attr('title', '<?php echo $this->translator->trans("pi.contextmenu.page.indexation"); ?>');
                                $('#page-action-dialog').dialog({
                                      height: 180,
                                      width: 400,
                                        open: function () {
                                       },
                                       beforeClose: function () {
                                           $('#page-action-dialog').html(' ');
                                       },
                                      buttons: {
                                          Ok: function () {
                                              $(this).dialog("close");
                                          }
                                      },
                                    show: 'scale',
                                    hide: 'scale',
                                    collapsingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                    expandingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                }).dialogExtend({
                                    "closable" : true,
                                    "maximizable" : true,
                                    "minimizable" : true,
                                    "collapsable" : true,
                                    "dblclick" : "collapse",
                                    "titlebar" : "transparent",
                                    "minimizeLocation" : "right",
                                    "icons" : {
                                      "close" : "ui-icon-circle-close",
                                      "maximize" : "ui-icon-circle-plus",
                                      "minimize" : "ui-icon-circle-minus",
                                      "collapse" : "ui-icon-triangle-1-s",
                                      "restore" : "ui-icon-bullet"
                                    },
                                });
                             });
                            // end ajax
                        }

                        if (_class == "page_action_desarchivage"){
                            // start ajax
                            $.ajax({
                                url: "<?php echo $this->container->get('router')->generate('public_indexation_page', array('action'=>'delete')); ?>",
                                data: "",
                                datatype: "json",
                                cache: false,
                                "beforeSend": function ( xhr ) {
                                    //xhr.overrideMimeType("text/plain; charset=x-user-defined");
                                },
                                "statusCode": {
                                    404: function() {
                                    }
                                }
                            }).done(function ( response ) {
                                $('#page-action-dialog').html("<?php echo $this->translator->trans("pi.page.indexation.delete.success"); ?>");
                                $('#page-action-dialog').attr('title', '<?php echo $this->translator->trans("pi.contextmenu.page.desindexation"); ?>');
                                $('#page-action-dialog').dialog({
                                      height: 180,
                                      width: 400,
                                        open: function () {
                                       },
                                       beforeClose: function () {
                                           $('#page-action-dialog').html(' ');
                                       },
                                      buttons: {
                                          Ok: function () {
                                              $(this).dialog("close");
                                          }
                                      },
                                    show: 'scale',
                                    hide: 'scale',
                                    collapsingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                    expandingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                }).dialogExtend({
                                    "closable" : true,
                                    "maximizable" : true,
                                    "minimizable" : true,
                                    "collapsable" : true,
                                    "dblclick" : "collapse",
                                    "titlebar" : "transparent",
                                    "minimizeLocation" : "right",
                                    "icons" : {
                                      "close" : "ui-icon-circle-close",
                                      "maximize" : "ui-icon-circle-plus",
                                      "minimize" : "ui-icon-circle-minus",
                                      "collapse" : "ui-icon-triangle-1-s",
                                      "restore" : "ui-icon-bullet"
                                    },
                                });
                             });
                            // end ajax
                        }

                        if (_class == "page_action_edit"){
                            // start ajax
                            $.ajax({
                                url: "<?php echo $this->container->get('router')->generate('public_urlmanagement_page'); ?>",
                                data: "type=page&action=edit&routename=<?php echo $this->container->get('request_stack')->getCurrentRequest()->get('_route'); ?>",
                                datatype: "json",
                                cache: false,
                                cache: false,
                                "beforeSend": function ( xhr ) {
                                    //xhr.overrideMimeType("text/plain; charset=x-user-defined");
                                },
                                "statusCode": {
                                    404: function() {
                                    }
                                }
                            }).done(function ( response ) {
                                var url = response[0].url;
                                $("#page-action-dialog").html('<iframe id="modalIframeId" width="100%" height="99%" style="overflow-x: hidden; overflow-y: auto" marginWidth="0" marginHeight="0" frameBorder="0" src="'+url+'" />').dialog({
                                     width: 840,
                                     height: height/1.5,
                                     open: function () {
                                         $(this).attr('title', '<?php echo $this->translator->trans("pi.page.update"); ?>');
                                     },
                                     beforeClose: function () {
                                         window.location.href= "<?php echo $this->container->get('router')->generate('public_refresh_page') ?>";
                                     },
                                     show: 'scale',
                                     hide: 'scale',
                                     collapsingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                     expandingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                }).dialogExtend({
                                    "closable" : true,
                                    "maximizable" : true,
                                    "minimizable" : true,
                                    "collapsable" : true,
                                    "dblclick" : "collapse",
                                    "titlebar" : "transparent",
                                    "minimizeLocation" : "right",
                                    "icons" : {
                                      "close" : "ui-icon-circle-close",
                                      "maximize" : "ui-icon-circle-plus",
                                      "minimize" : "ui-icon-circle-minus",
                                      "collapse" : "ui-icon-triangle-1-s",
                                      "restore" : "ui-icon-bullet"
                                    },
                                });
                             });
                            // end ajax
                        }

                        if (_class == "page_action_new"){
                            // start ajax
                            $.ajax({
                                url: "<?php echo $this->container->get('router')->generate('public_urlmanagement_page') ?>",
                                data: "type=page&action=new",
                                datatype: "json",
                                cache: false,
                                "beforeSend": function ( xhr ) {
                                    //xhr.overrideMimeType("text/plain; charset=x-user-defined");
                                },
                                "statusCode": {
                                    404: function() {
                                    }
                                }
                            }).done(function ( response ) {
                                var url = response[0].url;
                                $("#page-action-dialog").html('<iframe id="modalIframeId" width="100%" height="99%" style="overflow-x: hidden; overflow-y: auto" marginWidth="0" marginHeight="0" frameBorder="0" src="'+url+'" />').dialog({
                                     width: 840,
                                     height: height/1.5,
                                     open: function () {
                                         $(this).attr('title', '<?php echo $this->translator->trans("pi.page.create"); ?>');
                                     },
                                     beforeClose: function () {
                                         var routename = $(this).find('iframe').contents().find("#piapp_adminbundle_pagetype_route_name").val();
                                         //console.log(routename);
                                         $.ajax({
                                          url: "<?php echo $this->container->get('router')->generate('public_urlmanagement_page') ?>",
                                          data: "type=routename" + "&routename=" + routename + "&action=url",
                                          datatype: "json",
                                          cache: false,
                                          error: function(msg){ alert( "Error !: " + msg );},
                                          success: function(response){
                                              var url = response[0].url;
                                              window.location.href= url;
                                          }
                                      });
                                      // end ajax
                                     },
                                     show: 'scale',
                                     hide: 'scale',
                                     collapsingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                     expandingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                }).dialogExtend({
                                    "closable" : true,
                                    "maximizable" : true,
                                    "minimizable" : true,
                                    "collapsable" : true,
                                    "dblclick" : "collapse",
                                    "titlebar" : "transparent",
                                    "minimizeLocation" : "right",
                                    "icons" : {
                                      "close" : "ui-icon-circle-close",
                                      "maximize" : "ui-icon-circle-plus",
                                      "minimize" : "ui-icon-circle-minus",
                                      "collapse" : "ui-icon-triangle-1-s",
                                      "restore" : "ui-icon-bullet"
                                    },
                                });
                             });
                            // end ajax
                        }

                    });
                    // end click

                    /********************************
                     * start block action with click
                     ********************************/
                    $("a[class^='block_action_']").click( function() {
                        var id     = $(this).data('id');
                        var action = $(this).data('action');
                        var title  = $(this).attr('title');
                        var _class = $(this).attr('class');
                        var height = jQuery(window).height();
                        // start ajax
                        $.ajax({
                            url: "<?php echo $this->container->get('router')->generate('public_urlmanagement_page') ?>",
                            data: "id=" + id + "&action=" + action + "&type=block",
                            datatype: "json",
                            cache: false,
                            "beforeSend": function ( xhr ) {
                                //xhr.overrideMimeType("text/plain; charset=x-user-defined");
                            },
                            "statusCode": {
                                404: function() {
                                }
                            }
                        }).done(function ( response ) {
                            var url = response[0].url;
                            $("#block-action-dialog").html('<iframe id="modalIframeId" width="100%" height="99%" style="overflow-x: hidden; overflow-y: auto" marginWidth="0" marginHeight="0" frameBorder="0" src="'+url+'" />').dialog({
                                 width: 840,
                                 height: height/1.5,
                                 open: function () {
                                     $(this).attr('title', '<?php echo $this->translator->trans('pi.form'); ?> ' + title);
                                 },
                                 beforeClose: function () {
                                     window.location.href= "<?php echo $this->container->get('router')->generate('public_refresh_page') ?>";
                                 },
                                 show: 'scale',
                                 hide: 'scale',
                                 collapsingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                 expandingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                             }).dialogExtend({
                                 "closable" : true,
                                 "maximizable" : true,
                                 "minimizable" : true,
                                 "collapsable" : true,
                                 "dblclick" : "collapse",
                                 "titlebar" : "transparent",
                                 "minimizeLocation" : "right",
                                 "icons" : {
                                   "close" : "ui-icon-circle-close",
                                   "maximize" : "ui-icon-circle-plus",
                                   "minimize" : "ui-icon-circle-minus",
                                   "collapse" : "ui-icon-triangle-1-s",
                                   "restore" : "ui-icon-bullet"
                                 },
                               });
                         });
                        // end ajax
                    });
                    // end click

                    /********************************
                     * start widget action with click
                     ********************************/
                    $("a[class^='widget_action_']").click( function() {
                        var id         = $(this).data('id');
                        var action    = $(this).data('action');
                        var title    = $(this).attr('title');
                        var _class    = $(this).attr('class');
                        var height = jQuery(window).height();
                        // start ajax
                        $.ajax({
                            url: "<?php echo $this->container->get('router')->generate('public_urlmanagement_page') ?>",
                            data: "id=" + id + "&action=" + action + "&type=widget",
                            datatype: "json",
                            cache: false,
                            "beforeSend": function ( xhr ) {
                                //xhr.overrideMimeType("text/plain; charset=x-user-defined");
                            },
                            "statusCode": {
                                404: function() {
                                }
                            }
                        }).done(function ( response ) {
                            var url = response[0].url;
                            if ( (_class == "widget_action_delete") || (_class== "widget_action_move_up") || (_class== "widget_action_move_down") ){
                                $('#widget-action-dialog').dialog({
                                    height: 180,
                                    width: 400,
                                    open: function () {
                                        $(this).attr('title', '<?php echo $this->translator->trans('pi.form'); ?> ' + title);
                                        if (_class == "widget_action_delete")
                                            $(this).html('<?php echo $this->translator->trans('pi.widget.ajaxaction.widget_action_delete'); ?>');
                                        if (_class == "widget_action_move_up")
                                            $(this).html('<?php echo $this->translator->trans('pi.widget.ajaxaction.widget_action_move_up'); ?>');
                                        if (_class == "widget_action_move_down")
                                            $(this).html('<?php echo $this->translator->trans('pi.widget.ajaxaction.widget_action_move_down'); ?>');

                                        $(this).find('iframe').attr('style', 'width: 100%;height: 100%');
                                    },
                                    buttons: {
                                        Cancel: function () {
                                            $(this).dialog("close");
                                        },
                                        Ok: function () {
                                            $.ajax({
                                                url: url,
                                                data:"",
                                                datatype: "json",
                                                cache: false,
                                                error: function(msg){ alert( "Error !: " + msg );},
                                                success: function(response){
                                                    window.location.href= "<?php echo $this->container->get('router')->generate('public_refresh_page') ?>";
                                                }
                                            });
                                        }
                                    },
                                    show: 'scale',
                                    hide: 'scale',
                                    collapsingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                    expandingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                }).dialogExtend({
                                    "closable" : true,
                                    "maximizable" : true,
                                    "minimizable" : true,
                                    "collapsable" : true,
                                    "dblclick" : "collapse",
                                    "titlebar" : "transparent",
                                    "minimizeLocation" : "right",
                                    "icons" : {
                                      "close" : "ui-icon-circle-close",
                                      "maximize" : "ui-icon-circle-plus",
                                      "minimize" : "ui-icon-circle-minus",
                                      "collapse" : "ui-icon-triangle-1-s",
                                      "restore" : "ui-icon-bullet"
                                    },
                                });
                            } else {
                                $('#widget-action-dialog').html('<iframe id="modalIframeId" width="100%" height="99%" style="overflow-x: hidden; overflow-y: auto" marginWidth="0" marginHeight="0" frameBorder="0" src="'+url+'" />').dialog({
                                    width: 971,
                                    height: height/1.5,
                                    overlay: {
                                        backgroundColor: '#000',
                                        opacity: 0.5
                                    },
                                    open: function () {
                                        $(this).attr('title', 'Formulaire ' + title);
                                    },
                                    beforeClose: function () {
                                        window.location.href= "<?php echo $this->container->get('router')->generate('public_refresh_page') ?>";
                                    },
                                    show: 'scale',
                                    hide: 'scale',
                                    collapsingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                    expandingAnimation: { animated: "scale", duration: 1000000, easing: "easeOutExpo" },
                                }).dialogExtend({
                                    "closable" : true,
                                    "maximizable" : true,
                                    "minimizable" : true,
                                    "collapsable" : true,
                                    "dblclick" : "collapse",
                                    "titlebar" : "transparent",
                                    "minimizeLocation" : "right",
                                    "icons" : {
                                      "close" : "ui-icon-circle-close",
                                      "maximize" : "ui-icon-circle-plus",
                                      "minimize" : "ui-icon-circle-minus",
                                      "collapse" : "ui-icon-triangle-1-s",
                                      "restore" : "ui-icon-bullet"
                                    },
                                });
                            }
                         });
                        // end ajax
                    });
                    // end click

                    $(".img_action_viewer").click( function() {
                        $("img").each(function(index) {
                            var scr = $(this).attr("src");
                            var height = $(this).height();
                            var width  = $(this).width();
                            if ($("#viewer_"+index).is(':visible')){
                                $(this).show();
                                $("#viewer_"+index).remove();
                            } else {
                                $(this).before('<div id="viewer_'+index+'" class="viewer" style="height:'+height+'px;width:'+width+'px" ></div>');
                                $(this).hide();
                                $("#viewer_"+index).iviewer({
                                   src: scr,
                                   update_on_resize: true,
                                   zoom_animation: true,
                                   onMouseMove: function(ev, coords) { },
                                   onStartDrag: function(ev, coords) { return true; }, //this image will be dragged
                                   onDrag: function(ev, coords) { }
                               });
                           }
                        });
                    });

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
            <div id="page-action-dialog" style="display:none"></div>
            <div id="block-action-dialog" style="display:none"></div>
            <div id="widget-action-dialog" style="display:none"></div>

            <script id="adminblockTemplate" type="text/x-jquery-tmpl">
            <h5 class='block_action_menu' >
                <a href='#' class='block_action_admin'    data-action='admin'        data-id='${id}' title='<?php echo $this->translator->trans("pi.admin"); ?>' >&nbsp;</a>
                <a href='#' class='block_action_import'    data-action='import'    data-id='${id}' title='<?php echo $this->translator->trans("pi.allwidgets"); ?>' >&nbsp;</a>
            </h5>
            </script>
            <script id="adminwidgetTemplate" type="text/x-jquery-tmpl">
            <h6 class='widget_action_menu' >&nbsp;
                <a href='#' class='widget_action_move_up'    data-action='move_up'    data-id='${id}' title='<?php echo $this->translator->trans("pi.move-up"); ?>' >&nbsp;</a>
                <a href='#' class='widget_action_move_down'    data-action='move_down'    data-id='${id}' title='<?php echo $this->translator->trans("pi.move-down"); ?>' >&nbsp;</a>
                <a href='#' class='widget_action_edit'        data-action='edit'        data-id='${id}' title='<?php echo $this->translator->trans("pi.edit"); ?>' >&nbsp;</a>
                <a href='#' class='widget_action_delete'    data-action='delete'    data-id='${id}' title='<?php echo $this->translator->trans("pi.delete"); ?>' >&nbsp;</a>
                <a href='#' class='widget_action_admin'        data-action='admin'        data-id='${id}' title='<?php echo $this->translator->trans("pi.admin"); ?>' >&nbsp;</a>
            </h6>
            </script>
            <div id="hProBar"></div>
        <?php
        // We retrieve the contents of the buffer.
        $_content_html = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();

        return  $this->renderScript($_content_js, $_content_html, 'cmf/widgetadmin/');

        return $_content;
    }
}
