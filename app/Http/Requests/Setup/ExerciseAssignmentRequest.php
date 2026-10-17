<?php

namespace App\Http\Requests\Setup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class ExerciseAssignmentRequest
 *
 * Dedicated Form Request handling validation for both Assigning and Unassigning
 * exercises to muscle groups. Ensures no inline validation is needed in the controller.
 */
class ExerciseAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * When assigning: strictly requires both exercise and muscle group to be active and not soft-deleted.
     * When unassigning: validates existing IDs.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->routeIs('*.unassign')) {
            return [
                'exercise_id'     => ['required', 'integer', 'exists:exercises,id'],
                'muscle_group_id' => ['required', 'integer', 'exists:muscle_groups,id'],
            ];
        }

        return [
            'exercise_id' => [
                'required',
                'integer',
                Rule::exists('exercises', 'id')
                    ->where(function ($query) {
                        $query->where('is_active', true)
                              ->where('record_deleted', false);
                    }),
            ],
            'muscle_group_id' => [
                'required',
                'integer',
                Rule::exists('muscle_groups', 'id')
                    ->where(function ($query) {
                        $query->where('is_active', true)
                              ->where('record_deleted', false);
                    }),
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'exercise_id.required'     => 'Please select an exercise.',
            'exercise_id.exists'       => 'The selected exercise must be active and not deleted.',
            'muscle_group_id.required' => 'Please select a muscle group.',
            'muscle_group_id.exists'   => 'The selected muscle group must be active and not deleted.',
        ];
    }
}
