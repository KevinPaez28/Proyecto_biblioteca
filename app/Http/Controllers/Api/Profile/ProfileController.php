<?php

namespace App\Http\Controllers\Api\Profile;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\createProfile;
use App\Http\Requests\Profile\updateProfile;
use App\Services\Profile\ProfileServices;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    protected $ProfileServices;


    public function __construct(ProfileServices $profileServices)
    {
        $this->ProfileServices = $profileServices;
    }

    public function getAll()
    {
        $response = $this->ProfileServices->getProfiles();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function create(createProfile $request)
    {
        $data = $request->validated();

        $response = $this->ProfileServices->createprofiles($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateProfile $request, string $id)
    {
        $data = $request->validated();

        $response = $this->ProfileServices->updateProfiles($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->ProfileServices->deleteprofile($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
