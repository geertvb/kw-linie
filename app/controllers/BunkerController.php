<?php

class BunkerController extends BaseController
{

    public function index()
    {
        try {
            $statusCode = 200;
            $response = DB::table('bunker')->get();

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
