<?php
pload('app.AppController');
pload('packfire.response.pJsonResponse');
pload('view.MapDisplayView');

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
        $this->render(new MapDisplayView());
    }
    
    function polling(){
        session_write_close();
        
        $lastPoint = $this->params->get('lastPoint');
        
        $result = array();
        $timeout = 2000;
        $checkInterval = 500;
        while($timeout > 0 && empty($result)){
            
            $result = $this->service('database')
                ->from('coordinates')
                ->where('CoordinateId > :lastPoint')
                ->orderBy('Updated')
                ->param('lastPoint', $lastPoint)
                ->select('CoordinateId', 'Latitude', 'Longitude', 'DataSetId')
                ->map(function($row){
                    return array(
                        'coordinateId' => $row[0],
                        'latitude' => $row[1],
                        'longitude' => $row[2],
                        'dataset' => $row[3]
                    );
                })
                ->fetch()->toArray();
            
            if(empty($result)){
                usleep($checkInterval * 1000);
                $timeout -= $checkInterval;
            }
        }
        
        return new pJsonResponse($result);
    }
    
}