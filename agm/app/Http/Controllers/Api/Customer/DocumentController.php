<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseApiController;
use App\Repositories\Criteria\DocumentCriteriaRequest;
use App\Repositories\DocumentRepository;
use App\Transformers\DocumentTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\CMS\App\Models\Document;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends BaseApiController
{
    protected $documentRepository;

    public function __construct(DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    /**
     * Get list of documents
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $documents = $this->documentRepository
                ->pushCriteria(new DocumentCriteriaRequest($request))
                ->paginate($request->input('per_page', 10));

            $documents = $this->transform($documents, DocumentTransformer::class);

            return $this->responseSuccess($documents);
        } catch (\Exception $e) {
            Log::error('Get list documents error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * Get document details
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $document = Document::where('id', $id)
                ->where('is_published', Document::PUBLISHED)
                ->first();

            if (!$document) {
                return $this->responseNotFound('Document not found');
            }

            $document = $this->transform($document, DocumentTransformer::class);

            return $this->responseSuccess($document);
        } catch (\Exception $e) {
            Log::error('Get document detail error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * Download document
     *
     * @param int $id
     * @return BinaryFileResponse|JsonResponse
     */
    public function download($id)
    {
        try {
            $document = Document::where('id', $id)
                ->where('is_published', Document::PUBLISHED)
                ->first();

            if (!$document) {
                return $this->responseNotFound('Document not found');
            }

            $path = storage_path('app/public/' . $document->file_path);

            if (!file_exists($path)) {
                return $this->responseNotFound('Document file not found');
            }

            return response()->download($path, $document->file_name, [
                'Content-Type' => $document->mime_type
            ]);
        } catch (\Exception $e) {
            Log::error('Download document error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }
}
