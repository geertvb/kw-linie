<?php

class GemeenteController extends BaseController
{

    public function index()
    {
        try {
            $response = DB::table('gemeente')
                ->orderBy('naam', 'asc')
                ->get();
            $statusCode = 200;
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
