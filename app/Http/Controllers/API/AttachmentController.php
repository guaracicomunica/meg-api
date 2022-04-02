<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Handlers\DeleteAttachmentHandler;
use Illuminate\Http\JsonResponse;

class AttachmentController extends Controller
{
    /**
     * Create a new ActivityController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /***
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        DeleteAttachmentHandler::handle($id);
        return response()->json([
            'message' => 'Delete successfully done',
        ], 204);
    }

    public function deleteAttachmentDeliveredActivity($id): JsonResponse
    {
        DeleteAttachmentHandler::handle($id);
        return response()->json([
            'message' => 'Delete successfully done',
        ], 204);
    }
}
