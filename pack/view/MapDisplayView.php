<?php
pload('app.AppView');

/**
 * ApiDisplayView View
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package app.view
 * @since 1.0
 */
class MapDisplayView extends AppView {
    
    protected function create(){
        $this->define('rootUrl', $this->service('config.app')->get('app', 'rootUrl'));
        $this->define('apiKey', $this->service('config.app')->get('app', 'mapApiKey'));
    }
    
}