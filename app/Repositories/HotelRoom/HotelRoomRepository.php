<?php

namespace App\Repositories\HotelRoom;

use App\Models\HotelRoom;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class HotelRoomRepository implements HotelRoomRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = HotelRoom::query()
            ->with(['hotel', 'roomType', 'accommodation']);

        if (data_get($filters, 'hotel_id')) {
            $query->where(column: 'hotel_id', operator: '=', value: data_get($filters, 'hotel_id'));
        }

        if (data_get($filters, 'room_type_id')) {
            $query->where(column: 'room_type_id', operator: '=', value: data_get($filters, 'room_type_id'));
        }

        if (data_get($filters, 'accommodation_id')) {
            $query->where(column: 'accommodation_id', operator: '=', value: data_get($filters, 'accommodation_id'));
        }

        if (data_get($filters, 'min_quantity')) {
            $query->where(column: 'quantity', operator: '>=', value: data_get($filters, 'min_quantity'));
        }

        if (data_get($filters, 'with_trashed')) {
            $query->withTrashed();
        } elseif (data_get($filters, 'only_trashed')) {
            $query->onlyTrashed();
        }

        return $query->orderBy(column: 'created_at', direction: 'desc')
            ->paginate(perPage: $perPage);
    }

    public function findById(int $hotelRoomId): ?HotelRoom
    {
        return HotelRoom::with(['hotel', 'roomType', 'accommodation'])->find($hotelRoomId);
    }

    public function create(array $data): HotelRoom
    {
        $hotelRoom = \App\Models\HotelRoom::query()->create([
            'hotel_id' => data_get($data, 'hotel_id'),
            'room_type_id' => data_get($data, 'room_type_id'),
            'accommodation_id' => data_get($data, 'accommodation_id'),
            'quantity' => data_get($data, 'quantity'),
        ]);

        return $hotelRoom->load(['hotel', 'roomType', 'accommodation']);
    }

    public function update(HotelRoom $hotelRoom, array $data): HotelRoom
    {
        $updateData = [];

        if (array_key_exists('quantity', $data)) {
            data_set($updateData, 'quantity', data_get($data, 'quantity'));
        }

        if (! empty($updateData)) {
            $hotelRoom->update($updateData);
        }

        return $hotelRoom->fresh(['hotel', 'roomType', 'accommodation']);
    }

    public function delete(HotelRoom $hotelRoom): bool
    {
        return $hotelRoom->delete();
    }

    public function restore(int $hotelRoomId): HotelRoom
    {
        /** @var HotelRoom $hotelRoom */
        $hotelRoom = HotelRoom::withTrashed()->findOrFail($hotelRoomId);
        $hotelRoom->restore();

        return $hotelRoom->fresh(['hotel', 'roomType', 'accommodation']);
    }

    public function findTrashed(int $hotelRoomId): ?HotelRoom
    {
        return HotelRoom::onlyTrashed()->with(['hotel', 'roomType', 'accommodation'])->find($hotelRoomId);
    }
}
