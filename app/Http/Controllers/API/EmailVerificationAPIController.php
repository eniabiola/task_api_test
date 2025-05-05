<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\HasResponseTrait;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationAPIController extends Controller
{
    use HasResponseTrait;

    public function verifyEmailLink(EmailVerificationRequest $request): JsonResponse
    {
        $request->fulfill();
        return $this->successResponse("Email successfully verified.");
    }

    public function resendVerificationEmail(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->successResponse( 'Email already verified.');
        }
        $request->user()->sendEmailVerificationNotification();
        return $this->successResponse('Verification email resent.');
    }


}
