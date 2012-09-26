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
    
    function legend(){
        $result = $this->service('database')
            ->from('datasets')
            ->select('DataSetId', 'Title')
            ->map(function($row){
                return array(
                    'id' => $row[0],
                    'title' => $row[1]
                );
            })
            ->fetch()->toArray();
        return new pJsonResponse($result);
    }
    
    function polling($lastPoint){
        session_write_close();
        
        $autoIncrement = $lastPoint;
        $timeout = 3000;
        $checkInterval = 200;
        while($timeout > 0 && $lastPoint == $autoIncrement){
            /* @var $driver pMySqlConnector */
            $autoIncrement = $this->service('database.driver')
                    ->query('SHOW TABLE STATUS LIKE \'coordinates\'')
                    ->fetchColumn(10) - 1;
            if($lastPoint == $autoIncrement){
                usleep($checkInterval * 1000);
                $timeout -= $checkInterval;
            }
        }
        
        $result = array();
        if($lastPoint < $autoIncrement){
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
        }elseif($lastPoint > $autoIncrement){
            $result['status'] = 'reset';
        }
        return new pJsonResponse($result);
    }
    
}