<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('log_errors', 1);
ini_set('error_log', 'D:\xampp\htdocs\PIGIEREVENTS\error.txt');


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
        error_clear_last();
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
            header("Location: AdminDashboard.php");
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

                $sql = "INSERT INTO candidat (nom, classe, img) VALUES (:nom, :classe, :img)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':classe', $classe);
                $stmt->bindParam(':img', $db_file_name);
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

        if(isset($_GET['delEvent'], $_GET['type'])){
            $id = $_GET['delEvent'];
            $type = $_GET['type'];
            $image = "SELECT img FROM $type WHERE id = :id";
            $stmt_img = $pdo->prepare($image);
            $stmt_img->bindParam(':id', $id);
            $stmt_img->execute();
            $img_to_delete = $stmt_img->fetch(PDO::FETCH_ASSOC);
            $file = $img_to_delete['img'] ?? null;
            if(unlink($file)){
                echo"GOOD";
            }
            $sql = "DELETE FROM $type WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header("Location: AdminDashboard.php");
        }

        /*if(isset($_POST['updateCategory'])){
            
            $id = $_POST['categoryId'];
            $nom_cat = $_POST['categoryName'];

            $sql = "UPDATE categorie SET nom_cat = :nom_cat WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nom_cat', $nom_cat);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            echo json_encode(["status" => "success"]);
            header("Location: AdminDashboard.php");
            exit();
        }*/

        
        if(isset($_POST['PartUpdate'])){
            error_log('Debut de la procedure de modification');
            $id_recu = $_POST['ID'];
            error_log("Valeur de l'id recu : ". $id_recu);
            flush();
            $nom = $_POST['newName'];
            $classe = $_POST['newCla'];
            $upload_dir = 'Uploads/Participants/';
            $image_change = false;
            error_log("Verif du nom recu " . $nom);
            error_log("Verif de la classe recu " . $classe);
            error_log("Verif du fihier image recu : " . $_FILES['participantImage']['name']);
            flush();
            
            if(isset($_FILES['participantImage']) && $_FILES['participantImage']['error'] === UPLOAD_ERR_OK){
                $file_tmp_name = $_FILES['participantImage']['tmp_name'];
                $file_name = $_FILES['participantImage']['name'];
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $new_file_name = uniqid('Img_', true). '.'. $file_extension;
                $file_destination = $upload_dir . $new_file_name;

                if(move_uploaded_file($file_tmp_name, $file_destination)){
                    $image_change = true;
                    $sql_select = "SELECT img FROM candidat WHERE id = :id";
                    $stmt_select = $pdo->prepare($sql_select);
                    $stmt_select->execute([':id' => $id_recu]);
                    $oldImagePath = $stmt_select->fetchColumn();
                }else{
                    //http_response_code(500);
                    
                    echo (json_encode(['success' => false, 'message' => 'Erreur lors du déplacement du fichier téléchargé.']));
                }
            }
                $setClauses = ['nom = :nom', 'classe = :classe'];
                $bindParams = [
                    ':nom' => $nom,
                    ':classe' => $classe,
                    ':id' => $id_recu
                ];

                if ($image_change) {
                    $setClauses[] = 'img = :image_path';
                    $bindParams[':image_path'] = $file_destination;
                }

                $sql = "UPDATE candidat SET " . implode(', ', $setClauses) . " WHERE id = :id";
                $stmt = $pdo->prepare($sql);

                try {
                    $stmt->execute($bindParams);
                    
                    if ($image_change && $oldImagePath && file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }

                    echo json_encode(['success' => true, 'message' => 'Mise à jour réussie.']);
                    echo "<script type='text/javascript'>notifyMessage('Element modifie avec succes', 'success');</script>";

                } catch (\PDOException $e) {
                    //http_response_code(500);
                    echo (json_encode(['success' => false, 'message' => "Erreur BDD lors de la mise à jour: " . $e->getMessage()]));
                }
            }


    if(isset($_POST['CatUpdate'])){
            error_log('Debut de la procedure de modification');
            $id_recu = $_POST['ID'];
            error_log("Valeur de l'id recu : ". $id_recu);
            flush();
            $nom = $_POST['newName'];
            $des = $_POST['newDes'];
            $upload_dir = 'Uploads/Events/';
            $active_cat = isset($_POST['categoryActive']) ? 1 : 0;
            $image_change = false;
            error_log("Verif du nom recu " . $nom);
            error_log("Verif de la description recu " . $des);
            error_log("Verif du fihier image recu : " . $_FILES['categoryImage']['name']);
            flush();
            
            if(isset($_FILES['categoryImage']) && $_FILES['categoryImage']['error'] === UPLOAD_ERR_OK){
                $file_tmp_name = $_FILES['categoryImage']['tmp_name'];
                $file_name = $_FILES['categoryImage']['name'];
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $new_file_name = uniqid('Img_', true). '.'. $file_extension;
                $file_destination = $upload_dir . $new_file_name;

                if(move_uploaded_file($file_tmp_name, $file_destination)){
                    $image_change = true;
                    $sql_select = "SELECT img FROM categorie WHERE id = :id";
                    $stmt_select = $pdo->prepare($sql_select);
                    $stmt_select->execute([':id' => $id_recu]);
                    $oldImagePath = $stmt_select->fetchColumn();
                }else{
                    //http_response_code(500);
                    echo (json_encode(['success' => false, 'message' => 'Erreur lors du déplacement du fichier téléchargé.']));
                }
            }
                $setClauses = ['nom_cat = :nom', 'des_cat = :des', 'isActive = :active'];
                $bindParams = [
                    ':nom' => $nom,
                    ':des' => $des,
                    ':id' => $id_recu,
                    ':active' => $active_cat
                ];

                if ($image_change) {
                    $setClauses[] = 'img = :image_path';
                    $bindParams[':image_path'] = $file_destination;
                }

                $sql = "UPDATE categorie SET " . implode(', ', $setClauses) . " WHERE id = :id";
                $stmt = $pdo->prepare($sql);

                try {
                    $stmt->execute($bindParams);
                    
                    if ($image_change && $oldImagePath && file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }

                    echo json_encode(['success' => true, 'message' => 'Mise à jour réussie.']);

                } catch (\PDOException $e) {
                    //http_response_code(500);
                    echo (json_encode(['success' => false, 'message' => "Erreur BDD lors de la mise à jour: " . $e->getMessage()]));
                }
            }
    } catch (PDOException $e){
        die("Erreur de connexion : " . $e->getMessage()); 
    }
    exit();
    
?>