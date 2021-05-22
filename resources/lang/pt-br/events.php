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
