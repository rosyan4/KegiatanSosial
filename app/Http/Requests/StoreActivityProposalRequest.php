<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

   public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'objectives' => 'required|string',
            'benefits' => 'required|string',
            'proposed_date' => 'required|date|after:today',
            'proposed_location' => 'required|string|max:255',
            'estimated_participants' => 'required|integer|min:1',
            'estimated_budget' => 'nullable|numeric|min:0',
            'required_support' => 'nullable|string|max:500',
        ];
    }
}