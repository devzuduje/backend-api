<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\RoomType\CreateRoomTypeAction;
use App\Actions\V1\RoomType\DeleteRoomTypeAction;
use App\Actions\V1\RoomType\GetRoomTypesAction;
use App\Actions\V1\RoomType\RestoreRoomTypeAction;
use App\Actions\V1\RoomType\UpdateRoomTypeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\RoomType\IndexRoomTypeRequest;
use App\Http\Requests\V1\RoomType\StoreRoomTypeRequest;
use App\Http\Requests\V1\RoomType\UpdateRoomTypeRequest;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group('Gestión de Tipos de Habitación')]
#[Subgroup('CRUD de Tipos de Habitación')]
class RoomTypeController extends Controller
{
    #[Endpoint(
        title: 'Listar tipos de habitación',
        description: 'Obtiene una lista paginada de tipos de habitación con opciones de filtrado por nombre o código, incluyendo soporte para borrado lógico.'
    )]
    #[Authenticated]
    public function index(IndexRoomTypeRequest $request, GetRoomTypesAction $action): JsonResponse
    {
        $roomTypes = $action($request->validated());

        return response()->json([
            'success' => true,
            'data' => $roomTypes->items(),
            'meta' => [
                'current_page' => $roomTypes->currentPage(),
                'last_page' => $roomTypes->lastPage(),
                'per_page' => $roomTypes->perPage(),
                'total' => $roomTypes->total(),
            ],
        ]);
    }

    #[Endpoint(
        title: 'Crear tipo de habitación',
        description: 'Crea un nuevo tipo de habitación en el sistema con nombre y código únicos.'
    )]
    #[Authenticated]
    public function store(StoreRoomTypeRequest $request, CreateRoomTypeAction $action): JsonResponse
    {
        $roomType = $action($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tipo de habitación creado exitosamente.',
            'data' => $roomType,
        ], 201);
    }

    #[Endpoint(
        title: 'Mostrar tipo de habitación',
        description: 'Obtiene la información detallada de un tipo de habitación específico por su ID.'
    )]
    #[Authenticated]
    public function show(RoomType $roomType): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $roomType,
        ]);
    }

    #[Endpoint(
        title: 'Actualizar tipo de habitación',
        description: 'Actualiza la información de un tipo de habitación existente. Solo se actualizarán los campos proporcionados.'
    )]
    #[Authenticated]
    public function update(UpdateRoomTypeRequest $request, RoomType $roomType, UpdateRoomTypeAction $action): JsonResponse
    {
        $updatedRoomType = $action($roomType, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tipo de habitación actualizado exitosamente.',
            'data' => $updatedRoomType,
        ]);
    }

    #[Endpoint(
        title: 'Eliminar tipo de habitación',
        description: 'Elimina un tipo de habitación del sistema usando borrado lógico. El tipo de habitación puede ser restaurado posteriormente.'
    )]
    #[Authenticated]
    public function destroy(RoomType $roomType, DeleteRoomTypeAction $action): JsonResponse
    {
        $action($roomType);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de habitación eliminado exitosamente.',
        ]);
    }

    #[Endpoint(
        title: 'Restaurar tipo de habitación',
        description: 'Restaura un tipo de habitación previamente eliminado (borrado lógico).'
    )]
    #[Authenticated]
    public function restore(int $roomTypeId, RestoreRoomTypeAction $action): JsonResponse
    {
        $roomType = $action($roomTypeId);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de habitación restaurado exitosamente.',
            'data' => $roomType,
        ]);
    }
}
