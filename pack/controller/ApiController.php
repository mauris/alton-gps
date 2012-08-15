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
        $dataSet = base_convert($this->params->get('set'), 36, 10);
        
        $this->service('database')->table('coordinates')
                ->insert(array(
                    'Latitude' => $latitude,
                    'Longitude' => $longitude,
                    'Updated' => new pDbExpression('NOW()'),
                    'DataSetId' => $dataSet
                ));
        $this->response = new pJsonResponse(array());
    }
    
    private function randomColor($id){
        $rawR = mt_rand(0, 5) * 51;
        $rawG = mt_rand(0, 5) * 51;
        $rawB = mt_rand(0, 5) * 51;
        
        $mix = ceil(($id % 5) / 5 * 255);
        $r = ceil(($rawR + $mix) / 2);
        $g = ceil(($rawG + $mix) / 2);
        $b = ceil(($rawB + $mix) / 2);
        return str_pad(dechex($r << 16 + $g << 8 + $b), 6, '0', STR_PAD_LEFT);
    }
    
    function create(){
        $maxId = $this->service('database')->from('datasets')
                ->select('MAX(DataSetId)')
                ->fetch()->get(0);
        $this->service('database')->table('datasets')
                ->insert(array(
                    'Created' => new pDbExpression('NOW()'),
                    'Color' => $this->randomColor($maxId)
                ));
        $this->response = new pJsonResponse(array(
            'id' => base_convert($this->service('database.driver')->lastInsertId(), 10, 36)
        ));
    }
    
}