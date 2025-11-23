<?php
session_start();   
$dsn = "mysql:host=localhost;dbname=test";
$user = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    if (isset($_POST['participantName'], $_POST['participantClass'], $_POST['participantImage'])) {
        echo "Traitement de l'ajout du participant...";
        exit();
        $nom = $_POST['participantName'];
        $classe = $_POST['participantClass'];

        if(!isset($_FILES['participantImage']) || $_FILES['participantImage']['error'] !== UPLOAD_ERR_OK){
            die("Erreur lors du téléchargement de l'image.");
        }
        $upload_dir = 'Uploads/Participants/';
        $db_file_name = null;
        $file_tmp_name = $_FILES['participantImage']['tmp_name'];
        $file_name = $_FILES['participantImage']['name'];
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = uniqid('img_', true).$file_extension;
        $file_destination = $upload_dir . $new_file_name;

        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        if(!in_array($FILES['participantImage']['type'], $allowed_extensions)){
            die("Type de fichier non autorisé.");
        }
        if(move_uploaded_file($file_tmp_name, $file_destination)){
            $db_file_name = $file_destination;
        } else {
            die("Échec du téléchargement de l'image.");
        }

        $sql = "INSERT INTO candidat (nom, classe, image_cand) VALUES (:nom, :classe, :image_cand)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':classe', $classe);
        $stmt->bindParam(':image_cand', $db_file_name);
        if ($stmt->execute()) {
        echo "Enregistrement réussi !";
        header("Location: AdminDashboard.php");
        exit();
    } else {
        echo "Une erreur est survenue.";
    }

        echo json_encode(["status" => "success"]);
    }
    else if (isset($_POST['categoryName'])) {

        if(isset($_FILES['categoryImage']) && $_FILES['categoryImage']['error'] === UPLOAD_ERR_OK){

            $upload_dir = 'Uploads/Events/';
            $db_file_name = null;
            $file_tmp_name = $_FILES['categoryImage']['tmp_name'];
            $file_name = $_FILES['categoryImage']['name'];
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
        }else {
            echo $_FILES['categoryImage']['error'];
        }
        
        $nom_cat = $_POST['categoryName']; 
        $des_cat = $_POST['categoryDescription'];
        $active_cat = isset($_POST['categoryActive']) ? 1 : 0;
        $sql = "INSERT INTO categorie (nom_cat, img, des_cat, isActive) VALUES (:nom_cat, :img, :des_cat, :isActive)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom_cat', $nom_cat);
        $stmt->bindParam(':img', $db_file_name);
        $stmt->bindParam(':des_cat', $des_cat);
        $stmt->bindParam(':isActive', $active_cat);
        if ($stmt->execute()) {
            header("Location: AdminDashboard.php");
        exit();
    } else {
        echo "Une erreur est survenue.";
    }

        echo json_encode(["status" => "success"]);
    }
    else if (isset($_POST['results-link'])) {
        $sql = "SELECT c.nom_cat AS Categorie, r.nom AS 'Nom Candidat', r.nombre_vote AS 'Votes totaux'
                FROM resultats r 
                JOIN categorie c ON c.id = r.id
                GROUP BY c.nom_cat
                ORDER BY r.nombre_vote DESC";

        $stmt = $pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
        header("Location: AdminDashboard.php");
    }

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
//header("Location: AdminDashboard.php");
?>
