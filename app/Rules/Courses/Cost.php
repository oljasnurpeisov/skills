<?php

namespace App\Rules\Courses;

use App\Models\Route;
use Illuminate\Contracts\Validation\Rule;

class Cost implements Rule
{
    /**
     * @var int
     */
    private $cost;

    /**
     * Create a new rule instance.
     *
     * @param int $cost
     */
    public function __construct(int $cost)
    {
        $this->cost = $cost;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $this->cost > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Стоимость должна быть целым числом больше 0.';
    }
}
