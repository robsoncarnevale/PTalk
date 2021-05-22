<?php

return [
    'not-found' =>  'Member not found',

    'success-update'    =>  'Event updated successfully',
    'success-create'    =>  'Event created successfully',

    'error-create'  =>  "Error creating event - :error",
    'error-update'  =>  "Error updating event - :error",

    'error-delete-not-draft' => 'You can only remove draft events',

    'success-start-event' => 'Event :name initialized with success',
    'success-cancel-event' => 'Event :name cancelled with success',
    'error-start-event' => "Não foi possível inicializar o evento :name.\nNecessário preencher os seguintes campos: \n\n:fields",

    'fields' => [
        'name' => 'Name',
        'cover_picture' => 'Event Cover',
        'description' => 'Description',
        'address' => 'Address',
        'meeting_point' => 'Meeting point',
        'date' => 'Event Date',
        'date_limit' => 'Deadline for Registration',
        'start_time' => 'Start Time',
        'end_time' => 'End Time',
        'max_vehicles' => 'Maximum Number of Vehicles',
        'max_participants' => 'Maximum Number of Participants',
        'max_companions' => 'Maximum Number of Companions',
        'status' => 'Status',

        'remove-picture' => 'Remove',

        'class' => [
            'title' => ':CLASS Class',

            'start_subscription_date' => 'Registration Start Date',
            'vehicle_value' => 'Value per Vehicle',
            'participant_value' => 'Value per Participant',
            'companion_value' => 'Value per Companion',
        ],
    ],
];
