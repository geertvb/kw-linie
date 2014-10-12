<?php

class GemeenteController extends BaseController
{

    public function index()
    {
        try {
            $statusCode = 200;
            $response = [
                [
                    "gemeente_id" => "2904",
                    "naam" => "Aaigem",
                    "postcode" => "9420"
                ], [
                    "gemeente_id" => "2905",
                    "naam" => "Aalbeke",
                    "postcode" => "8511"
                ]];

        } catch (Exception $e) {
            $statusCode = 400;
            $response = [];
        }
        return Response::json($response, $statusCode);
    }

}
