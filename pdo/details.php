<?php
try {
    $mysqlClient = new PDO('mysql:host=localhost; dbname=gaulois_giacomo; charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],); // récupération de la base de donnée et définit la gestion et l'affichage des erreurs sous forme d'exceptions
    } catch (Exception $e) {
        die('Erreur : '. $e->getMessage()); // affiche un message si impossibilité de connexion à base de données
    } 
    
    if(isset($_GET['id'])) { // vérifie la présence de l'ID dans l'URL ($_GET)
        $id = $_GET['id'];
    } 

    $sqlQuery = "   SELECT *
                    FROM personnage 
                    INNER JOIN lieu ON personnage.id_lieu = lieu.id_lieu
                    INNER JOIN specialite ON personnage.id_specialite = specialite.id_specialite
                    where id_personnage = :id"; // intègre les paramètres de recherche SQL
    
    $personnageStatement = $mysqlClient->prepare($sqlQuery);  // récupère la variable $sqlQuery
    $personnageStatement -> bindParam(':id', $id);  // lie le paramètre id_personnage à l'ID contenue dans l'URL
    $personnageStatement -> execute();
    $personnage = $personnageStatement->fetch(PDO::FETCH_ASSOC); // récupère sous un tableau associatif la requête pour une seule ligne

    echo "Nom : ".$personnage['nom_personnage']." <br> Lieu d'habitation : ".$personnage['nom_lieu']." <br> Profession : ".$personnage['nom_specialite'];
    

    $batailleQuery = "  SELECT bataille.nom_bataille, bataille.date_bataille, prendre_casque.qte, (SELECT SUM(qte) FROM prendre_casque WHERE id_personnage = :id) AS total
                        FROM prendre_casque
                        INNER JOIN personnage ON prendre_casque.id_personnage = personnage.id_personnage
                        INNER JOIN bataille ON prendre_casque.id_bataille= bataille.id_bataille
                        where personnage.id_personnage = :id
                        ";
    $batailleStatement = $mysqlClient->prepare($batailleQuery);
    $batailleStatement -> bindParam(':id', $id);
    $batailleStatement -> execute();
    $batailles = $batailleStatement->fetchAll();
    ?>

<table border="1">
    <thead>
        <tr>
            <th scope="col">Bataille</th>
            <th scope="col">Date de la bataille</th>
            <th scope="col">Quantité de casque(s) ramassé(s)</th>
        </tr>
    <thead>
    <tbody>
<?php   if(empty($batailles)){
            echo "<tr><td colspan='3'>Le personnage n'a participé à aucune bataille</td></tr>";
            } else {
            foreach($batailles as $bataille){
                        echo "<tr>";
                        echo '<td>'. $bataille['nom_bataille'] .'</td>'; 
                        echo '<td>'. $bataille['date_bataille'] .'</td>';
                        echo '<td>'. $bataille['qte'] .'</td>';
                        echo '</tr>';
                }
                echo "<tr><td colspan='3'>Total des casques trouvés : ". $bataille['total']."</td></tr>";
            }              
            ?> 
    </tbody>
</table>

