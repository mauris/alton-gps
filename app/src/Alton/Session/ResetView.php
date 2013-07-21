<?php
namespace Alton\Session;

use Packfire\Application\Pack\View;

/**
 * ResetView class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package app.view
 * @since 1.0
 */
class ResetView extends View {
    
    protected function create(){
        $this->define('rootUrl', $this->service('config.app')->get('app', 'rootUrl'));
    }
    
}