<?php
  
  session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Gerenciamento de Eventos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
    <h3 class="text-center mb-4">Sistema de Gestão de Eventos</h3>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control" placeholder="Digite seu email" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Senha</label>
        <input name="senha" type="password" class="form-control" placeholder="Digite sua senha" required>
      </div>

      <button type="submit" class="btn btn-primary w-100 mb-3">Entrar</button>
    </form>

    <?php
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        require_once('conexao.php');
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        
        try{
          $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
          $stmt->execute([$email]);
          $usuario = $stmt->fetch();
          
          
          if($usuario && password_verify($senha, $usuario['senha'])){
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['acesso'] = true; 
            header('Location: principal.php');
            exit(); 
          } else {
            echo "<div class='alert alert-danger text-center' role='alert'>Credenciais inválidas!</div>";
          }
        } catch(Exception $e){
          echo "<div class='alert alert-danger text-center' role='alert'>Erro: " . $e->getMessage() . "</div>";
        }
      }
    ?>

    <p class="text-center mt-3 mb-0">
      Não tem conta? <a href="cadastro.php">Cadastre-se</a>
    </p>
  </div>
</div>

</body>
</html>