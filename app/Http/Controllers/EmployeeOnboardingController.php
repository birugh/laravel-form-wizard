<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccessRightsRequest;
use App\Http\Requests\StoreEvidenceRequest;
use App\Http\Requests\StoreJobDetailsRequest;
use App\Http\Requests\StorePersonalInformationRequest;
use App\Http\Requests\SubmitOnboardingRequest;
use App\Http\Resources\EmployeeOnboardingResource;
use App\Models\EmployeeOnboarding;
use App\Models\User;
use App\OnboardingStatus;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeOnboardingController extends Controller
{
    public function store(StorePersonalInformationRequest $request)
    {
        $onboarding = EmployeeOnboarding::create([
            'personal_information' => $request->validated(),
            'created_by' => Auth::id(),
        ])->refresh();;

        return response()->json([
            // 'message' => 'Draft created',
            'data' => new EmployeeOnboardingResource($onboarding),
            'success' => true,
            'message' => 'Onboarding draft created successfully',
        ], 201);
    }

    public function index()
    {
        $this->authorize('viewAny', EmployeeOnboarding::class);

        $onboardings = EmployeeOnboarding::latest()->paginate(5);

        return EmployeeOnboardingResource::collection($onboardings)
            ->additional([
                'success' => true,
                'message' => 'Employee onboardings retrieved successfully',
            ]);
    }
    public function show(EmployeeOnboarding $onboarding)
    {
        $this->authorize('view', $onboarding);

        return response()->json([
            'data' => new EmployeeOnboardingResource($onboarding),
            'success' => true,
            'message' => 'Employee onboarding retrieved successfully',
        ]);
    }

    public function updateStep2(
        StoreJobDetailsRequest $request,
        EmployeeOnboarding $onboarding
    ) {
        $this->authorize('update', $onboarding);

        $onboarding->update([
            'job_details' => $request->validated(),
        ]);

        return response()->json([
            'data' => new EmployeeOnboardingResource($onboarding->refresh()),
            'success' => true,
            'message' => 'Job details saved successfully',
        ])->setStatusCode(Response::HTTP_OK);
    }

    public function updateStep3(
        StoreAccessRightsRequest $request,
        EmployeeOnboarding $onboarding
    ) {
        $this->authorize('update', $onboarding);

        $accessRights = $request->safe()->except('evidences');

        if ($request->hasFile('evidences')) {
            $uploadedEvidences = [];

            foreach ($request->file('evidences') as $file) {
                $path = $file->store(
                    "uploads/onboardings/{$onboarding->id}/evidences"
                );

                $uploadedEvidences[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ];
            }

            $accessRights['evidences'] = $uploadedEvidences;
        } else {
            $accessRights['evidences'] = $onboarding->access_rights['evidences'] ?? [];
        }

        $onboarding->update([
            'access_rights' => $accessRights
        ]);

        return response()->json([
            'data' => new EmployeeOnboardingResource($onboarding->refresh()),
            'success' => true,
            'message' => 'Access rights saved successfully',
        ])->setStatusCode(Response::HTTP_OK);
    }

    // public function updateStep4(
    //     StoreEvidenceRequest $request,
    //     EmployeeOnboarding $onboarding
    // ) {
    //     $this->authorize('update', $onboarding);

    //     $onboarding->update([
    //         'evidences' => $request->validated(),
    //     ]);

    //     return response()->json([
    //         'data' => new EmployeeOnboardingResource($onboarding),
    //         'success' => true,
    //         'message' => 'Evidences saved successfully',
    //     ])->setStatusCode(Response::HTTP_OK);
    // }

    public function submit(EmployeeOnboarding $onboarding)
    {
        $this->authorize('submit', $onboarding);

        $data = [
            'personal_information' => $onboarding->personal_information,
            'job_details' => $onboarding->job_details,
            'access_rights' => $onboarding->access_rights,
            'evidences' => $onboarding->evidences,
        ];

        validator($data, SubmitOnboardingRequest::rules())->validate();

        DB::transaction(function () use ($onboarding) {
            User::create([
                'name' => $onboarding->personal_information['name'],
                'email' => $onboarding->personal_information['email'],
                'password' => Hash::make('password123'),
                'role' => 'user',
                'is_active' => true,
            ]);

            $onboarding->update([
                'status' => OnboardingStatus::SUBMITTED,
                'submitted_at' => now(),
            ]);
        });

        return response()->json([
            // 'data' => new EmployeeOnboardingResource($onboarding->refresh()),
            'data' => [
                'id' => $onboarding->id,
                'status' => OnboardingStatus::SUBMITTED,
                'submitted_at' => $onboarding->submitted_at,
            ],
            'success' => true,
            'message' => 'Employee onboarding submitted successfully',
        ])->setStatusCode(Response::HTTP_OK);
    }
}
