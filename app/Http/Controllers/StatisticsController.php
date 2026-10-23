<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        $byPlace = DB::table('places')
            ->leftJoin('items', 'items.place_id', '=', 'places.id')
            ->select('places.name')
            ->selectRaw('COUNT(items.id) as total')
            ->groupBy('places.id', 'places.name')
            ->orderBy('places.name')
            ->get();

        $monthExpression = match (DB::getDriverName()) {
            'mysql', 'mariadb' => "DATE_FORMAT(found_on, '%Y-%m')",
            'pgsql' => "TO_CHAR(found_on, 'YYYY-MM')",
            default => "strftime('%Y-%m', found_on)",
        };

        $byMonth = DB::table('items')
            ->selectRaw("{$monthExpression} as month, COUNT(*) as total")
            ->groupByRaw($monthExpression)
            ->orderBy('month')
            ->get();

        $maxPlace = max(1, (int) $byPlace->max('total'));
        $maxMonth = max(1, (int) $byMonth->max('total'));

        return view('statistics.index', compact('byPlace', 'byMonth', 'maxPlace', 'maxMonth'));
    }
}
