<?php

namespace App\Http\Controllers;

use App\Models\JobProcess;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpCodes;

class JobProcessController extends JsonController
{
    public function listJobProcesses(Request $request): Response
    {
        return static::buildResponse(JobProcess::listByUserId($request->user()->id));
    }

    public function viewJobProcess(int $jobProcessId): Response
    {
        try {
            return static::buildResponse(JobProcess::viewJobProcess($jobProcessId)->toPublicList());
        } catch (ModelNotFoundException $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_NOT_FOUND);
        }
    }

    public function createJobProcess(Request $request): Response
    {
        try {
            return static::buildResponse(
                JobProcess::createJobProcess($request->json()->all())->toPublicList(),
                HttpCodes::HTTP_CREATED
            );
        } catch (ValidationException $ex) {
            return static::buildResponse(['errors' => $ex->validator->errors()->getMessageBag()->toArray()], HttpCodes::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function editJobProcess(Request $request, int $jobProcessId): Response
    {
        $jobProcessValidator = JobProcess::getValidatorForEditPayload($request->json()->all());

        if ($jobProcessValidator->fails()) {
            return static::buildResponse(
                ['errors' => $jobProcessValidator->errors()->getMessageBag()->toArray()],
                HttpCodes::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            return static::buildResponse(
                JobProcess::editJobProcess($jobProcessId, $request->json()->all())->toPublicList(),
                HttpCodes::HTTP_CREATED
            );
        } catch (ValidationException $ex) {
            return static::buildResponse(['errors' => $ex->validator->errors()->getMessageBag()->toArray()], HttpCodes::HTTP_BAD_REQUEST);
        } catch (\Throwable $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }

    public function deleteJobProcess(int $jobProcessId): Response
    {
        return static::buildResponse(JobProcess::deleteJobProcess($jobProcessId));
    }
}
