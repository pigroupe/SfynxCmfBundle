<?php

namespace Sfynx\CmfBundle\Dumper;

/**
 * PhpGeneratorDumper creates a PHP class able to generate URLs for a given set of routes.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Tobias Schultze <http://tobion.de>
 */
class PhpPageDumper extends PageDumper
{
    /**
     * Dumps a set of routes to a PHP class.
     *
     * Available options:
     *
     *  * class:      The class name
     *  * base_class: The base class name
     *
     * @param array $options An array of options
     *
     * @return string A PHP class representing the generator class
     */
    public function dump(array $options = array())
    {
        return <<<EOF
<?php

/**
 * {$options['class']}
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class {$options['class']}
{
    /**
     * Constructor.
     */
    public function __construct()
    {
    }

}

EOF;
    }
}
