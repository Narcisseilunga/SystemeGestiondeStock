
<!DOCTYPE html>
<html>
<head>
    <title>Création de compte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }
        input[type="text"], input[type="email"], input[type="password"], input[type="submit"], input[type="button"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        input[type="button"] {
            background-color: #f44336;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Rénitialisation de Compte</h2>
        <form action="reset_password.php" method="post">            
            <input type="email" name="email" placeholder="Adresse email" required>
            <input type="submit" value="Renitialiser">
            <input type="button" value="Annuler" onclick="window.location.href='index.php'">
        </form>
    </div>
</body>
</html>
