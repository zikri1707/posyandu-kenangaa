<?php

namespace App\Services;

use App\Models\Pedukuhan;

class PedukuhanService
{
    /**
     * Create a new pedukuhan.
     */
    public function createPedukuhan(array $data): Pedukuhan
    {
        return Pedukuhan::create($data);
    }

    /**
     * Update an existing pedukuhan.
     */
    public function updatePedukuhan(Pedukuhan $pedukuhan, array $data): Pedukuhan
    {
        $pedukuhan->update($data);

        return $pedukuhan;
    }

    /**
     * Delete a pedukuhan.
     */
    public function deletePedukuhan(Pedukuhan $pedukuhan): void
    {
        $pedukuhan->delete();
    }
}
