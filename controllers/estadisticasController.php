<?php
// controllers/estadisticasController.php

require_once __DIR__ . '/../config/database.php';

class EstadisticasController
{

    // GET /api/estadisticas/top-war
    public function topWar()
    {
        global $pdo;
        try {
            $stmt = $pdo->query("
                SELECT * 
                FROM jugadores 
                WHERE war >=30
                ORDER BY CAST(war AS DECIMAL(4,2)) DESC 
 
            ");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rows);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al obtener top WAR: ' . $e->getMessage()]);
        }
    }

    // GET /api/estadisticas/top-avg
    public function topAvg()
    {
        global $pdo;
        try {
            $stmt = $pdo->query("
                SELECT 
                    *
                FROM jugadores 
                WHERE promedio_bateo >=0.280 and veces_al_bate>100
                ORDER BY CAST(promedio_bateo AS DECIMAL(4,3)) DESC;
            ");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rows);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al obtener top AVG: ' . $e->getMessage()]);
        }
    }

    // GET /api/estadisticas/top-hr


    // GET /api/estadisticas/top-dobles

    // GET /api/estadisticas/top-dobles


    // GET /api/estadisticas/top-triples

    // GET /api/estadisticas/top-ops


    // GET /api/estadisticas/top-rc (Runs Created)


    // GET /api/estadisticas/top-iso (Isolated Power)


    // GET /api/estadisticas/top-bb (Walk Percentage)


    // GET /api/estadisticas/top-k (Strikeout Percentage)


    // GET /api/estadisticas/top-xbh (Extra-Base Hits)


    // GET /api/estadisticas/top-sb (Stolen Base Percentage)




    // GET /api/estadisticas/top-tb (Total Bases)


    // GET /api/estadisticas/top-allstar

    // GET /api/mapa/ciudades


    // GET /api/estadisticas/resumen
    public function resumen()
    {
        global $pdo;
        try {
            $stmt = $pdo->query("
                SELECT 
                    COUNT(*) as total_jugadores,
                    SUM(home_runs) as total_hr,
                    AVG(CAST(war AS DECIMAL(4,2))) as avg_war,
                    MAX(CAST(war AS DECIMAL(4,2))) as max_war,
                    (SELECT nombre FROM jugadores ORDER BY CAST(war AS DECIMAL(4,2)) DESC LIMIT 1) as mejor_war
                FROM jugadores
            ");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($row);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al obtener resumen: ' . $e->getMessage()]);
        }
    }
}
