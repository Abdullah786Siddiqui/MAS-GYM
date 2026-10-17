<?php

namespace App\Http\Requests\Setup;

use App\Models\Exercise;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class ExerciseRequest
 *
 * Unified Form Request handling validation for both Storing and Updating Exercises.
 */
class ExerciseRequest extends FormRequest
{
    /**
     * Allowed equipment enum values.
     */
    public const VALID_EQUIPMENT = [
        'barbell',
        'dumbbell',
        'cable',
        'machine',
        'bodyweight',
    ];

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
     * Prepare data prior to validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true,
            ]);
        }

        if ($this->has('equipment') && is_string($this->input('equipment'))) {
            $this->merge([
                'equipment' => strtolower(trim($this->input('equipment'))),
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
        $exercise = $this->route('exercise');
        $exerciseId = $exercise instanceof Exercise ? $exercise->id : $exercise;

        return [
            // Exercise name is limited to 50 chars as requested and unique among non-deleted records
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('exercises', 'name')
                    ->where(fn ($query) => $query->where('record_deleted', false))
                    ->ignore($exerciseId),
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'equipment' => [
                'nullable',
                'string',
                Rule::in(self::VALID_EQUIPMENT),
            ],

            'video_url' => [
                'nullable',
                'url',
                'max:500',
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
            'name.required' => 'Please provide an exercise name.',
            'name.unique'   => 'An active exercise with this name already exists.',
            'name.max'      => 'The exercise name cannot exceed 50 characters.',
            'equipment.in'  => 'Selected equipment type is invalid. Choose from: Barbell, Dumbbell, Cable, Machine, or Bodyweight.',
            'video_url.url' => 'Please enter a valid URL for the demonstration video.',
            'video_url.max' => 'The video URL cannot exceed 500 characters.',
            'muscle_group_ids.*.exists' => 'One or more selected muscle groups are invalid.',
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
            'name'             => 'Exercise Name',
            'description'      => 'Description / Instructions',
            'equipment'        => 'Equipment Type',
            'video_url'        => 'Video Demonstration URL',
            'is_active'        => 'Status',
            'muscle_group_ids' => 'Target Muscle Groups',
        ];
    }
}
