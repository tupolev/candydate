<?php

namespace App\Http\Controllers;

use App\Models\JobProcessLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpCodes;

class JobProcessStatusController extends JsonController
{
    public function changeJobProcessStatus(Request $request, int $jobProcessId): Response
    {
        try {
            return static::buildResponse(
                JobProcessLog::changeJobProcessStatus($jobProcessId, $request->json()->all()),
                HttpCodes::HTTP_CREATED
            );
        } catch (ValidationException $ex) {
            return static::buildResponse($ex->validator->getMessageBag()->toArray(), HttpCodes::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }
}
