<h1>Lista de usuários</h1>
<?php

    $sql = "SELECT * FROM tb_usuarios";

    $results = $conn->query($sql);

    $qtdRes = $results->num_rows;

    if($qtdRes > 0){
        echo "<table class='table table-hover table-striped table-bordered'>";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>Nome</th>";
        echo "<th>E-mail</th>";
        echo "<th>Data Nascimento</th>";
        echo "<th>Ações</th>";
        echo "</tr>";
        
        while($row = $results->fetch_object()){
            echo "<tr>";
            echo "<td>".$row->id_usuario."</td>";
            echo "<td>".$row->descricao."</td>";
            echo "<td>".$row->email."</td>";
            echo "<td>".$row->datausuario."</td>";
            echo "<td>
            <button onclick=\"location.href='?page=editar&id_usuario=".$row->id_usuario."';\" class='btn btn-success'>Editar</button>
            <button onclick=\"if(confirm('Tem certeza que deseja excluir?'))
            {location.href='?page=salvar&acao=exluir&id_usuario=".$row->id_usuario."';
            }else{false;}\" class='btn btn-danger'>Excluir</button>
                </td>";
            echo "</tr>";
        }
        echo "</table>";

    }else{
        echo "<p class='alert alert-danger'>Não encontrou resultados!</p>" ;
    }

 



?>