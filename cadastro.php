<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cadastro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
    <h3 class="text-center mb-4">Cadastro</h3>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control" placeholder="Digite seu nome" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Digite seu email" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="senha" class="form-control" placeholder="Digite sua senha" required>
      </div>

      <button type="submit" class="btn btn-success w-100 mb-3">Cadastrar</button>
    </form>

    <?php
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        require_once('conexao.php');
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
        try{
          $stmt = $pdo->prepare('INSERT INTO usuario (nome, email, senha)
                                 VALUES (? , ?, ?);');
          if($stmt->execute([$nome, $email, $senha])){
            echo "<div class='alert alert-success text-center' role='alert'>Cadastro realizado! Faça o login!</div>";
          } else {
            echo "<div class='alert alert-danger text-center' role='alert'>Erro ao cadastrar! Tente novamente.</div>";
          }
        } catch(Exception $e){
          echo "<div class='alert alert-danger text-center' role='alert'>Erro: " . $e->getMessage() . "</div>";
        }
      }
    ?>

    <p class="text-center mt-3 mb-0">
      Já tem conta? <a href="index.php">Faça login</a>
    </p>
  </div>
</div>

</body>
</html>