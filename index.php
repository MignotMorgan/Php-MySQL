<?php
try
{
  $bdd = new PDO('mysql:host=localhost;dbname=imbd;charset=utf8', 'root', 'user');
}
catch (Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>requêtes SQL</title>
  </head>
  <body>
    <h1>Affiche uniquement les films de "Cristopher Nolan" :</h1>
    <?php
        $req = $bdd->prepare('SELECT *
                              FROM authors
                              INNER JOIN movies
                              ON movies.id_author = authors.id
                              WHERE authors.id = :id
                              ');
        $req->execute(array("id" => 1));
        $tab = $req->fetchAll(PDO::FETCH_ASSOC);

        echo "<pre>";
        print_r($tab);
        echo "</pre>";
     ?>


     <h1>Avec l'aide de l'image ci-dessous, utilise un "LEFT JOIN" et affiche TOUTES les valeurs de la table authors.</h1>
     <?php
         $req = $bdd->prepare('SELECT *
                                FROM authors
                                LEFT JOIN movies
                                ON movies.id_author = authors.id
                                ');
         $req->execute();
         $tab = $req->fetchAll(PDO::FETCH_ASSOC);

         echo "<pre>";
         print_r($tab);
         echo "</pre>";
     ?>

     <h1>Affiche TOUTES les valeurs qui se trouvent dans la table authors et movies</h1>
     <?php
     $req = $bdd->prepare('SELECT *
                            FROM authors
                            LEFT JOIN movies
                            ON movies.id_author = authors.id
                            UNION ALL
                            SELECT *
                            FROM authors
                            RIGHT JOIN movies
                            ON movies.id_author = authors.id
                            ');

         $req->execute();
         $tab = $req->fetchAll(PDO::FETCH_ASSOC);

         echo "<pre>";
         print_r($tab);
         echo "</pre>";
     ?>
     <h1>Affiche uniquement les valeurs qui n'ont pas de correspondances entres les tables. (Donc le résultat devrait être "Tintin en Amérique" et "Yves Lavandier".)</h1>
     <?php
         $req = $bdd->prepare('SELECT *
                                FROM authors
                                LEFT JOIN movies
                                ON movies.id_author = authors.id
                                WHERE movies.id_author IS NULL
                                UNION ALL
                                SELECT *
                                FROM authors
                                RIGHT JOIN movies
                                ON movies.id_author = authors.id
                                WHERE authors.id IS NULL
                              ');
         $req->execute();
         $tab = $req->fetchAll(PDO::FETCH_ASSOC);

         echo "<pre>";
         print_r($tab);
         echo "</pre>";
     ?>

     <h1>Modifie dans la rangé "Tintin en Amérique" en "Tintin et le secret de la licorne" et modifie l'id de l'auteur avec celui de "Steven Spielberg".</h1>
     <?php
     /*Requête SQL qui fonctionne*/
    //  $req = $bdd->prepare('SELECT *
    //                         FROM authors
    //                         WHERE lastname = "Spielberg"
    //                         AND firstname = "Steven"
    //                       ');
    //  $req->execute();
    //  $ligne = $req->fetch();
    //  $id = $ligne["id"];
    //
    //  $req = $bdd->prepare('UPDATE movies
    //                         SET id_author = :id,
    //                         title = "Tintin et le secret de la licorne"
    //                         WHERE title = "Tintin en amérique";
    //                       ');
    // $req->execute(array("id" => $id));
    /*Fin Requête SQL*/
    /*##########################################################""*/

    $req = $bdd->prepare('UPDATE movies
                          AS m
                          INNER JOIN authors
                          AS a
                          ON a.lastname = "Spielberg"
                          AND a.firstname = "Steven"
                          AND m.title = "Tintin en amérique"
                          SET m.id_author = a.id,
                          m.title = "Tintin et le secret de la licorne"
                          WHERE m.title = "Tintin en amérique"
                         ');
    $req->execute();
    $tab = $req->fetchAll(PDO::FETCH_ASSOC);

     echo "<pre>";
     print_r($tab);
     echo "</pre>";
      ?>

  </body>
</html>
