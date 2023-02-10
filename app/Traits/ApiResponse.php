<?php

namespace App\Traits;

trait ApiResponse
{
    protected function successResponse($data, $messageRetour = 'Resource trouvé', $codeRetour=200, $codeStatus = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'messageRetour' => $messageRetour,
            'hasPagination' => false,
            'data' => $data,
            'codeRetour' => $codeRetour
        ], $codeStatus);
    }

    protected function successResponseWithPagination($data, $messageRetour = 'Resource trouvé', $codeRetour=200, $codeStatus = 200): \Illuminate\Http\JsonResponse
    {
        $links = [
            'first' => $data->url(1),
            'last' => $data->url($data->lastPage()),
            'prev' => $data->previousPageUrl(),
            'next' => $data->nextPageUrl(),
        ];
        $meta = [
            'current_page' => $data->currentPage(),
            'from' => $data->firstItem(),
            'last_page' => $data->lastPage(),
            'path' => $data->path(),
            'per_page' => $data->perPage(),
            'to' => $data->lastItem(),
            'total' => $data->total(),
        ];
        return response()->json([
            'success' => true,
            'message' => $messageRetour,
            'hasPagination' => true,
            'codeRetour' => $codeRetour,
            'data' => [
                'data' => $data,
                'links' => $links,
                'meta' => $meta,
            ]
        ], $codeStatus);
    }

    protected function errorResponse($dataErreurs=[],$message=' Impossible d\'accéder à cette resource ', $codeRetour=400, $codeStatus=400): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'hasPagination' => false,
            'message' => $message,
            'dataErreurs' => $dataErreurs,
            'codeRetour' => $codeRetour
        ], $codeStatus);
    }

}
