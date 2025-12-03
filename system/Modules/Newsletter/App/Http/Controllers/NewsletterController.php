<?php

namespace Modules\Newsletter\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Newsletter\App\Models\Subscriber;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class NewsletterController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        $subscribers = Subscriber::paginate($perPage);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $subscribers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'email|unique:subscribers,email',
        ]);

        $subscriber = Subscriber::create([
            'email' => $request->input('email'),
        ]);

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'data' => $subscriber
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Subscriber deleted successfully.'
        ]);
    }
}
