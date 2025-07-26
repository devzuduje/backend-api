<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\HotelRoom\CreateHotelRoomAction;
use App\Actions\V1\HotelRoom\DeleteHotelRoomAction;
use App\Actions\V1\HotelRoom\GetHotelRoomsAction;
use App\Actions\V1\HotelRoom\RestoreHotelRoomAction;
use App\Actions\V1\HotelRoom\UpdateHotelRoomAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\HotelRoom\IndexHotelRoomRequest;
use App\Http\Requests\V1\HotelRoom\StoreHotelRoomRequest;
use App\Http\Requests\V1\HotelRoom\UpdateHotelRoomRequest;
use App\Models\HotelRoom;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group('Gestión de Habitaciones de Hotel')]
#[Subgroup('CRUD de Habitaciones')]
class HotelRoomController extends Controller
{
    #[Endpoint(
        title: 'Listar habitaciones de hotel',
        description: 'Obtiene una lista paginada de habitaciones de hotel con opciones de filtrado por hotel, tipo de habitación, acomodación o cantidad mínima, incluyendo soporte para borrado lógico.'
    )]
    #[Authenticated]
    public function index(IndexHotelRoomRequest $request, GetHotelRoomsAction $action): JsonResponse
    {
        $filters = $request->validated();
        $perPage = data_get($filters, 'per_page', 15);

        $hotelRooms = $action($filters, $perPage);

        return response()->json([
            'success' => true,
            'data' => $hotelRooms,
        ]);
    }

    #[Endpoint(
        title: 'Crear habitación de hotel',
        description: 'Crea una nueva habitación de hotel en el sistema con la combinación única de hotel, tipo de habitación y acomodación.'
    )]
    #[Authenticated]
    public function store(StoreHotelRoomRequest $request, CreateHotelRoomAction $action): JsonResponse
    {
        $hotelRoom = $action($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Habitación de hotel creada exitosamente.',
            'data' => $hotelRoom,
        ], 201);
    }

    #[Endpoint(
        title: 'Mostrar habitación de hotel',
        description: 'Obtiene la información detallada de una habitación de hotel específica por su ID, incluyendo las relaciones con hotel, tipo de habitación y acomodación.'
    )]
    #[Authenticated]
    public function show(HotelRoom $hotelRoom): JsonResponse
    {
        $hotelRoom->load(['hotel', 'roomType', 'accommodation']);

        return response()->json([
            'success' => true,
            'data' => $hotelRoom,
        ]);
    }

    #[Endpoint(
        title: 'Actualizar habitación de hotel',
        description: 'Actualiza la información de una habitación de hotel existente. Solo se puede actualizar la cantidad de habitaciones.'
    )]
    #[Authenticated]
    public function update(UpdateHotelRoomRequest $request, HotelRoom $hotelRoom, UpdateHotelRoomAction $action): JsonResponse
    {
        $updatedHotelRoom = $action($hotelRoom, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Habitación de hotel actualizada exitosamente.',
            'data' => $updatedHotelRoom,
        ]);
    }

    #[Endpoint(
        title: 'Eliminar habitación de hotel',
        description: 'Elimina una habitación de hotel del sistema usando borrado lógico. La habitación puede ser restaurada posteriormente.'
    )]
    #[Authenticated]
    public function destroy(HotelRoom $hotelRoom, DeleteHotelRoomAction $action): JsonResponse
    {
        $action($hotelRoom);

        return response()->json([
            'success' => true,
            'message' => 'Habitación de hotel eliminada exitosamente.',
        ]);
    }

    #[Endpoint(
        title: 'Restaurar habitación de hotel',
        description: 'Restaura una habitación de hotel previamente eliminada (borrado lógico).'
    )]
    #[Authenticated]
    public function restore(int $hotelRoomId, RestoreHotelRoomAction $action): JsonResponse
    {
        $hotelRoom = $action($hotelRoomId);

        return response()->json([
            'success' => true,
            'message' => 'Habitación de hotel restaurada exitosamente.',
            'data' => $hotelRoom,
        ]);
    }
}
