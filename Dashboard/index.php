<?php
// Dans toutes nos pages 
session_start();
if (isset($_SESSION['id_utilisateur']))
{ $user_id = $_SESSION['id_utilisateur'];
  require "../db.php";

  $nom_entreprise = $_SESSION['nom_entreprise'];
  
  if ($_SESSION['type']=="Super_Admin"){
    $resultAbonnes = $conn->query("SELECT COUNT(*) as total_abonnes FROM compte");
    $row = $resultAbonnes->fetch_assoc();
    $nombreAbonnes = $row['total_abonnes'];
    
    // Somme des ventes
    $queryVentes = "SELECT SUM(prix) as total_ventes FROM commandes";
    $resultVentes = $conn->query($queryVentes);
    $nombreVentes = $resultVentes->fetch_assoc()['total_ventes'];
    
    // Compter le nombre de fournisseurs
    $queryFournisseurs = "SELECT COUNT(*) as total_fournisseurs FROM supplier";
    $resultFournisseurs = $conn->query($queryFournisseurs);
    $nombreFournisseurs = $resultFournisseurs->fetch_assoc()['total_fournisseurs'];
    
    // Compter le nombre de produits
    $queryProduits = "SELECT COUNT(*) as total_produits FROM product";
    $resultProduits = $conn->query($queryProduits);
    $nombreProduits = $resultProduits->fetch_assoc()['total_produits'];
    
    // Compter le nombre d'employés
    $queryEmployee = "SELECT COUNT(*) as total_employee FROM employee";
    $resultEmployee = $conn->query($queryEmployee);
    $nombreEmployee = $resultEmployee->fetch_assoc()['total_employee'];
    
    // Compter le nombre de catégories
    $queryCateg = "SELECT COUNT(*) as total_categorie FROM category";
    $resultCateg = $conn->query($queryCateg);
    $nombreCateg = $resultCateg->fetch_assoc()['total_categorie'];
  
    $queryClients = "SELECT COUNT(*) as total_clients FROM compte";
    $resultClients = $conn->query($queryClients);
    $rowClients = $resultClients->fetch_assoc();
    $nombreClients = $rowClients['total_clients'];
  
    // Récupérer le nombre de commandes
    $queryCommandes = "SELECT COUNT(*) as total_commandes FROM commandes";
    $resultCommandes = $conn->query($queryCommandes);
    $rowCommandes = $resultCommandes->fetch_assoc();
    $nombreCommandes = $rowCommandes['total_commandes'];
  
    $query = "SELECT msg, date_msg FROM messages ORDER BY temp DESC LIMIT 4";
      $stmt = $conn->prepare($query);
  
      $stmt->execute();
  
      $result = $stmt->get_result();
    
  }elseif($_SESSION['type']=="Admin") {
    // Compter le nombre d'abonnés
    $resultAbonnes = $conn->query("SELECT COUNT(*) as total_abonnes FROM users WHERE nom_entreprise = '$nom_entreprise'");
    $row = $resultAbonnes->fetch_assoc();
    $nombreAbonnes = $row['total_abonnes'];
  
    // Somme des ventes
    $queryVentes = "SELECT SUM(prix) as total_ventes FROM commandes WHERE nom_entreprise = '$nom_entreprise'";
    $resultVentes = $conn->query($queryVentes);
    $nombreVentes = $resultVentes->fetch_assoc()['total_ventes'];
  
    // Compter le nombre de fournisseurs
    $queryFournisseurs = "SELECT COUNT(*) as total_fournisseurs FROM supplier WHERE nom_entreprise = '$nom_entreprise'";
    $resultFournisseurs = $conn->query($queryFournisseurs);
    $nombreFournisseurs = $resultFournisseurs->fetch_assoc()['total_fournisseurs'];
  
    // Compter le nombre de produits
    $queryProduits = "SELECT COUNT(*) as total_produits FROM product WHERE nom_entreprise = '$nom_entreprise'";
    $resultProduits = $conn->query($queryProduits);
    $nombreProduits = $resultProduits->fetch_assoc()['total_produits'];
  
    // Compter le nombre d'employés
    $queryEmployee = "SELECT COUNT(*) as total_employee FROM employee WHERE nom_entreprise = '$nom_entreprise'";
    $resultEmployee = $conn->query($queryEmployee);
    $nombreEmployee = $resultEmployee->fetch_assoc()['total_employee'];
  
    // Compter le nombre de catégories
    $queryCateg = "SELECT COUNT(*) as total_categorie FROM category WHERE nom_entreprise = '$nom_entreprise'";
    $resultCateg = $conn->query($queryCateg);
    $nombreCateg = $resultCateg->fetch_assoc()['total_categorie'];
    $queryClients = "SELECT COUNT(*) as total_clients FROM users WHERE nom_entreprise = ?";
  
    $stmtClients = $conn->prepare($queryClients);
    $stmtClients->bind_param("s", $nom_entreprise);
    $stmtClients->execute();
    $rowClients = $stmtClients->get_result()->fetch_assoc();
    $nombreClients = $rowClients['total_clients'];
  
    // Récupérer le nombre de commandes
    $queryCommandes = "SELECT COUNT(*) as total_commandes FROM commandes WHERE nom_entreprise = ?";
    $stmtCommandes = $conn->prepare($queryCommandes);
    $stmtCommandes->bind_param("s", $nom_entreprise);
    $stmtCommandes->execute();
    $rowCommandes = $stmtCommandes->get_result()->fetch_assoc();
    $nombreCommandes = $rowCommandes['total_commandes'];
  
    $query = "SELECT msg, date_msg, user_msg_recep FROM messages WHERE nom_entreprise = ? ORDER BY date_msg DESC LIMIT 4";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nom_entreprise);
  
    $stmt->execute();
  
    $result = $stmt->get_result();
  
  // Récupérer tous les messages
  
    }
require_once 'header.php' ?>
<div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Tableau de bord</h3>
                <h6 class="op-7 mb-2"><?php echo $_SESSION['nom_entreprise'];?></h6>
              </div>
              <div class="ms-md-auto py-2 py-md-0">
                <a href="../À-propos-de.php?entreprise=<?php echo $_SESSION['nom_entreprise'];?>" class="btn btn-label-info btn-round me-2">Voir les employé</a>
                <a href="message.php" class="btn btn-primary btn-round">Boite de reception</a>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-info  bubble-shadow-small"
                        >
                          <i class="fas fa-users"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Visiteurs</p>
                          <h4 class="card-title"><?php
                              if ($_SESSION['type']=="Super_Admin") {
                                  $cheminFichier = "../compteur.txt";$contenu = file_get_contents($cheminFichier);preg_match('/(\d+)/', $contenu, $matches);$nombreVisiteurs = $matches[0];
                                
                              }else {
                                  $cheminFichier = "../compteur$nom_entreprise.txt";$contenu = file_get_contents($cheminFichier);preg_match('/(\d+)/', $contenu, $matches);$nombreVisiteurs = $matches[0];
                                
                              }
                                
                              
                              echo $nombreVisiteurs;?>
                          </h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
<div class="col-sm-6 col-md-3">
  <div class="card card-stats card-round">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-icon">
          <div class="icon-big text-center icon-info bubble-shadow-small">
            <i class="fas fa-user-check"></i>
          </div>
        </div>
        <div class="col col-stats ms-3 ms-sm-0">
          <div class="numbers">
            <p class="card-category">Abonnées</p>
            <h4 class="card-title"><?php echo $nombreAbonnes; ?></h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-sm-6 col-md-3">
  <div class="card card-stats card-round">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-icon">
          <div class="icon-big text-center icon-warning bubble-shadow-small">
            <i class="fas fa-shopping-cart text-success"></i>
          </div>
        </div>
        <div class="col col-stats ms-3 ms-sm-0">
          <div class="numbers">
            <p class="card-category">Ventes</p>
            <h4 class="card-title">$ <?php echo $nombreVentes; ?></h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-sm-6 col-md-3">
  <div class="card card-stats card-round">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-icon">
          <div class="icon-big text-center icon-secondary bubble-shadow-small">
            <i class="fas fa-truck"></i>
          </div>
        </div>
        <div class="col col-stats ms-3 ms-sm-0">
          <div class="numbers">
            <p class="card-category">Fournisseurs</p>
            <h4 class="card-title"><?php echo $nombreFournisseurs; ?></h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-sm-6 col-md-3">
  <div class="card card-stats card-round">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-icon">
          <div class="icon-big text-center icon-danger bubble-shadow-small">
            <i class="fas fa-box"></i>
          </div>
        </div>
        <div class="col col-stats ms-3 ms-sm-0">
          <div class="numbers">
            <p class="card-category">Produits</p>
            <h4 class="card-title"><?php echo $nombreProduits; ?></h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
            <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-icon">
                    <div class="icon-big text-center icon-success bubble-shadow-small">
                      <i class="fas fa-tags"></i> <!-- Icône modifiée -->
                    </div>
                  </div>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                      <p class="card-category">Categories des produits</p>
                      <h4 class="card-title"><?php echo $nombreCateg; ?></h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-icon">
                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                      <i class="fas fa-user-tie text-danger"></i> <!-- Icône modifiée -->
                    </div>
                  </div>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                      <p class="card-category">Employés</p>
                      <h4 class="card-title"><?php echo $nombreEmployee; ?></h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>           
          <div class="row">
          <div class="col-md-8">
  <div class="card card-round">
    <div class="card-header">
      <div class="card-head-row">
        <div class="card-title">Admin Statistics</div>
        <div class="card-tools">
          <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
            <span class="btn-label">
              <i class="fa fa-pencil"></i>
            </span>
            Export
          </a>
          <a href="#" class="btn btn-label-info btn-round btn-sm">
            <span class="btn-label">
              <i class="fa fa-print"></i>
            </span>
            Print
          </a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="chart-container" style="min-height: 375px">
        <canvas id="statisticsCharts"></canvas>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('statisticsCharts').getContext('2d');
    var statisticsChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Visiteurs', 'Clients', 'Commandes'],
        datasets: [{
          label: 'Statistiques',
          data: [<?php echo $nombreVisiteurs; ?>, <?php echo $nombreClients; ?>, <?php echo $nombreCommandes; ?>],
          backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)'],
          borderColor: ['rgba(75, 192, 192, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  });


</script>
<div class="col-md-8">
  <div class="card card-round">
    <div class="card-header">
      <div class="card-head-row card-tools-still-right">
        <div class="card-title">Historique des Commandes</div>
        <div class="card-tools">
          <div class="dropdown">
            <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="#">Action</a>
              <a class="dropdown-item" href="#">Another action</a>
              <a class="dropdown-item" href="#">Something else here</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-items-center mb-0">
          <thead class="thead-light">
            <tr>
            <th scope="col">Nom</th>
              <th scope="col">Date & Heure</th>
              <th scope="col" class="text-end">Prix</th>
              <th scope="col" class="text-end">Validations</th>
              <th scope="col" class="text-end">Valider commande</th>
              <th scope="col" class="text-end">Contact vendeur</th>
            </tr>
          </thead>
          <tbody>
          <?php
$query = "SELECT id, nom_client, prix, numero_client, temp, devise, validation FROM commandes";
$stmt = $conn->prepare($query);

if ($stmt) {
    // Exécuter la requête
    $stmt->execute();

    // Lier les résultats
    $stmt->bind_result($id, $nom_client, $prix, $numero, $temp, $devise, $validation);
    while ($stmt->fetch()) {
        echo "<tr>";
        echo "<td class='text-end'>" . htmlspecialchars($nom_client) . "</td>";
        echo "<td class='text-end'>" . htmlspecialchars($temp) . "</td>";
        
        // Afficher le prix avec la devise
        if ($devise == "CDF") {
            echo "<td class='text-end'>" . htmlspecialchars($prix) . " CDF</td>";
        } else {
            echo "<td class='text-end'>$ " . htmlspecialchars($prix) . "</td>";
        }
        
        echo "<td class='text-end'>" . htmlspecialchars($validation) . "</td>";
        
        // Lien de validation qui mène à la page de validation
        echo "<td class='text-end'>
                <a href='validation_page.php?id=" . htmlspecialchars($id) . "'>
                    <i class='fas fa-pencil-alt'></i> Valider la commande
                </a>
              </td>";
        
        // Lien WhatsApp avec coloration
        if ($validation == "NON") {
          echo "<td class='text-end'>
                  <a href='#' style='color: red;'>
                      <i class='fab fa-whatsapp'></i> Contactez le client
                  </a>
                </td>";
      } else {
          echo "<td class='text-end'>
                  <a href='https://wa.me/" . htmlspecialchars($numero) . "' style='color: green;'>
                      <i class='fab fa-whatsapp'></i> Contactez le client
                  </a>
                </td>";
      }
      
      echo "</tr>";}
}
?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php

?>
</div>
  
</div>
          </div>
        </div>

<?php
$conn = null;
require_once 'footer.php';

}
else {
  // Rediriger l'utilisateur vers la page de connexion
  header("Location: ../creation_compte/connexion.php");       
  exit; }
?>
