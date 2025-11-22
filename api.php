<?php
                          session_start();   
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
                            
                            if (isset($_POST['participantName']) && isset($_POST['participantClass']) && isset($_POST['participantImage'])) {
                                $nom = $_POST['participantName'];
                                $classe = $_POST['participantClass'];
                                $image = $_POST['participantImage'];
                                $sql = " INSERT INTO candidat (nom, classe, image_cand) VALUES (:nom, :classe, :image)";

                            }else if(isset($_POST['categoryName'])){
                                $nom_cat = $_POST['categoryName']; 
                                $sql = " INSERT INTO candidat (nom) VALUES (:nom)";

                            }else if(isset($_POST['results-link'])){
                                $sql = "SELECT c.nom_cat AS Categorie, r.nom AS 'Nom Candidat', r.nombre_vote AS 'Votes totaux'
                            FROM resultats r JOIN categorie c ON c.id_cat = r.id_cat
                            GROUP BY c.nom_cat
                            ORDER BY r.nombre_vote DESC";

                            }
                            /*$sql = "SELECT * FROM candidat";
                            
                            $sql = " INSERT INTO categorie (nom_cat) VALUES (:nom_cat)";

                            
                            $sql = "SELECT SUM(montant)
                            FROM vote WHERE operateur = 'MTN MoMo'";

                            $sql = "SELECT SUM(montant) FROM vote
                                    WHERE operateur = 'Orange Money'";

                            $sql = " SELECT numero_tel AS Numero, montant AS Montant, montant/100 AS Votes, date_vote as 'Effectue', nom AS Candidat 
                            FROM vote";
                            */

                            $stmt = $pdo->query($sql);
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            echo json_encode($result);

?>
