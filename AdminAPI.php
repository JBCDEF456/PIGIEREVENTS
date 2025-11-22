<?php 
    header('Content-Type: application/json');

    function getParticipants($pdo){
        $stmt = $pdo->query("SELECT * FROM candidat");
        return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    function getCategories($pdo){
        $stmt = $pdo->query("SELECT * FROM categorie");
        return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    $dsn = "mysql:host=localhost;dbname=test";
    $user = "root";
    $password = "";
    try{
        $pdo = new PDO($dsn, $user, $password, [ 
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        if(isset($_GET['action'])){
            $action = $_GET['action'];
            switch($action){
                case "participants":
                    echo getParticipants($pdo);
                    break;
                case "categories":
                    echo getCategories($pdo);
                    break;
                default:
                    echo json_encode(["error" => "Action non reconnue"]);
                    break;
            }
        } else {
            echo json_encode(["error" => "Aucune action spécifiée"]);
        }
    } catch (PDOException $e){
        die("Erreur de connexion : " . $e->getMessage()); 
    }
?>