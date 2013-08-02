<?php
namespace Alton\Session;

use Packfire\Application\Pack\Controller as CoreController;

/**
 * SessionController
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package app.controler
 * @since version-created
 */
class Controller extends CoreController
{
    public function reset()
    {
        $this->render();
    }
    
    public function postReset()
    {
        $this->ioc['database.driver']->query('DELETE FROM `coordinates`');
        $this->ioc['database.driver']->query('DELETE FROM `datasets`');
        $this->ioc['database.driver']->query('ALTER TABLE `coordinates` AUTO_INCREMENT = 1');
        $this->ioc['database.driver']->query('ALTER TABLE `datasets` AUTO_INCREMENT = 1');
        $this->redirect($this->route('display'));
    }
}
