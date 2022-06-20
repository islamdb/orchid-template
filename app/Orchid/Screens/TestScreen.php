<?php

namespace App\Orchid\Screens\;

use App\Models\User;
use App\Support\ResourceScreen;
use Illuminate\Database\Eloquent\Builder;
use IslamDB\OrchidHelper\Column;
use IslamDB\OrchidHelper\View;

class TestScreen extends ResourceScreen
{
    /**
     * @var string
     */
    public $defaultSort = 'created_at';

    /**
     * @var string
     */
    public $sortingDirection = 'desc';

    /**
     * @var int
     */
    public $perPage = 10;

    /**
     * Title for add modal and edit modal
     *
     * @var string
     */
    public $title = 'name';

    /**
     * Use to define which file column
     * to save (in attachment)
     *
     * @var array
     */
    public $files = [];

    /**
     * @return null|Builder
     */
    public function model()
    {
        return User::query();
    }

    /**
     * @return Builder|null
     */
    public function modelView()
    {
        return $this->model();
    }

    /**
     * Form for add and edit
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }

    /**
     * Table columns
     *
     * @return array
     */
    public function columns()
    {
        return [];
    }

    /**
     * View data
     *
     * @return array
     */
    public function views()
    {
        return [];
    }
}
