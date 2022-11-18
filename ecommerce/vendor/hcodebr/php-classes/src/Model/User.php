<?php

namespace Hcode\Model;
//usando use para pegar outro namespace
//a barra está querendo pegar da raiz

use Exception;
use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model {

    const SESSION = "User";
    const SECRET = "Diego_Secret";

    public static function login($login,$password){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
            ":LOGIN" =>$login
        ));

        if(count($results) === 0){
            throw new \Exception("Usuário inexistente ou senha inválida.");
        }

        $data = $results[0];

      if(password_verify($password, $data["despassword"]) === true){
        $user = new User();

        $user->setData($data);

        $_SESSION[User::SESSION] = $user->getValues();

        return $user;



      }else{
        throw new \Exception("Usuário inexistente ou senha inválida.");
      }

    }

    public static function verifyLogin($inadmin = true){

        if(
            !isset($_SESSION[User::SESSION])
            ||
            !$_SESSION[User::SESSION]
            ||
            !(int)$_SESSION[User::SESSION]['iduser'] > 0
            ||
            (bool)$_SESSION[User::SESSION]['inadmin'] !== $inadmin 
            ){

            header("Location: /admin/login");
            exit;
            
        }
    }

    public static function logout(){

        $_SESSION[User::SESSION] = NULL;
        
    }


    public static function listAll(){
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");
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

       $results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
        ":desperson"   =>   $this->getdesperson(),
        ":deslogin"    =>  $this->getdeslogin(),
        ":despassword" =>  $this->getdespassword(),
        ":desemail"    =>  $this->getdesemail(),
        ":nrphone"     =>  $this->getnrphone(),
        ":inadmin"     => $this->getinadmin()



        ));

        $this->setData($results[0]);
    }


    public function get($iduser){
        
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser",array(
            ":iduser" => $iduser
        ));

        $this->setData($results[0]);
    }

    public function update(){

        $sql = new Sql();

        $results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":iduser"      =>   $this->getiduser(),
            ":desperson"   =>   $this->getdesperson(),
            ":deslogin"    =>  $this->getdeslogin(),
            ":despassword" =>  $this->getdespassword(),
            ":desemail"    =>  $this->getdesemail(),
            ":nrphone"     =>  $this->getnrphone(),
            ":inadmin"     => $this->getinadmin()
    
    
    
            ));
    
            $this->setData($results[0]);


    }


    public function delete(){
        $sql = new Sql();

        $sql->query("CALL sp_users_delete(:iduser)",array(
            ":iduser" => $this->getiduser()
        ));
    }
    /*
    //AGUARDANDO A AULA SER REGRAVADA
    public static function getForgot($email){

        //verificar se o e-mail ta cadastrado no banco
        $sql = new Sql();

      $results =  $sql->select("SELECT
         * 
        from db_ecommerce.tb_persons a 
        inner join db_ecommerce.tb_users b using(idperson)
        where a.desemail = :email",array(
            ":email" => $email
            //bindparams
        ));
        //o a.desemail vai ser nosso parametro então.. :email

        //validar se não encontrar o e-mail

        if(count($results) === 0){
            throw new \Exception("Não foi possível recuperar a senha");

        }else{
            //criar um novo registro para recuperação de senha
            $data = $results[0];

           $results2 = $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser,:desip)",array(
                ":iduser" => $data["iduser"], 
                ":desip"  => $_SERVER["REMOTE_ADDR"] //pega o ip do usuário
            ));

            if(count($results2) === 0){

                throw new \Exception("Não foi possível recuperar a senha");
            }else{

                $dataRecovery = $results2[0];

                base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128,User::SECRET,$dataRecovery["idrecovery"],MCRYPT_MODE_ECB));
            }
        }


    }
    */

 




}




?>