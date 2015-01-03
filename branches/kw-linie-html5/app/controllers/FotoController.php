<?php

class FotoController extends BaseController
{

    protected function contentType($filename, $mimetype) {
        if ("application/octet-stream" == $mimetype) {
            $file_extension = strtolower(substr(strrchr($filename, "."), 1));

            switch ($file_extension) {
                case "pdf":
                    $ctype = "application/pdf";
                    break;
                case "exe":
                    $ctype = "application/octet-stream";
                    break;
                case "zip":
                    $ctype = "application/zip";
                    break;
                case "doc":
                    $ctype = "application/msword";
                    break;
                case "xls":
                    $ctype = "application/vnd.ms-excel";
                    break;
                case "ppt":
                    $ctype = "application/vnd.ms-powerpoint";
                    break;
                case "gif":
                    $ctype = "image/gif";
                    break;
                case "png":
                    $ctype = "image/png";
                    break;
                case "jpe":
                case "jpeg":
                case "jpg":
                    $ctype = "image/jpg";
                    break;
                default:
                    $ctype = "application/force-download";
            }

            return $ctype;
        } else {
            return $mimetype;
        }
    }

    public function index()
    {
        try {
            $statusCode = 200;
            $response = DB::table('foto')
                ->select('foto_id', 'omschrijving', 'filename',
                    'mimetype', 'size', 'width', 'height',
                    'thumb_mimetype', 'thumb_size', 'thumb_width', 'thumb_height')
                ->get();

        } catch (Exception $e) {
            $statusCode = 400;
            $response = [
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ];
        }
        return Response::json($response, $statusCode);
    }

    public function thumbnail($id)
    {
        $foto = DB::table('foto')
            ->select('filename', 'thumb_mimetype', 'thumb_size', 'thumb_content')
            ->where('foto_id', $id)
            ->first();

        $ctype = $this->contentType($foto->filename, $foto->thumb_mimetype);

        return Response::make($foto->thumb_content, 200, array(
            'Content-type' => $ctype,
            'Content-length' => $foto->thumb_size));

    }

    public function content($id)
    {
        $foto = DB::table('foto')
            ->select('filename', 'mimetype', 'size', 'content')
            ->where('foto_id', $id)
            ->first();

        $ctype = $this->contentType($foto->filename, $foto->mimetype);

        return Response::make($foto->content, 200, array(
            'Content-type' => $ctype,
            'Content-length' => $foto->size));

    }

}
