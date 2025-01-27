<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Tableau de bord d'administrateur</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="../stock.ico"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" />
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="white">
            <a href="index.php" class="logo">
              <img
                src="../stock.png"
                alt="navbar brand"
                class="navbar-brand"
                height="20"
              />WISDOM SAAS
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item active">
                <a
                  data-bs-toggle="collapse"
                  href="#dashboard"
                  class="collapsed"
                  aria-expanded="false"
                >
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="dashboard">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="index.php">
                        <span class="sub-item">Tableau de bord</span>
                      </a>
                    </li>
                    <li>
                      <a href="../index.php?entreprise=<?php echo $_SESSION['nom_entreprise'];?>">
                        <span class="sub-item">Magasin d'article</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a href="../gestion fournisseur/fournisseur.php">
                  <i class="fas fa-truck"></i>
                  <p>Fournisseurs</p>
                  <span class="badge badge-success"></span>
                </a>
              </li>
              <li class="nav-item">
                <a href="../gestionproduit/stock.php">
                  <i class="fas fa-box"></i>
                  <p>Produits</p>
                  <span class="badge badge-success"></span>
                </a>
              </li>
              <li class="nav-item">
                <a href="../Catégorie de produit/category.php">
                  <i class="fas fa-tags"></i>
                  <p>Categories des produits</p>
                  <span class="badge badge-success"></span>
                </a>
              </li>
              <li class="nav-item">
                <a href="../gestionemploye/employe.php">
                  <i class="fas fa-user-tie"></i>
                  <p>Employé</p>
                  <span class="badge badge-success"></span>
                </a>
              </li>
              <?php if ($_SESSION['type']=="Super_Admin") {?>
                <li class="nav-item">
                  <a href="../gestionentreprises/entreprises.php">
                      <i class="fas fa-building"></i> <!-- Changer ici pour correspondre à votre logo -->
                      <p>Entreprises</p>
                      <span class="badge badge-success"></span>
                  </a>
              </li>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="white">
              <a href="index.html" class="logo">
                <img
                src="../stock.png"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
              <nav
                class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
              >
                <div class="input-group">
                  <div class="input-group-prepend">
                    <button type="submit" class="btn btn-search pe-1">
                      <i class="fa fa-search search-icon"></i>
                    </button>
                  </div>
                  <input
                    type="text"
                    placeholder="Recherche..."
                    class="form-control"
                  />
                </div>
              </nav>

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li
                  class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none"
                >
                  <a
                    class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-expanded="false"
                    aria-haspopup="true"
                  >
                    <i class="fa fa-search"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-search animated fadeIn">
                    <form class="navbar-left navbar-form nav-search">
                      <div class="input-group">
                        <input
                          type="text"
                          placeholder="Recherche..."
                          class="form-control"
                        />
                      </div>
                    </form>
                  </ul>
                </li>
                <?php
// Connexion à la base de données
if ($conn) {
   
    
    // Récupérer tous les messages
    $messages = [];
    while ($message = $result->fetch_assoc()) {
        $messages[] = $message; // Ajouter chaque message au tableau
    }
} 
?>

<li class="nav-item topbar-icon dropdown hidden-caret">
  <a
    class="nav-link dropdown-toggle"
    href="#"
    id="messageDropdown"
    role="button"
    data-bs-toggle="dropdown"
    aria-haspopup="true"
    aria-expanded="false"
  >
    <i class="fa fa-envelope"></i>
  </a>
  <ul
    class="dropdown-menu messages-notif-box animated fadeIn"
    aria-labelledby="messageDropdown"
  >
    <li>
      <div
        class="dropdown-title d-flex justify-content-between align-items-center"
      >
        Messages
        <a href="message.php" class="small">Marquer comme tout lu</a>
      </div>
    </li>
    <li>
      <div class="message-notif-scroll scrollbar-outer">
        <div class="notif-center">
          <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $message): ?>
            <a href="#">
              <div class="notif-img">
                <img src="assets/img/default-profile.jpg" alt="Img Profile" />
              </div>
              <div class="notif-content">
                <span class="subject"><?php echo htmlspecialchars($message['msg']); ?></span>
                <!--<span class="block"><?php echo htmlspecialchars($message['obj']); ?></span>-->
                <span class="time"><?php echo htmlspecialchars($message['date_msg']); ?></span>
              </div>
            </a>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Aucun message à afficher.</p>
          <?php endif; ?>
        </div>
      </div>
    </li>
    <li>
      <a class="see-all" href="javascript:void(0);"
        >voir tout les messages<i class="fa fa-angle-right"></i>
      </a>
    </li>
  </ul>
</li>              
                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a
                    class="nav-link"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <i class="fas fa-layer-group"></i>
                  </a>
                  <div class="dropdown-menu quick-actions animated fadeIn">
                    <div class="quick-actions-header">
                      <span class="title mb-1">Ajouts Rapide</span>
                      <span class="subtitle op-7">Selectionnez l'action à effectuer</span>
                    </div>
                    <div class="quick-actions-scroll scrollbar-outer">
                      <div class="quick-actions-items">
                        <div class="row m-0">
                          <a class="col-6 col-md-4 p-0" href="../gestionemploye/ajouter_employe.php">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-primary rounded-circle">
                                <i class="fas fa-user-tie"></i>
                              </div>
                              <span class="text">Employé</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="../gestion fournisseur/ajouter_fournisseur.php">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-secondary rounded-circle"
                              >
                                <i class="fas fa-truck"></i>
                              </div>
                              <span class="text">Fournisseurs</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="../gestionproduit/stock.php">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-danger rounded-circle">
                                <i class="fas fa-box"></i>
                              </div>
                              <span class="text">Produits</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="../Catégorie de produit/ajouter_categorie.php">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-success rounded-circle"
                              >
                                <i class="fas fa-tags"></i>
                              </div>
                              <span class="text">Categories des produits</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>

                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="../uploaded_img/<?php echo $_SESSION['img_utilisateur'];?>"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Salut,</span>
                      <span class="fw-bold"><?php echo $_SESSION['nom_utilisateur'];?></span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="../uploaded_img/<?php echo $_SESSION['img_utilisateur'];?>"
                              alt="image profile"
                              class="avatar-img rounded"
                            />
                          </div>
                          <div class="u-text">
                            <h4><?php echo $_SESSION['nom_utilisateur'];?></h4>
                            <p class="text-muted"><?php echo $_SESSION['email_utilisateur'];?></p>
                            <a
                              href="profile.html"
                              class="btn btn-xs btn-secondary btn-sm"
                              >View Profile</a
                            >
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Mon Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Parametre de compte</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../creation_compte/logout.php">Deconnexion</a>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>
