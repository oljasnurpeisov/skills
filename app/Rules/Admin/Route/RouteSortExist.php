<?php

namespace App\Rules\Admin\Route;

use App\Models\Route;
use Illuminate\Contracts\Validation\Rule;

class RouteSortExist implements Rule
{
    /**
     * @var int
     */
    private $type;

    /**
     * @var int
     */
    private $sort;

    /**
     * @var int|null
     */
    private $route_id;

    /**
     * @var Route
     */
    private $route;

    /**
     * Create a new rule instance.
     *
     * @param $type
     * @param $sort
     * @param $route_id
     */
    public function __construct(int $type, int $sort, $route_id = null)
    {
        $this->type     = $type;
        $this->sort     = $sort;
        $this->route_id = $route_id;
        $this->route    = new Route();
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
        $q = $this->route->whereType($this->type)->whereSort($this->sort);

        if (!empty($this->route_id)) {
            $q = $q->where('id', '!=', $this->route_id);
        }

        return !$q->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Номер уже существует.';
    }
}
