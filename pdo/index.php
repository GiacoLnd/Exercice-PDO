<?php
try {
$mysqlClient = new PDO('mysql:host=localhost; dbname=gaulois_giacomo; charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],);
} catch (Exception $e) {
    die('Erreur : '. $e->getMessage());
}

$sqlQuery = "   SELECT personnage.id_personnage, personnage.nom_personnage, lieu.nom_lieu, specialite.nom_specialite 
                FROM personnage
                INNER JOIN lieu ON personnage.id_lieu = lieu.id_lieu
                INNER JOIN specialite ON personnage.id_specialite = specialite.id_specialite
                ORDER BY personnage.nom_personnage ASC";
$personnageStatement = $mysqlClient->prepare($sqlQuery);
$personnageStatement -> execute();
$personnages = $personnageStatement->fetchAll();?>

<table border="1">
    <thead>
        <tr>
            <th scope="col">nom du villageois</th>
            <th scope="col">nom de sa spécialité</th>
            <th scope="col">nom du lieu d'habitation</th>
        </tr>
    <thead>
    <tbody>
<?php
foreach($personnages as $personnage){
    echo "<tr>";
    echo '<td><a href="details.php?id=' . $personnage['id_personnage'] . '">' . $personnage['nom_personnage'] . '</a></td>'; 
    echo '<td>'. $personnage['nom_specialite'].'</td>';
    echo '<td>'. $personnage['nom_lieu'].'</td>';
    echo '</tr>';
    }?> 
    </tbody>
</table>