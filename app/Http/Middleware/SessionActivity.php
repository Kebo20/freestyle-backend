<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\DB;

class SessionActivity
{


    protected $session;
    protected $timeout = 600 * 20; //20min

    public function __construct(Store $session)
    {
        $this->session = $session;
    }
    
    public function handle($request, Closure $next)
    {
        //var_dump($request->user()->token()->id);
        if (
            $request->path() == 'api/auth/login'
            // || $request->path() == 'api/external/clients'
            // || (strpos($request->path(), 'api/external/programming/') !== false)
            // || $request->path() == 'api/sendmail' || $request->path() == 'api/external/clients/active'
            // || $request->path() == 'api/auth/forgotPassword'
            // || $request->path() == 'api/external/getsettings'
            // || $request->path() == 'api/external/clients/searchruc'
            // || $request->path() == 'api/external/client/registerNatural'
            // || $request->path() == 'api/external/recaptcha'
            // || $request->path() == 'api/external/inscription/processVirtualInscription'
            // || $request->path() == 'api/services/programmings/masiveInactivate'
            // || $request->path() == 'api/services/programmings/masiveSendEmail'
            // || $request->path() == 'api/services/programmings/notificationbeforeadvisory'
            // || $request->path() == 'api/services/programmings/notificationnumberactiveusers'
            // || $request->path() == 'api/services/programmings/notificationnumberadvisorybyinterval'
            || $request->path() == 'api/external/files/storage'
            || $request->path() == 'api/products/novelties'
            || $request->path() == 'api/products/byCategory'
            || $request->path() == 'api/categories/list'
            || (strpos($request->path(), 'api/categories/') !== false)
             || $request->path() == 'api/auth/products'
             || $request->path() == 'api/auth/categories'






        ) return $next($request);

        if ($request->user() == null) {
            return response()->json([
                'message' => 'Token inválido'
            ], 401);
        }
        // $last_activity = DB::table('bts_sessions')->where('session_id', $request->user()->token()->id);

        // if (
        //     $last_activity->exists() &&
        //     time() - $last_activity->value('last_active') > $this->timeout
        // ) {
        //     $request->user()->token()->revoke();
        //     $last_activity->delete();
        //     return response()->json([
        //         'message' => 'Ha superado el lapso de inactividad'
        //     ], 401);
            //return redirect('login');
        // } else {
            // DB::table('bts_sessions')->updateOrInsert(
            //     ['session_id' => $request->user()->token()->id, 'contents' => $request->user()->token()->id],
            //     ['last_active' => time()]
            // );
        // }

        return $next($request);
    }
}
