# BUGFIX.md

**Data da correção:**
**Corrigido por:**

---

## O que era o bug

<!-- Descreva tecnicamente: arquivo, linha, qual era o problema -->
>  Em eNTREGAController.php
> 
>  Função public static function store 
> 
>  Linha 92
> 
>  A query não fazia qualquer validação do status da transportadora
> 
>  Antes: SELECT id FROM transportadoras WHERE id = ?
> 
>  Depois: SELECT id FROM transportadoras WHERE id = ? AND deleted_at IS NULL
> 
>  Mudança: AND deleted_at IS NULL
> 
>  Agora ocorre a validação da existência de uma data de deleção/inativação
> 
>  OBS: Essa abordagem partiu de uma inferência feita sobre o fato de haver apenas a data de deleção da transportadora sem a existência de um campo de status para essa data.
---

## Resposta para a Camila (Operações)

<!-- Escreva como se fosse uma mensagem para ela no chat do time.
     Sem código, sem termos técnicos.
     Explique: o que acontecia, o que foi corrigido,
     se as entregas cadastradas indevidamente precisam de alguma ação. -->

> Bom dia, Camila. Tudo bem? Sobre a sua queixa de fretes sendo criados com transportadoras inativas:
> Validamos internamente, esse tipo de inserção realmente era permitida, mas a correção já foi aplicada. Agora apenas transportadoras não inativas podem ser selecionadas em novos fretes. Importante ressaltar que essa correção é válida apenas para novos fretes. Peço que você revise o que foi criado entre a detecção da falha e a correção para evitarmos surpresas. Se precisar de mais alguma ajuda, nos informe.
> 
---

## Como reproduzir (antes da correção)

1. POST para a rota /entregas
2. id_transportadora de alguma transportadora inativa/deletada 
3. OBS: Nos testes que fiz, utilizei o id_transportadora 3, na ocasião

## Como verificar que está corrigido

1. Ao tentar realizar o cadastro de um frete com um id_transportadora com deleted_at, receber um alerta de erro
2. Resposta esperada: {"erro":"Transportadora não encontrada"}
