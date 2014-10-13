<?php

class VerbindingController extends BaseController
{

    public function index()
    {
        try {
            $statusCode = 200;
            $response = DB::table('verbinding')->get();

        } catch (Exception $e) {
            $statusCode = 400;
            $response = [
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ];
        }
        return Response::json($response, $statusCode);
    }

}
