<?php

return [

    'type' => [
        'club' => 'Club',
        'member' => 'Member'
    ],

    'history' => [
        'data' => [
            'operation' => [
                'transfer' => 'Transfer',
                'charge' => 'Load Account',
                'discount' => 'Discount'
            ],
            'operation_type' => [
                'credit' => 'Credit',
                'debit' => 'Debit'
            ]
        ]
    ],

    'not-found' =>  'Account not found',

    'error-launch-credit' => 'An error occurred while finalizing the release',
    'error-launch-debit' => 'An error occurred while finalizing the release',
    'error-negative-balance' => 'Club account negative balance is not allowed',
    'error-negative-balance-2' => 'There is not enough balance in the Club Account! To launch the entry, first enter credits in the Club Account.',

    'error-extract-administrator' => 'Administrator does not have bank statement!',

    'errors' => [
        'no-have-account' => 'You don\'t have a bank account!',
        'bank-account-not-found' => 'Bank account not found!'
    ]
];
