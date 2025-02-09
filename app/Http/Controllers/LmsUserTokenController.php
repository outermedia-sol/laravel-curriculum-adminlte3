<?php

namespace App\Http\Controllers;

use App\LmsUserToken;
use Illuminate\Http\Request;

class LmsUserTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = LmsUserToken::where('user_id', auth()->user()->id)
            ->where('organization_id', auth()->user()->current_organization_id)
            ->get()
            ->first();

        if (request()->wantsJson()) {
            return ['token' => is_null($token) ? false : true];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //abort_unless(\Gate::allows('lms_user_token_create'), 403);

        $input = $this->validateRequest();

        $token = LmsUserToken::updateOrCreate(
            [
                'organization_id' => auth()->user()->current_organization_id,
                'user_id' => auth()->user()->id,
            ],
            [
                'token' => $input['token'],
            ]);

        if (request()->wantsJson()) {
            return ['token' => $token];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LmsUserToken  $lmsUserToken
     * @return \Illuminate\Http\Response
     */
    public function destroy(LmsUserToken $lmsUserToken)
    {
        //todo: delete if user is deleted
    }

    protected function validateRequest()
    {
        return request()->validate([
            'token' => 'required',
        ]);
    }
}
