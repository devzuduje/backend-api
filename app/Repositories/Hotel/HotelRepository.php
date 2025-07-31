<?php

namespace App\Repositories\Hotel;

use App\Models\Hotel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;

final class HotelRepository implements HotelRepositoryInterface
{
    public function findById(int $id): ?Hotel
    {
        /** @var Hotel|null */
        return \App\Models\Hotel::query()->find($id);
    }

    public function findByNit(string $nit): ?Hotel
    {
        /** @var Hotel|null */
        return Hotel::byNit($nit)->first();
    }

    public function findByCity(string $city): Collection
    {
        return Hotel::byCity($city)->get();
    }

    public function findWithMinRooms(int $minRooms): Collection
    {
        return Hotel::withMinRooms($minRooms)->get();
    }

    public function search(string $term): Collection
    {
        return \App\Models\Hotel::query()->where(function (Builder $query) use ($term) {
            $query->where('name', 'LIKE', "%{$term}%")
                ->orWhere('nit', 'LIKE', "%{$term}%")
                ->orWhere('email', 'LIKE', "%{$term}%")
                ->orWhere('address', 'LIKE', "%{$term}%");
        })->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Hotel::query();

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    public function getWithRelations(array $relations = []): Collection
    {
        return Hotel::with($relations)->get();
    }

    public function create(array $data): Hotel
    {
        try {
            /** @var Hotel $hotel */
            $hotel = \App\Models\Hotel::query()->create([
                'name' => data_get($data, 'name'),
                'address' => data_get($data, 'address'),
                'city' => data_get($data, 'city'),
                'nit' => data_get($data, 'nit'),
                'email' => data_get($data, 'email'),
                'phone' => data_get($data, 'phone'),
                'max_rooms' => data_get($data, 'max_rooms'),
            ]);

            return $hotel->fresh();
        } catch (QueryException $e) {
            // Manejar errores de duplicidad de manera amigable
            if ($this->isDuplicateKeyError($e)) {
                $this->handleDuplicateKeyError($e, $data);
            }
            
            // Re-lanzar otras excepciones de base de datos
            throw $e;
        }
    }

    private function isDuplicateKeyError(QueryException $e): bool
    {
        // PostgreSQL: código 23505 para violación de restricción única
        // MySQL: código 23000 para violación de restricción única
        return in_array($e->errorInfo[0] ?? '', ['23000', '23505']);
    }

    private function handleDuplicateKeyError(QueryException $e, array $data): void
    {
        $errorMessage = $e->getMessage();
        
        // Detectar qué campo está duplicado
        if (str_contains($errorMessage, 'hotels_name_city_unique')) {
            $city = data_get($data, 'city', 'esta ciudad');
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Error de validación.',
                    'errors' => [
                        'name' => ["Ya existe un hotel con este nombre en {$city}. Por favor, elija un nombre diferente para esta ciudad."]
                    ]
                ], 422)
            );
        }
        
        if (str_contains($errorMessage, 'hotels_nit_unique') || str_contains($errorMessage, 'nit')) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Error de validación.',
                    'errors' => [
                        'nit' => ['Ya existe un hotel con este NIT. Por favor, verifique el número de identificación.']
                    ]
                ], 422)
            );
        }
        
        if (str_contains($errorMessage, 'hotels_email_unique') || str_contains($errorMessage, 'email')) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Error de validación.',
                    'errors' => [
                        'email' => ['Ya existe un hotel con este email. Por favor, elija un email diferente.']
                    ]
                ], 422)
            );
        }
        
        // Error genérico si no podemos identificar el campo específico
        throw new HttpResponseException(
            response()->json([
                'message' => 'Error de duplicidad.',
                'errors' => [
                    'general' => ['Los datos proporcionados ya existen en el sistema. Por favor, verifique la información.']
                ]
            ], 422)
        );
    }

    public function update(Hotel $hotel, array $data): Hotel
    {
        try {
            $updateData = [];

            if (array_key_exists('name', $data)) {
                data_set($updateData, 'name', data_get($data, 'name'));
            }

            if (array_key_exists('address', $data)) {
                data_set($updateData, 'address', data_get($data, 'address'));
            }

            if (array_key_exists('city', $data)) {
                data_set($updateData, 'city', data_get($data, 'city'));
            }

            if (array_key_exists('nit', $data)) {
                data_set($updateData, 'nit', data_get($data, 'nit'));
            }

            if (array_key_exists('email', $data)) {
                data_set($updateData, 'email', data_get($data, 'email'));
            }

            if (array_key_exists('phone', $data)) {
                data_set($updateData, 'phone', data_get($data, 'phone'));
            }

            if (array_key_exists('max_rooms', $data)) {
                data_set($updateData, 'max_rooms', data_get($data, 'max_rooms'));
            }

            if (! empty($updateData)) {
                $hotel->update($updateData);
            }

            return $hotel->fresh();
        } catch (QueryException $e) {
            // Manejar errores de duplicidad de manera amigable
            if ($this->isDuplicateKeyError($e)) {
                $this->handleDuplicateKeyError($e, $data);
            }
            
            // Re-lanzar otras excepciones de base de datos
            throw $e;
        }
    }

    public function delete(Hotel $hotel): bool
    {
        return $hotel->delete();
    }

    public function getTopHotelsByRooms(int $limit = 10): Collection
    {
        return \App\Models\Hotel::query()->orderByDesc('max_rooms')
            ->limit($limit)
            ->get();
    }

    public function getHotelsByRoomsRange(int $minRooms, int $maxRooms): Collection
    {
        return \App\Models\Hotel::query()->whereBetween('max_rooms', [$minRooms, $maxRooms])
            ->orderBy('max_rooms')
            ->get();
    }

    public function restore(int $hotelId): Hotel
    {
        /** @var Hotel $hotel */
        $hotel = Hotel::withTrashed()->findOrFail($hotelId);

        $hotel->restore();

        return $hotel->fresh();
    }

    public function forceDelete(int $hotelId): bool
    {
        /** @var Hotel $hotel */
        $hotel = Hotel::withTrashed()->findOrFail($hotelId);

        return $hotel->forceDelete();
    }

    public function getTrashed(): Collection
    {
        return Hotel::onlyTrashed()->get();
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        if (data_get($filters, 'city')) {
            /** @var \App\Models\Builders\HotelBuilder $query */
            $query->byCity(data_get($filters, 'city'));
        }

        if (data_get($filters, 'min_rooms')) {
            /** @var \App\Models\Builders\HotelBuilder $query */
            $query->withMinRooms(data_get($filters, 'min_rooms'));
        }

        if ($search = data_get($filters, 'search')) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('nit', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }

        if (data_get($filters, 'with_trashed')) {
            /** @var \App\Models\Builders\HotelBuilder $query */
            $query->withTrashed();
        } elseif (data_get($filters, 'only_trashed')) {
            /** @var \App\Models\Builders\HotelBuilder $query */
            $query->onlyTrashed();
        }

        $query->orderBy('name');
    }
}
