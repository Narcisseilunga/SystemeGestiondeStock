<!DOCTYPE html>
<html>
<head>
    <title>Connexion/Inscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1877f2;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            color: #333;
        }
        input[type="email"], input[type="password"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #1877f2;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #166fe5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <form action="test.php" method="post">
            <label for="email">Adresse email:</label>
            <input type="email" id="email" name="email">
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password">
            <input type="submit" value="Se connecter">
        </form>
        <p>
            pas de compte ?<br>
            <a href="inscription_util.php">enregistrez-vous</a>
        </p>
        <!--<p> Vous avez oublier votre mot de passe ?<br>
            <a href="donnee_reset.php">Renitialiser-le</a>
        </p>-->
    </div>
</body>
</html>