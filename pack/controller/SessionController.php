<?php
pload('app.AppController');
pload('packfire.database.pDbExpression');

/**
 * SessionController
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package app.controler
 * @since version-created
 */
class SessionController extends AppController {
    
    public function reset(){
        $this->render();
    }
    
    public function postReset(){
        $this->service('database.driver')->query('DELETE FROM `coordinates`');
        $this->service('database.driver')->query('DELETE FROM `datasets`');
        $this->service('database.driver')->query('ALTER TABLE `coordinates` AUTO_INCREMENT = 1');
        $this->service('database.driver')->query('ALTER TABLE `datasets` AUTO_INCREMENT = 1');
        $this->redirect($this->route('display'));
    }
    
}