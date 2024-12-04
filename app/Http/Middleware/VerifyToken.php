<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if token is passed in query or headers
            if ($request->has('token')) {
                JWTAuth::setToken($request->token); // Use query parameter
            }

            // Attempt to authenticate user
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenInvalidException $e) {
            return response()->json(["message" => "Token is invalid"], 401);
        } catch (TokenExpiredException $e) {
            return response()->json(["message" => "Token is expired"], 401);
        } catch (JWTException $e) {
            return response()->json(["message" => "Token not provided"], 400);
        } catch (\Exception $e) {
            \Log::error("JWT Error: " . $e->getMessage());
            return response()->json(["message" => "An error occurred"], 500);
        }

        return $next($request);
    }
}