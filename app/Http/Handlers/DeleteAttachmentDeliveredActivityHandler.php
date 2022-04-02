<?php

namespace App\Http\Handlers;

use App\Models\UserActivityDeliveryFile;
use App\Utils\File;
use Illuminate\Support\Facades\DB;

class DeleteAttachmentDeliveredActivityHandler
{

    /***
     * @param $id
     * @return void
     */
    public static function handle($id)
    {
        try {
            DB::beginTransaction();

            $attachment = UserActivityDeliveryFile::findOrFail($id);
            
            File::delete($attachment->path);

            $attachment->delete();

            DB::commit();
        } catch (Exception $ex)
        {
            DB::rollBack();
            throw $ex;
        }
    }
}
