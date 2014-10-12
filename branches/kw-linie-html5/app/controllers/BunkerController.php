<?php

class BunkerController extends BaseController
{

    public function index()
    {
        try {
            $statusCode = 200;
            $response = [
                [
                    "id" => "666",
                    "firstname" => "Geert"
                ], [
                    "id" => "665",
                    "firstname" => "test"
                ]];

        } catch (Exception $e) {
            $statusCode = 400;
            $response = [];
        }
        return Response::json($response, $statusCode);
    }

}
