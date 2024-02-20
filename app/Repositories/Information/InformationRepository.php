<?php

namespace App\Repositories\Information;

use App\Models\Information;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendFirebaseNotification;
use App\Services\Firebase\FirebaseServiceInterface;
use App\Repositories\Information\InformationRepositoryInterface;

class InformationRepository implements InformationRepositoryInterface
{
    private $model;
    private $firebaseService;

    public function __construct(
        Information $model,
        FirebaseServiceInterface $firebaseService,
    )
    {
        $this->model = $model;
        $this->firebaseService = $firebaseService;
    }

    private $field =
    [
        'id',
        'user_id',
        'name',
        'short_description',
        'note',
        'file_url',
        'file_path',
        'file_disk',
        'image_url',
        'image_path',
        'image_disk',
    ];

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with([
                        'user' => function ($query) {
                            $query->select('id', 'name', 'email');
                        },
                    ])
                    ->select($this->field);
        if ($search) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
        }
        return $query->orderBy('created_at', 'DESC')->paginate($perPage);
    }

    public function indexMobile()
    {
        return $this->model
                    ->select($this->field)
                    ->orderBy('created_at', 'DESC')->get();
    }

    public function store(array $data)
    {
        $information = $this->model->create($data);
        if ($information) {
            $title = $information->name;
            $body = $information->short_description;
            $url = $information->image_url;
            // $this->firebaseService->sendNotificationInformation($title, $body, $url);
            Queue::push(new SendFirebaseNotification($title, $body, $url));
        }
        return $information;
    }

    public function show($id)
    {
        $information = $this->model
                            ->with([
                                'user' => function ($query) {
                                    $query->select('id', 'name', 'email');
                                },
                            ])
                            ->where('id', $id)
                            ->first($this->field);
        return $information ? $information : $information = null;
    }

    public function update($id, $data)
    {
        $information = $this->model->find($id);
        if ($information) {
            $information->update($data);
            $title = $information->name;
            $body = $information->short_description;
            $url = $information->image_url;
            // $this->firebaseService->sendNotificationInformation($title, $body, $url);
            Queue::push(new SendFirebaseNotification($title, $body, $url));
            return $information;
        }
        return null;
    }

    public function destroy($id)
    {
        $information = $this->model->find($id);
        if ($information) {
            $information->delete();
            return $information;
        }
        return null;
    }
}
