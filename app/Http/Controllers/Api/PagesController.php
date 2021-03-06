<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Repositories\PageRepository;
use App\Repositories\SettingsRepository;
use App\Transformers\InfoTransformer;

class PagesController extends ApiController
{
    /**
     * Get lists of info pages and info titles
     *
     * @param PageRepository $repository
     * @param SettingsRepository $settingsRepository
     * @return \Dingo\Api\Http\Response
     */
    public function index(PageRepository $repository, SettingsRepository $settingsRepository)
    {
        $pages = $repository->getPagesWithDeleted($this->getConference()->id, $this->since);
        $this->checkModified($pages);

        $response = [
            'info'  => $pages,
            'title' => [
                'titleMajor' => $settingsRepository->getValueByKey('titleMajor', $this->getConference()->id),
                'titleMinor' => $settingsRepository->getValueByKey('titleMinor', $this->getConference()->id),
            ]
        ];

        $transformer = $this->app->make(InfoTransformer::class);

        return $this->response->array($transformer->transform($response));
    }
}
