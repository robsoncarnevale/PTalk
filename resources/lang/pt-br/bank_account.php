<?php

return [

    'type' => [
        'club' => 'Clube',
        'member' => 'Membro'
    ],

    'history' => [
        'data' => [
            'operation' => [
                'transfer' => 'Transferência',
                'charge' => 'Carregar Conta',
                'discount' => 'Desconto'
            ],
            'operation_type' => [
                'credit' => 'Crédito',
                'debit' => 'Débito'
            ]
        ],
        'description-charge' => 'Carregamento de conta'
    ],

    'not-found' =>  'Conta não encontrada',

    'error-generic-bank-account' => 'Erro ao realizar a operação',
    'error-transfer' => 'Ocorreu um erro ao finalizar a transferência!',
    'error-launch-debit' => 'Ocorreu um erro ao finalizar o lançamento!',
    'error-negative-balance' => 'Não é permitido saldo negativo na conta do clube!',
    'error-negative-balance-2' => 'Não há saldo suficiente na Conta do Clube! Para efetuar o lançamento primeiro insira créditos na Conta do Clube.',

    'error-extract-administrator' => 'Administrador não possui extrato bancário!',

    'errors' => [
        'no-have-account' => 'Você não possuí uma conta bancária!',
        'bank-account-not-found' => 'Conta bancária não localizada!',
        'bank-account-not-found-origin' => 'Conta de origem não localizada!',
        'insufficient-fund' => 'Saldo insuficiente!',
        'transfer-my' => 'Não pode realizar uma transferência para si mesmo!',
        'member-not-have-account' => 'Membro não possuí conta ou está inativa/bloqueada!',
        'user-destiny' => 'Usuário de destino não localizado!',
        'min-transfer' => 'Valor mínimo excedido!',
        'opertaion-type-not-found' => 'Tipo de Operação inválida',
        'reversal-charge' => 'Estorno de Carregamento de Conta'
    ],

    'success-transfer' => 'Transferência realizada com sucesso!',
    'success-charge' => 'Carregamento de conta realizado'
];
