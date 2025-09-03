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

public function destacados() {
    global $pdo;

    try {
        // 1. WAR más alto
        $stmt_war = $pdo->query("
            SELECT nombre, CAST(war AS DECIMAL(4,2)) as war, lugar_nacimiento 
            FROM jugadores 
            WHERE war IS NOT NULL 
            ORDER BY war DESC 
            LIMIT 1
        ");
        $war = $stmt_war->fetch(PDO::FETCH_ASSOC);

        // 2. Mejor AVG
        $stmt_avg = $pdo->query("
            SELECT nombre, promedio_bateo, lugar_nacimiento 
            FROM jugadores 
            WHERE promedio_bateo IS NOT NULL 
            ORDER BY promedio_bateo DESC 
            LIMIT 1
        ");
        $avg = $stmt_avg->fetch(PDO::FETCH_ASSOC);

        // 3. Más apariciones en All-Star
        $stmt_all_star = $pdo->query("
            SELECT nombre, all_star_appearances, lugar_nacimiento 
            FROM jugadores 
            WHERE all_star_appearances IS NOT NULL 
            ORDER BY all_star_appearances DESC 
            LIMIT 1
        ");
        $all_star = $stmt_all_star->fetch(PDO::FETCH_ASSOC);

        // 4. Más jonrones
        $stmt_hr = $pdo->query("
            SELECT nombre, home_runs, lugar_nacimiento 
            FROM jugadores 
            WHERE home_runs IS NOT NULL 
            ORDER BY home_runs DESC 
            LIMIT 1
        ");
        $hr = $stmt_hr->fetch(PDO::FETCH_ASSOC);

        // Preparar respuesta
        $data = [
            'war_mas_alto' => [
                'valor' => $war ? $war['war'] : null,
                'jugador' => $war ? $war['nombre'] : 'N/A',
                'ciudad' => $war ? $war['lugar_nacimiento'] : 'N/A'
            ],
            'mejor_avg' => [
                'valor' => $avg ? number_format($avg['promedio_bateo'], 3) : '0.000',
                'jugador' => $avg ? $avg['nombre'] : 'N/A',
                'ciudad' => $avg ? $avg['lugar_nacimiento'] : 'N/A'
            ],
            'mas_all_star' => [
                'valor' => $all_star ? $all_star['all_star_appearances'] : 0,
                'jugador' => $all_star ? $all_star['nombre'] : 'N/A',
                'ciudad' => $all_star ? $all_star['lugar_nacimiento'] : 'N/A'
            ],
            'lider_jonrones' => [
                'valor' => $hr ? $hr['home_runs'] : 0,
                'jugador' => $hr ? $hr['nombre'] : 'N/A',
                'ciudad' => $hr ? $hr['lugar_nacimiento'] : 'N/A'
            ]
        ];

        // ✅ Imprimir el JSON (como hace resumen)
        echo json_encode($data);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener destacados: ' . $e->getMessage()]);
    }
}



}
