<?php

namespace App\Http\Resources;

use App\Models\AccountLaunch;

use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Extract extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "uuid" => $this->uuid,
            "user" => $this->userToArray($this->user),
            "account_number" => $this->account_number,
            "account_holder" => $this->account_holder,
            "balance" => $this->balance,
            "status" => $this->status,
            "extract" => $this->getExtract($request),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }

    private function userToArray($user)
    {
        return array_merge($user->toArray(), [ 'member_class' => $user->member_class ]);
    }

    private function getExtract(\Illuminate\Http\Request $request)
    {
        $first_date = $request->get('init', date('Y-m-01 00:00:00'));
        $last_date = $request->get('finish', date('Y-m-t 23:59:59'));
        $type = $request->get('type');

        if (strlen($first_date) <= 10) {
            $first_date = $first_date . " 00:00:00";
        }

        if (strlen($last_date) <= 10) {
            $last_date = $last_date . " 23:59:59";
        }

        $extract = AccountLaunch::select()
            ->with('get_created_by')
            ->with('event')
            ->where('account_number', $this->account_number)
            ->whereBetween('created_at', [ dateBrToDatabase(substr($first_date, 0, 10)) . ' 00:00:00', dateBrToDatabase(substr($last_date, 0, 10)) . ' 23:59:59' ])
            ->orderBy('created_at', 'desc');

        if($type && $type != 'all')
            $extract->where('type', $type);

        $extract = $extract->get();

        $extract = $extract->toArray();

        foreach($extract as $i_extract => $this_extract){
            if (! empty($this_extract['get_created_by'])) {
                $extract[$i_extract]['get_created_by']['photo_url'] = UserPhoto::get($this_extract['get_created_by']['photo']);
            }

            $extract[$i_extract]['created_at_formatted'] = (new Carbon($this_extract['created_at']))->format('d/m/Y H:i:s');
        }


        return $extract;
    }
}