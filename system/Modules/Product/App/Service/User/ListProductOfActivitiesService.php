<?php

namespace Modules\Product\App\Service\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\App\Models\Product;
use Carbon\Carbon;

class ListProductOfActivitiesService
{
    public function getProductsByActivities(?string $search = null)
    {
        try {
            return Product::query()
                ->where('type', 'activities')
                ->select(['id', 'name'])
                ->orderByDesc('id')
                ->get();

        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
