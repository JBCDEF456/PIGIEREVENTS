<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="Dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
       
        .sidebar {
            min-height: 100vh;
            border-top-right-radius: 5%;
            border-bottom-right-radius: 5%;
        }
    </style>
    <script src="Dashboard.js"></script>
  </head>
  <body>
 
  <div class="container-fluid ">
        
        <div class="row">
            
            <div class="col-12 d-md-none p-3 bg-light border-bottom">
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                    <i class="bi bi-list"></i> Menu
                </button>
            </div>

            <div class="col-md-4 col-lg-3 bg-dark text-white sidebar p-0 offcanvas-md offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
                
                <div class="offcanvas-header">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body d-flex flex-column p-3">
                    <a class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-4"><i class="bi bi-bootstrap"></i> P.V.S</span>
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto d-grid gap-2">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white btncustom sidebar-link active"  aria-current="page" id="home-link" data-bs-target="dashboard-section">
                                <i class="bi bi-house me-2"></i> Accueil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white btncustom sidebar-link" id="results-link" data-bs-target="results-section">
                                <i class="bi bi-speedometer2 me-2"></i> Resultats
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white btncustom sidebar-link" id="participants-link" data-bs-target="participants-section">
                                <i class="bi bi-question-circle me-2"></i> Gestions des participants
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white btncustom sidebar-link" id="votes-link" data-bs-target="votes-section">
                                <i class="bi bi-table me-2"></i> Gestions des votes
                            </a>
                        </li>
                        <hr>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white btndeco">
                                <i class="bi bi-people me-2"></i> Deconnexion
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <main class="col-md-8 ms-sm-auto col-lg-9 px-md-4 py-4" id="page-content-wrapper">
                
                <div class="section-content d-none" id="dashboard-section">
                    
                    <div class="container justify-content-center text-center">
                        <h1>Bienvenue sur le tableau de bord Admin</h1>
                    </div>
                        <div class="row">
                            <div class="col-md-6 mb-4 ">
                                <div class="card">
                                    <div class="card-body justify-content-center text-center">
                                        <h5 class="card-title">Nombre de personne sur la session</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4  ">
                                <div class="card">
                                    <div class="card-body justify-content-center text-center">
                                        <h5 class="card-title">Nombre de vote valide</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4 ">
                                <div class="card">
                                    <div class="card-body justify-content-center text-center">
                                        <h5 class="card-title">Fonds collectes sur OM</h5>
                                        <?php
                                            $dsn = "mysql:host=localhost;dbname=test";
                                            $user = "root";
                                            $password = "";

                                            try {
                                            $pdo = new PDO($dsn, $user, $password, [
                                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                                                            ]);
                                            } catch (PDOException $e) {
                                            die("Erreur de connexion : " . $e->getMessage());
                                            }
                                            $sql = "SELECT SUM(montant)
                                            FROM vote
                                            WHERE operateur = 'Orange Money'";
                                            $stmt = $pdo->query($sql);
                                            $fondsOM = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4 ">
                                <div class="card">
                                    <div class="card-body justify-content-center text-center">
                                        <h5 class="card-title">Fonds collectes sur MTN</h5>
                                        <?php
                            
                            $dsn = "mysql:host=localhost;dbname=test";
                            $user = "root";
                            $password = "";

                            try {
                            $pdo = new PDO($dsn, $user, $password, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                                            ]);
                            } catch (PDOException $e) {
                            die("Erreur de connexion : " . $e->getMessage());
                            }
                            $sql = "SELECT SUM(montant)
                            FROM vote
                            WHERE operateur = 'MTN MoMo'";
                            $stmt = $pdo->query($sql);
                            $fondsMoMo = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                </div>

                <div class="section-content d-none" id="results-section">
                    <div class="container justify-content-center text-center">
                        <h1>Section des resultats</h1>
                            <?php
                            /*
                            $dsn = "mysql:host=localhost;dbname=test";
                            $user = "root";
                            $password = "";

                            try {
                            $pdo = new PDO($dsn, $user, $password, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                                            ]);
                            } catch (PDOException $e) {
                            die("Erreur de connexion : " . $e->getMessage());
                            }
                            $sql = "SELECT c.nom_cat AS Categorie, r.nom AS 'Nom Candidat', r.nombre_vote AS 'Votes totaux'
                            FROM resultats r JOIN categorie c ON c.id_cat = r.id_cat
                            GROUP BY c.nom_cat
                            ORDER BY r.nombre_vote DESC";
                            $stmt = $pdo->query($sql);
                            $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            */?>
                    </div>
                </div>

                <div class="section-content d-none" id="votes-section">
                    <div class="container justify-content-center text-center">
                        <h1>Section de gestion des votes</h1>
                    </div>
                        <div class="row col-lg-4 col-md-7 col-6 mx-auto mt-4">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Ajouter une categorie de vote</button>
                            <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title " id="addCategoryModalLabel">Ajouter une categorie de vote</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="addCategoryForm">
                                                <div class="mb-3">
                                                    <label for="categoryName" class="form-label justify-content-start">Nom de la categorie</label>
                                                    <input type="text" class="form-control" id="categoryName" required>
                                                    <?php /*
                                                        $dsn = "mysql:host=localhost;dbname=test";
                                                        $user = "root";
                                                        $password = "";

                                                        try {
                                                        $pdo = new PDO($dsn, $user, $password, [
                                                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                                                                        ]);
                                                        } catch (PDOException $e) {
                                                        die("Erreur de connexion : " . $e->getMessage());
                                                        }
                                                        $nom_cat = $_POST['categoryName']; 
                                                        $sql = " INSERT INTO categorie (nom_cat) VALUES (:nom_cat)";
                                                        $stmt = $pdo->query($sql);
                                                        $ajoutCat = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                        */?>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" id="categoryImage" accept="image/*" required>
                                                        <label class="input-group-text" for="categoryImage">Image categorie</label>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                                            <button type="button" class="btn btn-success" id="saveCategoryBtn">Enregistrer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="container">
                        <!-- Tableau des votes. Je compte sur toi pour le remplir Celio 
                                                        avec ton magiques PHP et SQL ;)-->
                            <?php
                            /*
                            $dsn = "mysql:host=localhost;dbname=test";
                            $user = "root";
                            $password = "";

                            try {
                            $pdo = new PDO($dsn, $user, $password, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                                            ]);
                            } catch (PDOException $e) {
                            die("Erreur de connexion : " . $e->getMessage());
                            }
                            $sql = " SELECT numero_tel AS Numero, montant AS Montant, montant/100 AS Votes, date_vote as 'Effectue', nom AS Candidat 
                            FROM vote";
                            $stmt = $pdo->query($sql);
                            $tableau = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            */?>

                    </div>
                </div>

                <div class="section-content d-none" id="participants-section">
                    <div class="container justify-content-center text-center">
                        <h1>Section de gestion des participants</h1>
                    </div>
                        <div class="row col-lg-4 col-md-7 col-6 mx-auto mt-4">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addParticipants">Ajouter un participant</button>
                            <div class="modal fade" id="addParticipants" tabindex="-1" aria-labelledby="addParticipantsLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title " id="addParticipantsLabel">Ajouter un participant</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="addParticipantsForm">
                                                <div class="mb-3">
                                                    <label for="participantName" class="form-label justify-content-start">Nom du participant</label>
                                                    <input type="text" class="form-control" id="participantName" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="participantClass" class="form-label justify-content-start">Classe du participant</label>
                                                    <input type="text" class="form-control" id="participantClass" required>
                                                        <?php
                                                        /*
                                                         $dsn = "mysql:host=localhost;dbname=test";
                                                        $user = "root";
                                                        $password = "";
                                                        try {
                                                        $pdo = new PDO($dsn, $user, $password, [

                                                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                                                            } catch (PDOException $e) {
                                                            die("Erreur de connexion : " . $e->getMessage());
                                                            }
                                                            $nom = $_POST['participantName'];
                                                            $classe = $_POST['participantClass'];
                                                            $sql = " INSERT INTO candidat (nom, classe) VALUES (:nom, :classe)";
                                                            $stmt = $pdo->query($sql);
                                                            $ajoutPart = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                            */?>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" id="participantPhoto" accept="image/*" required>
                                                        <label class="input-group-text" for="participantPhoto">Photo participants</label>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                                            <button type="button" class="btn btn-success" id="saveCategoryBtn">Enregistrer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="container">
                        <!-- Tableau des participants. Je compte sur toi pour le remplir Celio 
                                                        avec ton magiques PHP et SQL ;)-->
                            <?php
                            /*$dsn = "mysql:host=localhost;dbname=test";
                            $user = "root";
                            $password = "";

                            try {
                            $pdo = new PDO($dsn, $user, $password, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                                            ]);
                            } catch (PDOException $e) {
                            die("Erreur de connexion : " . $e->getMessage());
                            }
                            $sql = "SELECT *
                            FROM candidat";
                            $stmt = $pdo->query($sql);
                            $affichageCandidat = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            */?>
                    </div>
                    
                </div>
            </main>
            
        </div>
</div>
    <script src="Dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html> 