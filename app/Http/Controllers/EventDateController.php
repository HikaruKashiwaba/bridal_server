<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventDate;

class EventDateController extends Controller
{
    public function getEventDate(Request $request) {
        $fair_id_array = $request->fair_id_array;

        for ($i = 0; $i < count($fair_id_array); $i++) {
            $event_date = new EventDate;
            $event_date = EventDate::where('fair_id', $fair_id_array[$i])->get(['date']);
            $fair_date_information_array = [];
            $fair_date_information_array['fair_id'] = $fair_id_array[$i];
            $fair_date_information_array['event_date'] = $event_date;
            $fair_date_array[$i] = $fair_date_information_array;
        }
        return response()->json($fair_date_array, 200);
    }
}
