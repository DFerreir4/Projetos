<?php

namespace Hcode\Model;
//usando use para pegar outro namespace
//a barra está querendo pegar da raiz


use \Hcode\DB\Sql;
use \Hcode\Model;

class Category extends Model {

   

    public static function listAll(){
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");
    }

    public function save(){

        $sql = new Sql();

        /*PROCEDURE `sp_users_save`(
        pdesperson VARCHAR(64), 
        pdeslogin VARCHAR(64), 
        pdespassword VARCHAR(256), 
        pdesemail VARCHAR(128), 
        pnrphone BIGINT, 
        pinadmin TINYINT */

       $results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(
        ":idcategory"   =>   $this->getidcategory(),
        ":descategory"    =>  $this->getdescategory()
        ));

        $this->setData($results[0]);

        Category::updateFile();

    }


    public function get($idcategory){
        $sql = new Sql();

      $results =  $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory",[
            ":idcategory" => $idcategory
        ]);

        $this->setData($results[0]);

    }

    public function delete(){
        $sql = new Sql();

        $sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory",[
            ":idcategory" => $this->getidcategory()
        ]);

        Category::updateFile();
    }

    public static function updateFile(){

        $categories = Category::listAll();
        //define que o a variavel é um array
        $html = [];

        foreach($categories as $row ){
            array_push($html,'<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');

        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "categories-menu.html", implode('',$html));

    }

}




?>