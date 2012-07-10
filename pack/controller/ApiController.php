<?php
pload('app.AppController');
pload('packfire.database.pDbExpression');
pload('packfire.response.pJsonResponse');

/**
 * ApiController class
 * 
 * Handles interaction for home
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package app.controller
 * @since 1.0-sofia
 */
class ApiController extends AppController {
    
    function receive(){
        $latitude = $this->params->get('latitude');
        $longitude = $this->params->get('longitude');
        
        $sessionId = $this->service('database')
                ->from('sessions')->select('MAX(SessionId)')->fetch()->get(0);
        if($sessionId){
            $sessionId = $sessionId[0];
            $this->service('database')->table('coordinates')
                    ->insert(array(
                        'Latitude' => $latitude,
                        'Longitude' => $longitude,
                        'Updated' => new pDbExpression('NOW()'),
                        'SessionId' => $sessionId
                    ));
        }
        $this->response = new pJsonResponse(array());
    }
    
}