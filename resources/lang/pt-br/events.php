<?php

return [
    'not-found' =>  'Evento não encontrado',

    'success-update'    =>  'Evento atualizado com sucesso',
    'success-create'    =>  'Evento criado com sucesso',

    'error-create'  =>  'Erro ao criar evento - :error',
    'error-update'  =>  "Erro ao atualizar evento - :error",

    'error-delete-not-draft' => 'É possível remover somente eventos em rascunho',
    
    'success-start-event' => 'Evento :name inicializado com sucesso',
    'success-cancel-event' => 'Evento :name cancelado com sucesso',
    'error-start-event' => "Não foi possível inicializar o evento :name.\nNecessário preencher os seguintes campos: \n\n:fields",

    'success-subscribe-event' => 'Inscrição efetuada com sucesso no evento :name',
    'error-subscribe-event' => [
        'status' => 'Evento :name não está ativo',
        'start_subscription_date' => 'A data de inscrição para membros :member_class_name se inicia em :date',
        'without_balance' => 'Saldo insuficiente para inscrição no valor de :value no evento :name',
        'already-subscribted' => 'Você já está inscrito no evento :name',

        'max-participants' => 'Limite de participantes já atingido',
        'max-vehicles' => 'Limite de veículos já atingido',
        'max-companions' => 'Quantidade de acompanhantes informado ultrapassa o limite permitido',

        'generic' => 'Ocorreu um erro ao se inscrever no evento :name - :error',
    ],
    
    'success-cancel-event' => 'Evento :name cancelado com sucesso',
    'error-cancel-event' => [
        'status' => 'Evento :name precisa estar ativo para ser cancelado',
        'generic' => 'Ocorreu um erro ao cancelar o evento :name - :error',
    ],

    'fields' => [
        'name' => 'Nome',
        'cover_picture' => 'Capa do Evento',
        'description' => 'Descrição',
        'address' => 'Endereço',
        'meeting_point' => 'Ponto de Encontro',
        'date' => 'Data do Evento',
        'date_limit' => 'Data Limite de Inscrição',
        'start_time' => 'Hora de Início',
        'end_time' => 'Hora de Término',
        'max_vehicles' => 'Número Máximo de Veículos',
        'max_participants' => 'Número Máximo de Participantes',
        'max_companions' => 'Número Máximo de Acompanhantes',
        'status' => 'Status',

        'remove-picture' => 'Remover',
 
        'class' => [
            'title' => 'Classe :CLASS',

            'start_subscription_date' => 'Data Início de Inscrição',
            'vehicle_value' => 'Valor por Veículo',
            'participant_value' => 'Valor por Participante',
            'companion_value' => 'Valor por Acompanhante',
        ],
    ],
];
