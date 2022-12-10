<?php

namespace App\Http\Middleware;

use App\Models\Toko;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekToko
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $id = Auth::id();
        $toko = Toko::where('id_user', $id)->first();
        if (!$toko) {
            return redirect('seller/buat-toko');
        }
        return $next($request);
    }
}
