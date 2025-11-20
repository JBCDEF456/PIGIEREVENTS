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
        }
    </style>
  </head>
  <body>
 
  <div class="container-fluid ">
        
        <div class="row">
            
            <div class="col-12 d-md-none p-3 bg-light border-bottom">
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                    <i class="bi bi-list"></i> Menu
                </button>
            </div>

            <div class="col-md-3 col-lg-2 bg-dark text-white sidebar p-0 offcanvas-sm offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
                
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
                            <a href="#" class="nav-link text-white btncustom"  aria-current="page">
                                <i class="bi bi-house me-2"></i> Accueil
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link text-white btncustom">
                                <i class="bi bi-speedometer2 me-2"></i> Resultats
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link text-white btncustom">
                                <i class="bi bi-table me-2"></i> Gestions des votes
                            </a>
                        </li>
                        <hr>
                        <li>
                            <a href="#" class="nav-link text-white btndeco">
                                <i class="bi bi-people me-2"></i> Deconnexion
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <h1>Bienvenue sur le tableau de bord Admin</h1>
                
            </main>
            
        </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html> 