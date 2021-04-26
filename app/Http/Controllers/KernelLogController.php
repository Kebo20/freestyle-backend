<?php

namespace App\Http\Controllers;

use App\Models\KernelLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KernelLogController extends Controller
{
    public static function fnKernelLogMessage(Exception $exception)
    {
        try {


            DB::beginTransaction();

            $code = 'IT' . date('Y') . '-' . str_pad((KernelLog::whereYear('created_at',date('Y'))->count() + 1), 6, '0', STR_PAD_LEFT);

            $log = new KernelLog();
            $log->code = $code;
            $log->created_at = date('Y-m-d H:i:s');
            $log->created_by = Auth::check() ? auth()->id() : null;
            $log->updated_at = date('Y-m-d H:i:s');
            $log->updated_by = Auth::check() ? auth()->id() : null;
            $log->message = $exception->getMessage();
            $log->extended_message = $exception;


            if (env('APP_MODE') == 'local') {
                return $exception->getMessage();
            } else {
                $log->save();
                DB::commit();

                return 'Código de error: ' . $code . '. Contacte con el administrador del sistema. ';
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }}
