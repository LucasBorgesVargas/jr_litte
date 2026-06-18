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

        $sql = '
            SELECT id, codigo, descricao, ativo
            FROM motivos_nao_conformidade
            WHERE ' . implode(' AND ', $where) . '
            ORDER BY id DESC
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

    public static function listarNaoConformidades(array $params): void
    {
        $db    = Database::connection();
        $binds = [$params['id']];

        $sql = '
            SELECT id, id_entrega, id_motivo, descricao, created_at  FROM nao_conformidades WHERE id_entrega = ?
        ';

        $stmt = $db->prepare($sql);
        $stmt->execute($binds);
        $rows = $stmt->fetchAll();

        json(array_map(fn($r) => [
            'id'         => (int) $r['id'],
            'id_entrega' => (int) $r['id_entrega'],
            'id_motivo'  => (int) $r['id_motivo'],
            'descricao'  => $r['descricao'],
            'created_at' => $r['created_at'],
        ], $rows));
    }

    public static function store(array $params): void
    {
        $data = body();
        $db   = Database::connection();

        $stmt = $db->prepare('SELECT id FROM entregas WHERE id = ?');
        $stmt->execute([$params['id']]);
        if (!$stmt->fetch()) {
            json(['erro' => 'Entrega não encontrada'], 404);
        }

        if (empty($data['id_motivo'])) {
            json(['erro' => 'Campo obrigatório: id_motivo'], 422);
        }

        $stmt = $db->prepare('SELECT id FROM motivos_nao_conformidade WHERE id = ?');
        $stmt->execute([$data['id_motivo']]);
        if (!$stmt->fetch()) {
            json(['erro' => 'Motivo não encontrado'], 404);
        }

        $descricao = !empty($data['descricao']) ? $data['descricao'] : null;

        $stmt = $db->prepare('INSERT INTO nao_conformidades (id_entrega, id_motivo, descricao) VALUES (?, ?, ?)');
        $stmt->execute([$params['id'], $data['id_motivo'], $descricao]);


        $id = $db->lastInsertId();

        $stmt = $db->prepare('SELECT * FROM nao_conformidades WHERE id = ?');
        $stmt->execute([$id]);
        $nc = $stmt->fetch();

        json([
            'id'         => (int) $nc['id'],
            'id_entrega' => (int) $nc['id_entrega'],
            'id_motivo'  => (int) $nc['id_motivo'],
            'descricao'  => $nc['descricao'],
            'created_at' => $nc['created_at'],
        ], 201);


    }


}
