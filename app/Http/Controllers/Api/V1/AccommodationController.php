<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\Accommodation\CreateAccommodationAction;
use App\Actions\V1\Accommodation\DeleteAccommodationAction;
use App\Actions\V1\Accommodation\GetAccommodationsAction;
use App\Actions\V1\Accommodation\RestoreAccommodationAction;
use App\Actions\V1\Accommodation\UpdateAccommodationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Accommodation\IndexAccommodationRequest;
use App\Http\Requests\V1\Accommodation\StoreAccommodationRequest;
use App\Http\Requests\V1\Accommodation\UpdateAccommodationRequest;
use App\Models\Accommodation;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group('Gestión de Acomodaciones')]
#[Subgroup('CRUD de Acomodaciones')]
class AccommodationController extends Controller
{
    #[Endpoint(
        title: 'Listar acomodaciones',
        description: 'Obtiene una lista paginada de acomodaciones con opciones de filtrado por nombre, código o capacidad mínima, incluyendo soporte para borrado lógico.'
    )]
    #[Authenticated]
    public function index(IndexAccommodationRequest $request, GetAccommodationsAction $action): JsonResponse
    {
        $filters = $request->validated();
        $perPage = data_get($filters, 'per_page', 15);

        $accommodations = $action($filters, $perPage);

        return response()->json([
            'success' => true,
            'data' => $accommodations,
        ]);
    }

    #[Endpoint(
        title: 'Crear acomodación',
        description: 'Crea una nueva acomodación en el sistema con nombre, código y capacidad únicos.'
    )]
    #[Authenticated]
    public function store(StoreAccommodationRequest $request, CreateAccommodationAction $action): JsonResponse
    {
        $accommodation = $action($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Acomodación creada exitosamente.',
            'data' => $accommodation,
        ], 201);
    }

    #[Endpoint(
        title: 'Mostrar acomodación',
        description: 'Obtiene la información detallada de una acomodación específica por su ID.'
    )]
    #[Authenticated]
    public function show(Accommodation $accommodation): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $accommodation,
        ]);
    }

    #[Endpoint(
        title: 'Actualizar acomodación',
        description: 'Actualiza la información de una acomodación existente. Solo se actualizarán los campos proporcionados.'
    )]
    #[Authenticated]
    public function update(UpdateAccommodationRequest $request, Accommodation $accommodation, UpdateAccommodationAction $action): JsonResponse
    {
        $updatedAccommodation = $action($accommodation, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Acomodación actualizada exitosamente.',
            'data' => $updatedAccommodation,
        ]);
    }

    #[Endpoint(
        title: 'Eliminar acomodación',
        description: 'Elimina una acomodación del sistema usando borrado lógico. La acomodación puede ser restaurada posteriormente.'
    )]
    #[Authenticated]
    public function destroy(Accommodation $accommodation, DeleteAccommodationAction $action): JsonResponse
    {
        $action($accommodation);

        return response()->json([
            'success' => true,
            'message' => 'Acomodación eliminada exitosamente.',
        ]);
    }

    #[Endpoint(
        title: 'Restaurar acomodación',
        description: 'Restaura una acomodación previamente eliminada (borrado lógico).'
    )]
    #[Authenticated]
    public function restore(int $accommodationId, RestoreAccommodationAction $action): JsonResponse
    {
        $accommodation = $action($accommodationId);

        return response()->json([
            'success' => true,
            'message' => 'Acomodación restaurada exitosamente.',
            'data' => $accommodation,
        ]);
    }
}
