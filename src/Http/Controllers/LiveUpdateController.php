<?php

namespace Wehaa\Liveupdate\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class LiveUpdateController extends Controller
{
    public function update(NovaRequest $request)
    {
        $model = $request->model()->find($request->id);
        $model->{$request->attribute} = $request->value;
        $model->save();
    }
}
