<?php

namespace App\Http\Requests\Setup;

use App\Models\MuscleGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class MuscleGroupRequest
 *
 * Unified Form Request handling validation for both Storing and Updating Muscle Groups.
 */
class MuscleGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $muscleGroup = $this->route('muscleGroup');
        $muscleGroupId = $muscleGroup instanceof MuscleGroup ? $muscleGroup->id : $muscleGroup;

        return [
            // Name length restricted to 50 chars as requested, unique only among active non-deleted records
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('muscle_groups', 'name')
                    ->where(fn ($query) => $query->where('record_deleted', false))
                    ->ignore($muscleGroupId),
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    /**
     * Custom human-readable validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please provide a muscle group name.',
            'name.unique'   => 'An active muscle group with this name already exists.',
            'name.max'      => 'The muscle group name cannot exceed 50 characters.',
            'description.max' => 'The description cannot exceed 1000 characters.',
        ];
    }

    /**
     * Custom friendly attribute display names.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name'        => 'Muscle Group Name',
            'description' => 'Description',
            'is_active'   => 'Status',
        ];
    }
}
