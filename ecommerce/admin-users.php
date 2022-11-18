<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;




//rota da tela que vai listar todos os usuários.
$app->get('/admin/users',function(){
    //precisa ta logado para verificar essa tela
    //utilizando este metodo para verificação
    User::verifyLogin();

    $users = User::listAll();

    $page = new PageAdmin();
    
    $page->setTpl("users", array(
        "users" => $users
    ));
});
//rota do create de usuários
$app->get('/admin/users/create',function(){
    //precisa ta logado para verificar essa tela
    //utilizando este metodo para verificação
    User::verifyLogin();

    $page = new PageAdmin();
    
    $page->setTpl("users-create");
});

//rota para exclusão de dados no banco de dados
$app->get("/admin/users/:iduser/delete",function($iduser){
    User::verifyLogin();

    $user = new User();
    //carregar o usuário
    $user->get((int)$iduser);

    $user->delete();

    header("Location: /admin/users");
    exit;
    
});

$app->get("/admin/users/:iduser",function($iduser){
    //o valor do parametro vai para :iduser
    //precisa ta logado para verificar essa tela
    //utilizando este metodo para verificação
    User::verifyLogin();

    $user = new User;

    $user->get((int)$iduser);

    $page = new PageAdmin();
    
    $page->setTpl("users-update", array(
        "user" =>$user->getValues()
    ));
});
//rota para salvar no banco de dados
$app->post("/admin/users/create",function(){
    User::verifyLogin();

    $user = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

    $user->setData($_POST);

    $user->save();

    header("Location: /admin/users");

    exit;

});
//rota para edição de dados no banco de dados
$app->post("/admin/users/:iduser",function($iduser){
    User::verifyLogin();

    $user = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

    $user->get((int)$iduser);

    $user->setData($_POST);

    $user->update();

    header("Location: /admin/users");

    exit;
    
});









?>