<?php
session_start(); 
    header('Content-Type: application/json');

    function getParticipants($pdo){
        $stmt = $pdo->query("SELECT * FROM candidat");
        return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    function getCategories($pdo){
        $stmt = $pdo->query("SELECT * FROM categorie");
        return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    //

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

        if(isset($_POST["submitCategory"])){
            $nom_cat = $_POST['categoryName']; 
            $sql = "INSERT INTO categorie (nom_cat) VALUES (:nom_cat)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nom_cat', $nom_cat);
            $stmt->execute();

            echo json_encode(["status" => "success"]);
            header("Location: AdminDashboard.php");
            exit();

        }

        if(isset($_POST["submitParticipants"])){
            
            if(isset($_FILES['participantPhoto']) && $_FILES['participantPhoto']['error'] === UPLOAD_ERR_OK){

                $upload_dir = 'Uploads/Participants/';
                $db_file_name = null;
                $file_tmp_name = $_FILES['participantPhoto']['tmp_name'];
                $file_name = $_FILES['participantPhoto']['name'];
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $new_file_name = uniqid('img_', true).'.'.$file_extension;
                $file_destination = $upload_dir . $new_file_name;

                $allowed_extensions = ['jpg', 'jpeg', 'png'];
                if(!in_array($file_extension, $allowed_extensions)){
                    die("Type de fichier non autorisé.");
                }
                if(move_uploaded_file($file_tmp_name, $file_destination)){
                    $db_file_name = $file_destination;
                } else {
                    die("Échec du téléchargement de l'image.");
                }

                $nom = $_POST['participantName'];
                $classe = $_POST['participantClass'];

                $sql = "INSERT INTO candidat (nom, classe, img_part) VALUES (:nom, :classe, :img_part)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':classe', $classe);
                $stmt->bindParam(':img_part', $db_file_name);
                $stmt->execute();

                echo json_encode(["status" => "success"]);
                header("Location: AdminDashboard.php");
                exit();
            }
            else{
                echo $_FILES['participantPhoto']['error'];
                header("Location: AdminDashboard.php");
                exit();
            }


        }

        if(isset($_GET['delEvents'])){
            $id = $_GET['delEvents'];
            $image = "SELECT img_cat FROM categorie WHERE id_cat = :id";
            $stmt_img = $pdo->prepare($image);
            $stmt_img->execute([':id' => $id]);
            $img_to_delete = $stmt_img->fetch(PDO::FETCH_ASSOC);
            $file = $img_to_delete['img_cat'] ?? null;
            if(unlink($file)){
                echo"GOOD";
            }
            $sql = "DELETE FROM categorie WHERE id_cat = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo "L'evenement est parti";
            header("Location: AdminDashboard.php");
        }
    } catch (PDOException $e){
        die("Erreur de connexion : " . $e->getMessage()); 
    }
    exit();
    header("Location: AdminDashboard.php");
?>