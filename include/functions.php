<?php
function availibilityCheck($pdo,$nazwa_sali,$data,$start,$stop){
    $inner=0;
    try {
        $stmt = $pdo->prepare(' SELECT nazwa_sali,czas_start,czas_stop FROM rezerwacje WHERE data=:data and NOT ((:start BETWEEN `czas_start` AND `czas_stop`) OR :stop BETWEEN `czas_start` AND `czas_stop`);');

        $stmt->bindValue(':data', $data, PDO::PARAM_STR);
        $stmt->bindValue(':start', $start, PDO::PARAM_STR);
        $stmt->bindValue(':stop', $stop, PDO::PARAM_STR);
        $stmt->execute();
        if($inner){
            $tresc='<select id=filmyWith name="filmyWith" size="8">'.PHP_EOL;
        }
        foreach ($stmt as $row){
//            $tresc.="<option value='".$row['nazwa_sali']."' >".$row['czas_start']." ".$row['czas_stop']."</option>".PHP_EOL;
            $tresc.="<option value='".$row['nazwa_sali']."' >".$row['nazwa_sali']."</option>".PHP_EOL;
        }
        if($inner){
            $tresc.='</select><br/>'.PHP_EOL;
        }
        $stmt->closeCursor();
        return  $tresc;
    } catch (PDOException $e){
        echo 'Błąd odczytu z bazy: ' . $e->getMessage();
    }
}



function addMovieToActor($pdo,$id_aktora,$id_filmu){
    try {
        $stmt = $pdo->prepare(' INSERT INTO obsada
                                (id_aktora,id_filmu)
                                VALUES (:id_aktora,:id_filmu);');
        $stmt->bindValue(':id_aktora', $id_aktora, PDO::PARAM_INT);
        $stmt->bindValue(':id_filmu', $id_filmu, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e){
        echo 'Błąd dodawania aktora do filmu: ' . $e->getMessage();
    }
}

function removeActorFromMovie($pdo,$id_aktora,$id_filmu){
    try {
        $stmt = $pdo->prepare(' DELETE FROM obsada
                                WHERE id_aktora=:id_aktora
                                AND id_filmu=:id_filmu;');
        $stmt->bindValue(':id_aktora', $id_aktora, PDO::PARAM_INT);
        $stmt->bindValue(':id_filmu', $id_filmu, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e){
        echo 'Błąd usuwania aktora z filmu: ' . $e->getMessage();
    }
}


?>