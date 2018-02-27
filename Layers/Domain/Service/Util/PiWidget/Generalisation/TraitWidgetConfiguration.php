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
namespace Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Generalisation;

trait TraitWidgetConfiguration {

    /**
     * Return list of available widget plugins.
     *
     * @return array
     * @access public
     * @static
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2011-12-28
     */
    public static function getDefaultConfigXml()
    {
        $source  =  "<?xml version=\"1.0\"?>\n";
        $source .=  "<config>\n";
        $source .=  "    <widgets>\n";

        /////////// CACHED
        $source .=  "        <cachable>true</cachable>\n";

        /////////// CSS/JS APPLYED
        $source .=  "        <css>bundles/sfynxtemplate/css/default1.css</css>\n";
        $source .=  "        <css>bundles/sfynxtemplate/css/default2.css</css>\n";
        $source .=  "        <js>bundles/sfynxtemplate/css/default1.js</js>\n";
        $source .=  "        <js>bundles/sfynxtemplate/css/default2.js</js>\n";

        /////////// USER WIDGET
        $source .=  "        <user>\n";
        $source .=  "            <controller>SfynxAuthBundle:User:_connexion_default</controller>\n";
        $source .=  "            <params>\n";
        $source .=  "                <template>SfynxAuthBundle:Security:login.html.twig</template>\n";
        $source .=  "                <referer_redirection>true</referer_redirection>\n";
        $source .=  "            </params>\n";
        $source .=  "        </user>\n";

        /////////// CONTENT WIDGET
        $source .=  "        <content>\n";
        // snippet parameters
        $source .=  "            <id></id>\n";
        $source .=  "            <snippet>false</snippet>\n";
        // jquery extenstion parameters
        $source .=  "            <controller>TWITTER:tweets_blog</controller>\n";
        $source .=  "            <params>\n";
        $source .=  "                <template></template>\n";
        $source .=  "                <enabledonly>true</enabledonly>\n";
        $source .=  "            </params>\n";
        // media parameters
        $source .=  "            <media>\n";
        $source .=  "                <format>default_small</format>\n";
        $source .=  "                <align>right</align>\n";
        $source .=  "                <class>maclass</class>\n";
        $source .=  "                <link>MonImage</link>\n";
        $source .=  "            </media>\n";
        $source .=  "        </content>\n";

        /////////// SEARCH WIDGET
        $source .=  "        <search>\n";
        $source .=  "            <controller>LUCENE:search-lucene</controller>\n";
        $source .=  "            <params>\n";
        $source .=  "                <template>searchlucene-result.html.twig</template>\n";
        $source .=  "                <MaxResults></MaxResults>\n";
        // lucene parameters
        $source .=  "                <lucene>\n";
        $source .=  "                    <action>renderDefault</action>\n";
        $source .=  "                    <menu>searchlucene</menu>\n";
        $source .=  "                    <searchBool>true</searchBool>\n";
        $source .=  "                    <searchBoolType>AND</searchBoolType>\n";
        $source .=  "                    <searchByMotif>true</searchByMotif>\n";
        $source .=  "                    <setMinPrefixLength>0</setMinPrefixLength>\n";
        $source .=  "                    <getResultSetLimit>0</getResultSetLimit>\n";
        $source .=  "                    <searchFields>\n";
        $source .=  "                        <sortField>Contents</sortField>\n";
        $source .=  "                        <sortType>SORT_STRING</sortType>\n";
        $source .=  "                        <sortOrder>SORT_ASC</sortOrder>\n";
        $source .=  "                    </searchFields>\n";
        $source .=  "                    <searchFields>\n";
        $source .=  "                        <sortField>Key</sortField>\n";
        $source .=  "                        <sortType>SORT_NUMERIC</sortType>\n";
        $source .=  "                        <sortOrder>SORT_DESC</sortOrder>\n";
        $source .=  "                    </searchFields>\n";
        $source .=  "                </lucene>\n";
        $source .=  "            </params>\n";
        $source .=  "        </search>\n";

        /////////// GEDMO WIDGET
        $source .=  "        <gedmo>\n";
        // snippet parameters
        $source .=  "            <id></id>\n";
        $source .=  "            <snippet>false</snippet>\n";
        // navigation and organigram and slider common parameters
        $source .=  "            <controller>PiAppGedmoBundle:Activity:_template_list</controller>\n";
        $source .=  "            <params>\n";
        $source .=  "                <node></node>\n";
        $source .=  "                <enabledonly>true</enabledonly>\n";
        $source .=  "                <category></category>\n";
        $source .=  "                <template></template>\n";
        // navigation parameters
        $source .=  "                <navigation>\n";
        $source .=  "                    <query_function>getAllTree</query_function>\n";
        $source .=  "                    <searchFields>\n";
        $source .=  "                          <nameField>field1</nameField>\n";
        $source .=  "                          <valueField>value1</valueField>\n";
        $source .=  "                    </searchFields>\n";
        $source .=  "                    <searchFields>\n";
        $source .=  "                          <nameField>field2</nameField>\n";
        $source .=  "                          <valueField>value2</valueField>\n";
        $source .=  "                    </searchFields>\n";
        $source .=  "                    <separatorClass>separateur</separatorClass>\n";
        $source .=  "                    <separatorText><![CDATA[ &ndash; ]]></separatorText>\n";
        $source .=  "                    <separatorFirst>false</separatorFirst>\n";
        $source .=  "                    <separatorLast>false</separatorLast>\n";
        $source .=  "                    <ulClass>infoCaption</ulClass>\n";
        $source .=  "                    <liClass>menuContainer</liClass>\n";
        $source .=  "                    <counter>true</counter>\n";
        $source .=  "                    <routeActifMenu>\n";
        $source .=  "                        <liActiveClass></liActiveClass>\n";
        $source .=  "                        <liInactiveClass></liInactiveClass>\n";
        $source .=  "                        <aActiveClass></aActiveClass>\n";
        $source .=  "                        <aInactiveClass></aInactiveClass>\n";
        $source .=  "                        <enabledonly>true</enabledonly>\n";
        $source .=  "                    </routeActifMenu>\n";
        $source .=  "                    <lvlActifMenu>\n";
        $source .=  "                        <liActiveClass></liActiveClass>\n";
        $source .=  "                        <liInactiveClass></liInactiveClass>\n";
        $source .=  "                        <aActiveClass></aActiveClass>\n";
        $source .=  "                        <aInactiveClass></aInactiveClass>\n";
        $source .=  "                        <enabledonly>true</enabledonly>\n";
        $source .=  "                    </lvlActifMenu>\n";
        $source .=  "                </navigation>\n";
        // organigram parameters
        $source .=  "                <organigram>\n";
        $source .=  "                    <query_function>getAllTree</query_function>\n";
        $source .=  "                    <searchFields>\n";
        $source .=  "                          <nameField>field1</nameField>\n";
        $source .=  "                          <valueField>value1</valueField>\n";
        $source .=  "                    </searchFields>\n";
        $source .=  "                    <searchFields>\n";
        $source .=  "                          <nameField>field2</nameField>\n";
        $source .=  "                          <valueField>value2</valueField>\n";
        $source .=  "                    </searchFields>\n";
        $source .=  "                    <params>\n";
        $source .=  "                        <action>renderDefault</action>\n";
        $source .=  "                        <menu>organigram</menu>\n";
        $source .=  "                        <id>orga</id>\n";
        $source .=  "                    </params>\n";
        $source .=  "                    <fields>\n";
        $source .=  "                        <field>\n";
        $source .=  "                            <content>title</content>\n";
        $source .=  "                            <class>pi_tree_desc</class>\n";
        $source .=  "                        </field>\n";
        $source .=  "                        <field>\n";
        $source .=  "                            <content>descriptif</content>\n";
        $source .=  "                        </field>\n";
        $source .=  "                    </fields>\n";
        $source .=  "                </organigram>\n";
        // slider parameters
        $source .=  "                <slider>\n";
        $source .=  "                    <query_function>getAllAdherents</query_function>\n";
        $source .=  "                    <searchFields>\n";
        $source .=  "                          <nameField>field1</nameField>\n";
        $source .=  "                          <valueField>value1</valueField>\n";
        $source .=  "                    </searchFields>\n";
        $source .=  "                    <searchFields>\n";
        $source .=  "                          <nameField>field2</nameField>\n";
        $source .=  "                          <valueField>value2</valueField>\n";
        $source .=  "                    </searchFields>\n";
        $source .=  "                    <orderby_date></orderby_date>\n";
        $source .=  "                    <orderby_position>ASC</orderby_position>\n";
        $source .=  "                    <MaxResults>4</MaxResults>\n";
        $source .=  "                    <action>renderDefault</action>\n";
        $source .=  "                    <menu>entity</menu>\n";
        $source .=  "                    <id>flexslider</id>\n";
        $source .=  "                    <boucle_array>false</boucle_array>\n";
        $source .=  "                    <params>\n";
        $source .=  "                        <animation>slide</animation>\n";
        $source .=  "                        <direction>horizontal</slideDirection>\n";
        $source .=  "                        <slideshow>true</slideshow>\n";
        $source .=  "                        <redirection>false</redirection>\n";
        $source .=  "                        <startAt>0</slideToStart>\n";
        //$source .=  "                        <easing>swing</easing>\n";
        $source .=  "                        <slideshowSpeed>6000</slideshowSpeed>\n";
        $source .=  "                        <animationSpeed>800</animationDuration>\n";
        $source .=  "                        <directionNav>true</directionNav>\n";
        $source .=  "                        <pauseOnAction>false</pauseOnAction>\n";
        $source .=  "                        <pauseOnHover>true</pauseOnHover>\n";
        $source .=  "                        <pausePlay>true</pausePlay>\n";
        $source .=  "                        <controlNav>true</controlNav>\n";
        $source .=  "                        <minItems>1</minItems>\n";
        $source .=  "                        <maxItems>1</maxItems>\n";
        $source .=  "                    </params>\n";
        $source .=  "                </slider>\n";
        $source .=  "            </params>\n";
        $source .=  "        </gedmo>\n";

        $source .=  "    </widgets>\n";
        $source .=  "    <advanced>\n";
        $source .=  "        <roles>\n";
        $source .=  "            <role>ROLE_VISITOR</role>\n";
        $source .=  "            <role>ROLE_USER</role>\n";
        $source .=  "            <role>ROLE_ADMIN</role>\n";
        $source .=  "            <role>ROLE_SUPER_ADMIN</role>\n";
        $source .=  "        </roles>\n";
        $source .=  "    </advanced>\n";
        $source .=  "</config>\n";

        return $source;
    }
}