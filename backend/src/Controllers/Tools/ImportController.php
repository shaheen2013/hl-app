<?php
namespace App\Controllers\Tools;
use Interop\Container\ContainerInterface;

// Check if hotelier is logued in
include RUTA_DIR . LIB . 'logueado.php';
hotelStaffLanding();
// Template info

class ImportController
{
   protected $container;

   public function __construct(ContainerInterface $container) {
       $this->container = $container;
   }

   public function __invoke($request, $response, $args) {
        // your code
        // to access items in the container... $this->container->get('');
        $view = $this->container->get('view') ;
         //add data to the view
        $view->addData([
            'page_title' => 'lfkdsldj',
            'page_icon' => 'lfkdsjl',
            'current_page' =>'lkdslafsja',
            'current_subPage' =>'lkdslafsja',
            'url' =>'lkdslafsja'
        ]);

        return $view->render('tools::import');
   }



}

?>