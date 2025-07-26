<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\Hotel\CreateHotelAction;
use App\Actions\V1\Hotel\DeleteHotelAction;
use App\Actions\V1\Hotel\GetHotelsAction;
use App\Actions\V1\Hotel\UpdateHotelAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Hotel\IndexHotelRequest;
use App\Http\Requests\V1\Hotel\StoreHotelRequest;
use App\Http\Requests\V1\Hotel\UpdateHotelRequest;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group('Gestión de Hoteles')]
#[Subgroup('CRUD de Hoteles')]
class HotelController extends Controller
{
    #[Endpoint(
        title: 'Listar hoteles',
        description: 'Obtiene una lista paginada de hoteles con opciones de filtrado por ciudad, número mínimo de habitaciones y búsqueda por nombre o NIT.'
    )]
    #[Authenticated]
    public function index(IndexHotelRequest $request, GetHotelsAction $action): JsonResponse
    {
        $hotels = $action($request->validated());

        return response()->json([
            'success' => true,
            'data' => $hotels->items(),
            'meta' => [
                'current_page' => $hotels->currentPage(),
                'last_page' => $hotels->lastPage(),
                'per_page' => $hotels->perPage(),
                'total' => $hotels->total(),
            ],
        ]);
    }

    #[Endpoint(
        title: 'Crear hotel',
        description: 'Crea un nuevo hotel en el sistema con la información proporcionada.'
    )]
    #[Authenticated]
    public function store(StoreHotelRequest $request, CreateHotelAction $action): JsonResponse
    {
        $hotel = $action($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Hotel creado exitosamente.',
            'data' => $hotel,
        ], 201);
    }

    #[Endpoint(
        title: 'Mostrar hotel',
        description: 'Obtiene la información detallada de un hotel específico por su ID.'
    )]
    #[Authenticated]
    public function show(Hotel $hotel): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $hotel,
        ]);
    }

    #[Endpoint(
        title: 'Actualizar hotel',
        description: 'Actualiza la información de un hotel existente. Solo se actualizarán los campos proporcionados.'
    )]
    #[Authenticated]
    public function update(UpdateHotelRequest $request, Hotel $hotel, UpdateHotelAction $action): JsonResponse
    {
        $updatedHotel = $action($hotel, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Hotel actualizado exitosamente.',
            'data' => $updatedHotel,
        ]);
    }

    #[Endpoint(
        title: 'Eliminar hotel',
        description: 'Elimina un hotel del sistema. Esta operación no se puede deshacer.'
    )]
    #[Authenticated]
    public function destroy(Hotel $hotel, DeleteHotelAction $action): JsonResponse
    {
        $action($hotel);

        return response()->json([
            'success' => true,
            'message' => 'Hotel eliminado exitosamente.',
        ]);
    }
}
