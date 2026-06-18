<?php

namespace App\Controllers;

use App\Database;

class QualidadeController
{
    public static function index(array $params): void
    {
        $db    = Database::connection();
        $where = ['ativo=1'];
        $binds = [];


        if (!empty($_GET['id'])) {
            $where[] = 'e.id = ?';
            $binds[] = $_GET['id'];
        }

        if (!empty($_GET['codigo'])) {
            $where[] = 'e.codigo = ?';
            $binds[] = $_GET['codigo'];
        }

        if (!empty($_GET['descricao'])) {

            $binds[] = $_GET['descricao'];
        }


        $sql = '
            SELECT e.id, e.codigo, e.descricao, e.ativo
            FROM motivos_nao_conformidade e
            WHERE ' . implode(' AND ', $where) . '
            ORDER BY e.id DESC
        ';

        $stmt = $db->prepare($sql);
        $stmt->execute($binds);
        $rows = $stmt->fetchAll();

        json(array_map(fn($r) => [
            'id'         => (int) $r['id'],
            'codigo'     => $r['codigo'],
            'descricao'     => $r['descricao'],
        ], $rows));
    }


}
