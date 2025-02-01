<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->whenLoaded('category', function () {
                return $this->category ? new CategoryResource($this->category) : null;
            }),
            'news_agency' => $this->whenLoaded('newsAgency', function () {
                return $this->newsAgency ? new NewsAgencyResource($this->newsAgency) : null;
            }),
            'source' => $this->whenLoaded('source', function () {
                return $this->source ? new SourceResource($this->source) : null;
            }),
            'author' => $this->whenLoaded('author', function () {
                return $this->author ? new AuthorResource($this->author) : null;
            }),
            'published_at' => $this->publish_date,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
