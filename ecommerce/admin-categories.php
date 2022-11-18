<?php

use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use Hcode\Model\Category;



$app->get("/admin/categories",function(){

    User::verifyLogin();
    
    $categories = Category::listAll();
    
    $page = new PageAdmin();
    
    $page->setTpl("categories",[
        'categories' => $categories
    ]);

});

$app->get("/admin/categories/create",function(){
    User::verifyLogin();
    
    $page = new PageAdmin();
    // o template que ja está na pasta
    $page->setTpl("categories-create");

});


$app->post("/admin/categories/create",function(){

    User::verifyLogin();
    
$category = new Category;
$category->setData($_POST);

$category->save();
//depois que salvar , vai ser enviado para pagina categories
header('Location: /admin/categories');
exit;

});

$app->get("/admin/categories/:idcategory/delete", function($idcategory){

    User::verifyLogin();
//parametro da function o id do endereço
    $category = new Category;

    $category->get((int)$idcategory);

    $category->delete();

    header('Location: /admin/categories');
    exit;

});


$app->get("/admin/categories/:idcategory", function($idcategory){

    User::verifyLogin();
    
    $category = new Category;

    $category->get((int)$idcategory);
    
    //parametro da function o id do endereço
    //aqui vai mostrar uma tela
    $page = new PageAdmin();
    // o template que ja está na pasta
    $page->setTpl("categories-update", [
        'category' =>$category->getValues()
    ]);
     
    
    });

    $app->post("/admin/categories/:idcategory", function($idcategory){

        User::verifyLogin();
            //estanciando 
        $category = new Category;
            //int converter o id se for string
        $category->get((int)$idcategory);
            //carregar os dados atuais - receber do post
        $category->setData($_POST);

        $category->save();

        header('Location: /admin/categories');
        exit;
        
        });
        
    $app->get("/categories/:idcategory",function($idcategory){

        $category = new Category();

        $category->get((int)$idcategory);

        $page = new Page();

        $page->setTpl("category", [

            'category' => $category->getValues(),
            'products' => []


        ]);
    });










?>