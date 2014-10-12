<?php

class DeelgemeenteController extends BaseController
{

    public function index()
    {
        try {
            $statusCode = 200;
            $response = [
                [
                    "deelgemeente_id" => "1",
                    "deelgemeente" => "Aartselaar",
                    "gemeente" => "Aartselaar",
                    "kwlinie" => 0
                ], [
                    "deelgemeente_id" => "2",
                    "deelgemeente" => "Antwerpen Kern - Oude stad",
                    "gemeente" => "Antwerpen",
                    "kwlinie" => 0
                ]];

        } catch (Exception $e) {
            $statusCode = 400;
            $response = [];
        }
        return Response::json($response, $statusCode);
    }

}
