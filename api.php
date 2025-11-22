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
        $nom = $_POST['participantName'];
        $classe = $_POST['participantClass'];
        $image_cand = $_POST['participantImage'];

        $sql = "INSERT INTO candidat (nom, classe, image_cand) VALUES (:nom, :classe, :image_cand)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':classe', $classe);
        $stmt->bindParam(':image_cand', $image_cand);
        $stmt->execute();

        echo json_encode(["status" => "success"]);
    }
    else if (isset($_POST['categoryName'])) {
        $nom_cat = $_POST['categoryName']; 
        $sql = "INSERT INTO categorie (nom_cat) VALUES (:nom_cat)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom_cat', $nom_cat);
        $stmt->execute();

        echo json_encode(["status" => "success"]);
    }
    else if (isset($_POST['results-link'])) {
        $sql = "SELECT c.nom_cat AS Categorie, r.nom AS 'Nom Candidat', r.nombre_vote AS 'Votes totaux'
                FROM resultats r 
                JOIN categorie c ON c.id_cat = r.id_cat
                GROUP BY c.nom_cat
                ORDER BY r.nombre_vote DESC";

        $stmt = $pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    }

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
