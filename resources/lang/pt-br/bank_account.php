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
        ]
    ],

    'not-found' =>  'Conta não encontrada',

    'error-transfer' => 'Ocorreu um erro ao finalizar a transferência!',
    'error-launch-debit' => 'Ocorreu um erro ao finalizar o lançamento!',
    'error-negative-balance' => 'Não é permitido saldo negativo na conta do clube!',
    'error-negative-balance-2' => 'Não há saldo suficiente na Conta do Clube! Para efetuar o lançamento primeiro insira créditos na Conta do Clube.',

    'error-extract-administrator' => 'Administrador não possui extrato bancário!',

    'errors' => [
        'no-have-account' => 'Você não possuí uma conta bancária!',
        'bank-account-not-found' => 'Conta bancária não localizada!',
        'member-not-have-account' => 'Membro não possuí conta ou está inativa/bloqueada!'
    ]
];
