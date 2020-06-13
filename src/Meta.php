<?php

declare(strict_types=1);

namespace Veryard\Meta;

use DOMDocument;
use Veryard\Meta\Exceptions\MetaException;

class Meta
{
    /**
     * @var string URL
     */
    private string $url;

    /**
     * @var string Hash of URL
     */
    private string $hash;

    /**
     * Default tags to get from meta
     * @var array
     */
    private array $tags = [
        'description',
        'keywords',
        'og:image',
    ];

    /**
     * CURL
     * @var |null
     */
    private $curl = null;

    /**
     * Result
     * @var array
     */
    protected array $result = [];

    /**
     * Meta constructor.
     * @param string $url
     * @param array $tags
     * @throws MetaException
     */
    public function __construct(string $url, array $tags = [])
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new MetaException("Invalid URL provided.");
        }

        $this->url = $url;
        $this->hash = md5($this->url);

        if (! empty($tags)) {
            $this->tags = array_merge($this->tags, $tags);
        }
    }

    /**
     * @return array
     * @throws MetaException
     */
    public function get(): array
    {
        $this->setup();

        $data = curl_exec($this->curl);
        curl_close($this->curl);

        if (! $data) {
            throw new MetaException("Something went wrong");
        }

        return $this->parse($data);
    }

    /**
     * Parse CURL Response
     * @param string $data
     * @return array
     */
    private function parse(string $data): array
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($data);

        $title = $this->parseTitle($dom);

        $tags = $this->parseTags($dom);

        $this->result = array_merge(
            [
                'title' => $title,
            ],
            $tags
        );

        return $this->result;
    }

    /**
     * Get Title from DOM
     * @param DOMDocument $dom
     * @return string|null
     */
    private function parseTitle(DOMDocument $dom): ?string
    {
        $nodes = $dom->getElementsByTagName("title");

        if ($nodes->count() == 0) {
            return null;
        }

        return $nodes->item(0)->nodeValue;
    }

    /**
     * Get Tags from DOM
     * @param DOMDocument $dom
     * @return array|null
     */
    private function parseTags(DOMDocument $dom): ?array
    {
        $response = [];
        $metas = $dom->getElementsByTagName("meta");
        if ($metas->count() == 0) {
            return $response;
        }

        for ($i = 0; $i < $metas->length; $i++) {
            $meta = $metas->item($i);

            foreach ($this->tags as $tag) {
                if (! array_key_exists($tag, $response)) {
                    $response[$tag] = "";
                }

                if ($meta->getAttribute('name') == $tag || $meta->getAttribute('property') == $tag) {
                    $response[$tag] = $meta->getAttribute('content');
                }
            }
        }

        return $response;
    }

    /**
     * Setup CURL
     */
    private function setup(): void
    {
        $this->curl = curl_init();
        curl_setopt_array($this->curl, [
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->url,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 10,
        ]);
    }
}
