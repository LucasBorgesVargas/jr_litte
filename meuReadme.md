[README.md](README.md)
Como rodar
# 1. Configure o ambiente
cp .env.example .env
# edite .env com suas credenciais MySQL

# 2. Instale as dependências
composer install

# 3. Crie as tabelas
vendor/bin/phinx migrate

# 4. Popule os dados iniciais
vendor/bin/phinx seed:run

# 5. Suba o servidor
php -S localhost:8000 public/index.php

Sistema atual
Endpoints disponíveis:

GET   /transportadoras

POST  /transportadoras

GET   /transportadoras/{id}

PATCH /transportadoras/{id}/desativar

PATCH /transportadoras/{id}/reativar

GET   /entregas

ex de resposta esperada:
>{
"id": 5,
"codigo": "BRD-2026-00002",
"status": "CRIADA",
"data_prazo": "0000-00-00",
"peso_kg": 100,
"volumes": 1,
"transportadora": "Cargas do Sul S.A.",
"destinatario": {
"nome": "João da Silva",
"cidade": "Porto Alegre",
"uf": "RS"
}
},

POST  /entregas

GET   /entregas/{id}

PATCH /entregas/{id}/status

GET /motivos-nao-conformidade

POST /entregas/{id}/nao-conformidades

GET /rastreamento/{id}

ex de resposta esperada:
>"id": 1,
"codigo": "BRD-2024-00001",
"status": "EM_TRANSITO",
"data_prazo": "2024-12-20",
"peso_kg": 12.5,
"volumes": 3,
"created_at": "2026-06-07 15:29:31",
"updated_at": "2026-06-07 15:29:31",
"transportadora": {
"id": 1,
"nome_fantasia": "Transportes Rápido Ltda"
},
"remetente": {
"id": 1,
"nome": "Indústria Alfa Ltda",
"cidade": "São Paulo",
"uf": "SP"
},
"destinatario": {
"id": 1,
"nome": "João da Silva",
"cidade": "Porto Alegre",
"uf": "RS"
},
"rastreamento": [
{
"id": 1,
"status": "CRIADA",
"descricao": "Entrega cadastrada no sistema",
"cidade": "São Paulo",
"uf": "SP",
"data": "2026-06-07 15:29:31"
},
{
"id": 2,
"status": "COLETADA",
"descricao": "Carga coletada no remetente",
"cidade": "São Paulo",
"uf": "SP",
"data": "2026-06-07 15:29:31"
},
{
"id": 3,
"status": "EM_TRANSITO",
"descricao": "Em rota para destino",
"cidade": "Curitiba",
"uf": "PR",
"data": "2026-06-07 15:29:31"
}
]

GET /entregas/{id}/nao-conformidades

ex de resoista esperada:
>    {
"id": 1,
"id_entrega": 1,
"id_motivo": 1,
"descricao": "teste",
"created_at": "2026-06-18 20:15:45"
},
{
"id": 2,
"id_entrega": 1,
"id_motivo": 1,
"descricao": "Caixa amassada",
"created_at": "2026-06-18 20:26:14"
}

Dados de seed disponíveis (use os IDs para testar):

3 transportadoras (2 ativas, 1 inativa)
2 remetentes
3 destinatários
3 entregas em status variados com histórico de ocorrências
6 motivos de não conformidade


## Decisões técnicas

- QualidadeController foi criado separado pois sua responsabilidade é diferente dos demais controllers — gerencia motivos e registros de não conformidade, não entregas ou transportadoras
- descricao em não conformidades é opcional — quando não enviado, é armazenado como null no banco
