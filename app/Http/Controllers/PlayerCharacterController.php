<?php

namespace App\Http\Controllers;

use App\Character;
use App\Http\Requests\CharacterUpdateRequest;
use App\Http\Resources\CharacterResource;
use App\Http\Resources\CharacterIndexResource;
use App\Http\Resources\PlayerResource;
use App\Motel;
use App\Player;
use App\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PlayerCharacterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $start = round(microtime(true) * 1000);

        $query = Character::query()->orderBy('first_name');

        // Filtering by cid.
        if ($cid = $request->input('character_id')) {
            $query->where('character_id', $cid);
        }

        // Filtering by name.
        if ($name = $request->input('name')) {
            if (Str::startsWith($name, '=')) {
                $name = Str::substr($name, 1);
                $query->where(DB::raw('CONCAT(first_name, \' \', last_name)'), $name);
            } else {
                $query->where(DB::raw('CONCAT(first_name, \' \', last_name)'), 'like', "%{$name}%");
            }
        }

        // Filtering by Vehicle Plate.
        if ($plate = $request->input('vehicle_plate')) {
            $query->whereHas('vehicles', function ($subQuery) use ($plate) {
                if (Str::startsWith($plate, '=')) {
                    $plate = Str::substr($plate, 1);
                    $subQuery->where('plate', $plate);
                } else {
                    $subQuery->where('plate', 'like', "%{$plate}%");
                }
            });
        }

        // Filtering by Phone Number.
        if ($phone = $request->input('phone')) {
            if (Str::startsWith($phone, '=')) {
                $phone = Str::substr($phone, 1);
                $query->where('phone_number', $phone);
            } else {
                $query->where('phone_number', 'like', "%{$phone}%");
            }
        }

        // Filtering by Job.
        if ($job = $request->input('job')) {
            if (Str::startsWith($phone, '=')) {
                $job = Str::substr($job, 1);
                $query->where(DB::raw('CONCAT(job_name, \' \', department_name, \' \', position_name)'), $job);
            } else {
                $query->where(DB::raw('CONCAT(job_name, \' \', department_name, \' \', position_name)'), 'like', "%{$job}%");
            }
        }

        $query->select([
            'character_id', 'steam_identifier', 'first_name', 'last_name', 'gender', 'job_name',
            'department_name', 'position_name', 'phone_number',
        ]);

        $characters = CharacterIndexResource::collection($query->paginate(15, [
            'id',
        ])->appends($request->query()));

        $end = round(microtime(true) * 1000);

        return Inertia::render('Characters/Index', [
            'characters' => $characters,
            'filters'    => $request->all(
                'cid',
                'name',
                'vehicle_plate',
                'phone',
                'job'
            ),
            'time'       => $end - $start,
            'playerMap'  => Player::fetchSteamPlayerNameMap($characters->toArray($request), 'steamIdentifier'),
        ]);
    }

    /**
     * Display the specified resource for editing.
     *
     * @param Player $player
     * @param Character $character
     * @return Response
     */
    public function edit(Player $player, Character $character): Response
    {
        $motels = Motel::query()->where('cid', $character->character_id)->get()->sortBy(['motel', 'room_id']);

        return Inertia::render('Players/Characters/Edit', [
            'player'    => new PlayerResource($player),
            'character' => new CharacterResource($character),
            'motels'    => $motels->toArray(),
        ]);
    }

    /**
     * Updates the specified resource.
     *
     * @param Player $player
     * @param Character $character
     * @param CharacterUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(Player $player, Character $character, CharacterUpdateRequest $request)
    {
        $data = $request->validated();

        if (!empty($data['date_of_birth'])) {
            $time = strtotime($data['date_of_birth']);
            if (!$time) {
                return back()->with('error', 'Invalid date of birth');
            }

            $data['date_of_birth'] = date('Y-m-d', $time);
        }

        $character->update($data);
        return back()->with('success', 'Character was successfully updated.');
    }

}
