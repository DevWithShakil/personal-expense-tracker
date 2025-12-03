<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => number_format($this->amount, 2, '.', ''),
            'amount_formatted' => '৳' . number_format($this->amount, 2),
            'description' => $this->description,
            'date' => $this->transaction_date->format('Y-m-d'),
            'date_human' => $this->transaction_date->format('d M, Y'),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
