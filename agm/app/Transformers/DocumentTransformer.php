<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\CMS\App\Models\Document;

class DocumentTransformer extends TransformerAbstract
{
    public function transform(Document $document)
    {
        return [
            'id' => $document->id,
            'title' => $document->title,
            'file_name' => $document->file_name,
            'mime_type' => $document->mime_type,
            'file_size' => $document->file_size,
//            'formatted_file_size' => $document->formatted_file_size,
//            'description' => $document->description,
            'created_at' => $document->created_at->timestamp,
            'updated_at' => $document->updated_at->timestamp,
            'download_url' => route('api.customer.documents.download', $document->id),
            'view_url' => $this->getViewUrl($document),
            'is_published' => $document->is_published,
        ];
    }

    /**
     * Get the URL for viewing the document directly in browser
     *
     * @param Document $document
     * @return string
     */
    private function getViewUrl(Document $document)
    {
        if ($document->mime_type === 'application/pdf') {
            // For PDF files, we can use Storage URL to get a direct link
            return asset('storage/' . $document->file_path);
        }

        // For non-PDF files, return the download URL as fallback
        return route('api.customer.documents.download', $document->id);
    }
}
