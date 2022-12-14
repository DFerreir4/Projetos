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

    public function getProducts($related = true) //por padrão ele vai trazer todos que estão relacionados a categoria
    {

        $sql = new Sql();

        if($related === true){

           return $sql->select("
           SELECT * FROM db_ecommerce.tb_products WHERE idproduct IN(
                select a.idproduct from db_ecommerce.tb_products a
                inner join db_ecommerce.tb_productscategories b on a.idproduct = b.idproduct
                where b.idcategory = :idcategory
            );
            
            ",[':idcategory' => $this->getidcategory()]);

        }else{
          return  $sql->select("
            SELECT * FROM db_ecommerce.tb_products WHERE idproduct NOT IN(
                select a.idproduct from db_ecommerce.tb_products a
                inner join db_ecommerce.tb_productscategories b on a.idproduct = b.idproduct
                where b.idcategory = :idcategory
            );
        ",[':idcategory' => $this->getidcategory()]);
        }
    }


    public function addProduct(Product $product)
    {
        $sql = new Sql();

        $sql->query("INSERT INTO tb_productscategories (idcategory, idproduct) VALUES(:idcategory,:idproduct)",[
            ':idcategory' => $this->getidcategory(),
            ':idproduct' => $product->getidproduct()
        ]);

    }

    public function removeProduct(Product $product)
    {
        $sql = new Sql();

        $sql->query("DELETE FROM tb_productscategories  WHERE idcategory = :idcategory AND idproduct = :idproduct",[
            ':idcategory' => $this->getidcategory(),
            ':idproduct' => $product->getidproduct()
        ]);

    }

    public function getProductsPage($page = 1,$itensPerPage = 8)
    {   
        

        $start = ($page-1) * $itensPerPage;

        $sql = new Sql();
        //resultado dos produtos
        $results = $sql->select("
        SELECT SQL_CALC_FOUND_ROWS * FROM db_ecommerce.tb_products a 
        INNER JOIN db_ecommerce.tb_productscategories b 
        ON a.idproduct = b.idproduct
        INNER JOIN db_ecommerce.tb_categories c 
        ON c.idcategory = b.idcategory
        WHERE c.idcategory = :idcategory
        LIMIT $start, $itensPerPage;",[
            ':idcategory' => $this->getidcategory()
        ]);
        //resultado total
        $resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");
   
        return [
            'data' => Product::checkList($results),
            'total' =>(int)$resultTotal[0]["nrtotal"],
            'pages' => ceil($resultTotal[0]["nrtotal"] / $itensPerPage)
            //função do php que converte pra cima
        ];
   
    }







}




?>