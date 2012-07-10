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
    
    public function create(){
        $this->render();
    }
    
    public function postCreate(){
        $note = $this->params->get('note');
        $this->service('database')->table('sessions')
                ->insert(array(
                    'Note' => $note,
                    'Created' => new pDbExpression('NOW()')
                ));
        $this->redirect($this->route('display'));
    }
    
    public function reset(){
        $this->render();
    }
    
    public function postReset(){
        $this->service('database.driver')->query('DELETE FROM `coordinates`');
        $this->service('database.driver')->query('DELETE FROM `sessions`');
        $this->service('database.driver')->query('ALTER TABLE `coordinates` AUTO_INCREMENT = 1');
        $this->service('database.driver')->query('ALTER TABLE `sessions` AUTO_INCREMENT = 1');
        $this->params->add('note', 'Reset performed.');
        $this->forward($this, 'create');
    }
    
}