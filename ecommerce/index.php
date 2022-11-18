<?php 
session_start();
//composer para trazer as dependências
require_once("vendor/autoload.php");

//namespace


use \Slim\Slim;

//rotas
$app = new Slim();

$app->config('debug', true);

require_once('site.php');
require_once('admin.php');
require_once('admin-users.php');
require_once('admin-categories.php');
require_once('admin-products.php');






/*

//AGUARDANDO O A AULA SER REGRAVADA
$app->get("/admin/forgot",function(){

    $page = new PageAdmin([
        "header" => false,
        "footer" => false
    ]);
    
    $page->setTpl("forgot");


});


$app->post("/admin/forgot",function(){

    $user = User::getForgot($_POST["email"]);

});

*/
   //Continuando... Rota de templates de categoria

  

  







//o que faz rodar tudo de cima
$app->run();

 ?>