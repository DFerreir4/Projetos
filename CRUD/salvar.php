<?php

    switch($_REQUEST['acao']){

        //Cadastrar
        case 'cadastrar':
            $nome = $_POST['descricao'];
            $email = $_POST['email'];
            $senha = md5($_POST['senha']);
            $dataNascimento = $_POST['datausuario'];

            $sql = "INSERT INTO tb_usuarios (descricao, email,senha,datausuario)
                    VALUES ('{$nome}', '{$email}', '{$senha}', '{$dataNascimento}')";

            $results = $conn->query($sql);     

            if($results == true){
                echo "<script>alert('Cadastro feito com sucesso'!);</script>";
                
                echo "<script>location.href='?page=listar'</script>";

            }else{
                echo "<script>alert('Não foi possivel cadastrar!');</script>";
                
                echo "<script>location.href='?page=listar'</script>";
            }

        break;
        //Editar
        case 'editar':
            $nome = $_POST['descricao'];
            $email = $_POST['email'];
            $senha = md5($_POST['senha']);
            $dataNascimento = $_POST['datausuario'];
            
            $sql = "UPDATE tb_usuarios SET
                descricao = '{$nome}',
                email = '{$email}',
                senha = '{$senha}',
                datausuario = '{$dataNascimento}'

             WHERE id_usuario = ".$_REQUEST["id_usuario"];

            $results = $conn->query($sql);  

            if($results == true){
                echo "<script>alert('Editado feito com sucesso'!);</script>";
                
                echo "<script>location.href='?page=listar'</script>";

            }else{
                echo "<script>alert('Não foi possivel Editar!');</script>";
                
                echo "<script>location.href='?page=listar'</script>";
            }

        break;

        //Excluir
        case 'excluir':

            $sql = "DELETE FROM tb_usuarios WHERE id_usuario =".$_REQUEST["id_usuario"];

            $results = $conn->query($sql);  

            if($results == true){
                echo "<script>alert('Excluído com sucesso'!);</script>";
                
                echo "<script>location.href='?page=listar'</script>";

            }else{
                echo "<script>alert('Não foi possivel Excluir!');</script>";
                
                echo "<script>location.href='?page=listar'</script>";
            }


        break;
            
    
    
    }




?>