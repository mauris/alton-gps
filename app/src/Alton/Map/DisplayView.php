<?php
namespace Alton\Map;

use Packfire\Application\Pack\View;

/**
 * DisplayView class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package app.view
 * @since 1.0
 */
class DisplayView extends View
{
    protected function create()
    {
        $this->define('rootUrl', $this->ioc['config.app']->get('app', 'rootUrl'));
        $this->define('apiKey', $this->ioc['config.app']->get('app', 'mapApiKey'));
    }
}
