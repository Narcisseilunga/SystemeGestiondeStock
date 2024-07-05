<!DOCTYPE html>
<html>
<head>
    <title>Création de compte utilisateur</title>
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
            background-color: #f0f2f5;
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
        input[type="text"], input[type="email"], input[type="password"], input[type="tel"], select, input[type="submit"], input[type="button"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"], input[type="button"] {
            background-color: #1877f2;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #166fe5;
        }
        .ann{
            background-color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Création de compte utilisateur</h2>
        <form action="traitement.php" method="post">
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" required>
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required>
            <label for="genre">Genre:</label>
            <select id="genre" name="genre">
                <option value="homme">Homme</option>
                <option value="femme">Femme</option>
            </select>
            <label for="telephone">Numéro de téléphone:</label>
            <input type="tel" id="telephone" name="telephone" required>
            <label for="email">Adresse email:</label>
            <input type="email" id="email" name="email" required>
            <label for="motdepasse">Mot de passe:</label>
            <input type="password" id="motdepasse" name="motdepasse" required>
            <input type="submit" value="Créer compte">
            <input class="ann" type="button" value="Annuler" onclick="window.location.href='annuler.php'" >
        </form>
    </div>
</body>
</html>