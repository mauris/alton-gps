<?php
pload('app.AppController');
pload('packfire.database.pDbExpression');
pload('packfire.response.pJsonResponse');

/**
 * ApiController class
 * 
 * Handles interaction for API access
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
        return new pJsonResponse(array());
    }
    
    private static function randomColor($id){
            $rawR = mt_rand(0, 10) * 25.5;
            $rawG = mt_rand(0, 10) * 25.5;
            $rawB = mt_rand(0, 10) * 25.5;

            $mix = ceil(($id % 5) / 5 * 255);
            $r = ceil(($rawR + $mix) % 255);
            $g = ceil(($rawG + $mix) % 255);
            $b = ceil(($rawB + $mix) % 255);
            return str_pad(dechex(($r << 16) | ($g << 8) | $b), 6, '0', STR_PAD_LEFT);
    }
    
    function create(){
        $maxId = $this->service('database')->from('datasets')
                ->select('MAX(DataSetId)')
                ->fetch()->get(0);
        $this->service('database')->table('datasets')
                ->insert(array(
                    'Created' => new pDbExpression('NOW()'),
                    'Color' => self::randomColor($maxId)
                ));
        echo base_convert($this->service('database.driver')
                    ->lastInsertId(), 10, 36);
        exit;
    }
    
    function generateImage(){
        $id = $this->params->get('dataSet');
        $color = $this->service('database')->from('datasets')
                ->select('Color')->where('DataSetId = :id')
                ->map(function($x){return $x[0];})
                ->param('id', $id)->fetch()->get(0);
        if($color){
            $color = hexdec($color);
        }else{
            $color = 0;
        }
        $red = ($color >> 16) & 0xFF;
        $green = ($color >> 8) & 0xFF;
        $blue = $color & 0xFF; 
        $img = imagecreatetruecolor(16, 16);
        imagealphablending($img, false);
        imagesavealpha($img, true);
        $transparent = imagecolorallocatealpha( $img, 0, 0, 0, 127 ); 
        imagefill( $img, 0, 0, $transparent ); 
        $clr = imagecolorallocatealpha($img, $red, $green, $blue, 0);
        imagefilledellipse($img, 8, 8, 10, 10, $clr);
        header('Content-Type: image/png');
        imagepng($img);
        exit;
    }
    
}