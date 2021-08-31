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
    'error-start-event' => "The :name event could not be initialized.\Need to fill in the following fields: \n\n:fields",

    'success-subscrive-event' => 'Successful registration for the :name event',
    'error-subscribe-event' => [
        'status' => ':name event is not active',
        'start_subscription_date' => 'The registration date for :member_class_name members starts with :date',
        'without_balance' => 'Insufficient balance for enrollment in the :value of the :name event',
        'already-subscribted' => 'You are already registered for the :name event',

        'max-participants' => 'Participant limit already reached',
        'max-vehicles' => 'Vehicle limit already reached',
        'max-companions' => 'Number of companions informed exceeds the allowed limit',

        'generic' => 'An error occurred while registering for the :name event -: error',
    ],

    'error-unsubscribe-event' => [
        'not-found' => 'Subscription not found',
        'unsubscribe_date_limit' => 'Cancellation deadline is until :date',
    ],

    'success-cancel-event' => ':event event successfully canceled',
    'error-cancel-event' => [
        'status' => ':name event must be active to be canceled',
        'generic' => 'An error occurred while canceling :name event - :error',
    ],


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
