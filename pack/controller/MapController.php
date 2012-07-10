<?php
pload('app.AppController');
pload('packfire.response.pJsonResponse');

/**
 * MapController
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package app.controler
 * @since version-created
 */
class MapController extends AppController {
    
    function display(){
        
        $sessionId = $this->service('database')
                ->from('sessions')->select('MAX(SessionId)')->fetch()->get(0);
        $this->state['sessionId'] = $sessionId;
        
        $this->render();
    }
    
    function polling(){
        session_write_close();
        
        $lastPoint = $this->params->get('lastPoint');
        $sessionId = $this->params->get('sessionId');
        
        $result = array();
        $timeout = 2000;
        $checkInterval = 500;
        while($timeout > 0 && empty($result)){
            
            $result = $this->service('database')
                ->from('coordinates')
                ->where('SessionId = :session AND CoordinateId > :lastPoint')
                ->orderBy('Updated')
                ->param('session', $sessionId)
                ->param('lastPoint', $lastPoint)
                ->select('CoordinateId', 'Latitude', 'Longitude')
                ->map(function($row){
                    return array(
                        'coordinateId' => $row[0],
                        'latitude' => $row[1],
                        'longitude' => $row[2]
                    );
                })
                ->fetch()->toArray();
            
            if(empty($result)){
                usleep($checkInterval * 1000);
                $timeout -= $checkInterval;
            }
        }
        
        $this->response = new pJsonResponse($result);
    }
    
}