<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\EventHistory;
use App\Models\User;


use Storage;

/**
 * Event Model
 *
 * @author Davi Souto
 * @since 20/08/2020
 */
class Event extends Model
{
    protected $table = 'events';

    const ACTIVE_STATUS = 'active';
    const INACTIVE_STATUS = 'inactive';
    const DRAFT_STATUS = 'draft';
    const CLOSED_STATUS = 'closed';
    const REALIZED_STATUS = 'realized';
    const CANCELLED_STATUS = 'cancelled';


    protected $fillable = [
        'name',
        'description',
        'address',
        'meeting_point',
        'date',
        'date_limit',
        'start_time',
        'end_time',
        'max_vehicles',
        'max_participants',
        'max_companions',
        'status',
    ];

    protected $hidden = [
        'club_code',
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    /**
     * Get event history
     */
    public function history()
    {
        return $this->hasMany('App\Models\EventHistory');
    }

    /////////////////////////

    /**
     * Upload event cover picture
     * 
     * @param \Illuminate\Http\File $file
     * @author Davi Souto
     * @since 17/05/2021
     */
    public function upload($file)
    {
        $upload_photo = Storage::disk('images')->putFile(getClubCode().'/events', $file);

        if ($upload_photo)
        {
            if (! empty($this->cover_picture) && Storage::disk('images')->exists($this->cover_picture)) {
                Storage::disk('images')->delete($this->cover_picture);
            }
            
            $this->cover_picture = $upload_photo;
        }

        return $this;
    }

    /**
     * Save event history 
     */
    public function saveHistory($old_data)
    {
        $history = new EventHistory();
        $history->club_code = getClubCode();
        $history->effected_by = User::getAuthenticatedUserId();
        $history->event_id = $this->id;

        // First register
        if (! $old_data) {
            $history->resume = json_encode([
                'event' => $this->toArray(),
            ]);

            $history->status = Event::DRAFT_STATUS;
        } else {
            $actual_data_event = $this->toArray();
            $history->resume = json_encode([
                'event' => array_diff($actual_data_event, $old_data['event']),
                'old_event' => $old_data['event'],
            ]);

            $history->status = $this->status;
        }

        $history->save();
    }
}
