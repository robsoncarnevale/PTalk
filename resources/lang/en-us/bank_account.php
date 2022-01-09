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
        ],
        'description-charge' => 'Account loading'
    ],

    'not-found' =>  'Account not found',

    'error-generic-bank-account' => 'Error performing the operation',
    'error-transfer' => 'An error occurred while finalizing the transfer',
    'error-launch-debit' => 'An error occurred while finalizing the release',
    'error-negative-balance' => 'Club account negative balance is not allowed',
    'error-negative-balance-2' => 'There is not enough balance in the Club Account! To launch the entry, first enter credits in the Club Account.',

    'error-extract-administrator' => 'Administrator does not have bank statement!',

    'errors' => [
        'no-have-account' => 'You don\'t have a bank account!',
        'bank-account-not-found' => 'Bank account not found!',
        'bank-account-not-found-origin' => 'Origin account not found!',
        'insufficient-fund' => 'Insufficient funds!',
        'transfer-my' => 'You cannot perform a transfer for yourself!',
        'member-not-have-account' => 'Member does not have an account or is inactive/blocked!',
        'user-destiny' => 'Target User Not Found!',
        'min-transfer' => 'Minimum value exceeded!',
        'opertaion-type-not-found' => 'Operation type invalid',
        'reversal-charge' => 'Account Charge Reversal'
    ],

    'success-transfer' => 'Transfer successful!',
    'success-charge' => 'Account charge performed'
];
