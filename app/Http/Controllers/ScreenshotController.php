<?php

namespace App\Http\Controllers;

use App\Character;
use App\Http\Resources\LogResource;
use App\Log;
use App\PanelLog;
use App\Player;
use App\Screenshot;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ScreenshotController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function render(Request $request): Response
    {
        $query = Screenshot::query()->orderByDesc('created_at');

        $page = Paginator::resolveCurrentPage('page');

        $query->select(['id', 'steam_identifier', 'filename', 'note', 'created_at']);
        $query->limit(20)->offset(($page - 1) * 20);

        $screenshots = $query->get()->toArray();

        return Inertia::render('Screenshots/Index', [
            'screenshots' => $screenshots,
            'links'       => $this->getPageUrls($page),
            'playerMap'   => Player::fetchSteamPlayerNameMap($screenshots, ['steam_identifier']),
            'page'        => $page,
        ]);
    }

    /**
     * All Anti-Cheat screenshots.
     *
     * @param Request $request
     * @return Response
     */
    public function antiCheat(Request $request): Response
    {
        $page = Paginator::resolveCurrentPage('page');

        $system = DB::table('system_screenshots')
            ->orderByDesc('created_at')
            ->select(['character_id', 'url', 'details', 'created_at'])
            ->limit(20)->offset(($page - 1) * 20)
            ->get()->toArray();

        $characterIds = [];

        foreach ($system as $entry) {
            $characterId = $entry->character_id;

            if (!in_array($characterId, $characterIds)) {
                $characterIds[] = $characterId;
            }
        }

        $players = !empty($characterIds) ? Character::query()->select(['player_name', 'steam_identifier'])->whereIn('character_id', $characterIds)->groupBy('steam_identifier')->get()->toArray() : [];

        return Inertia::render('Screenshots/Index', [
            'screenshots' => $system,
            'links'       => $this->getPageUrls($page),
            'playerMap'   => Player::fetchSteamPlayerNameMap($players, ['steam_identifier']),
            'page'        => $page,
        ]);
    }

}
