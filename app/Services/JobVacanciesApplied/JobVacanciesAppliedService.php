<?php

namespace App\Services\JobVacanciesApplied;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInvitationInterviewMail;
use App\Services\Helper\HelperServiceInterface;
use App\Services\JobVacanciesApplied\JobVacanciesAppliedServiceInterface;
use App\Repositories\JobVacanciesApplied\JobVacanciesAppliedRepositoryInterface;

class JobVacanciesAppliedService implements JobVacanciesAppliedServiceInterface
{
    private $repository;
    private $helperService;

    public function __construct(JobVacanciesAppliedRepositoryInterface $repository, HelperServiceInterface $helperService)
    {
        $this->repository = $repository;
        $this->helperService = $helperService;
    }

    public function index($perPage, $search, $status)
    {
        return $this->repository->index($perPage, $search, $status);
    }

    public function store(array $data)
    {
        $data['status'] = $this->formatTextTitle($data['status']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['status'] = $this->formatTextTitle($data['status']);
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function sendEmailInterview($data)
    {
        $item = $this->repository->show($data['id']);
        $helper = $this->helperService->show();
        $message = 'Belum ada jadwal interview';
        $error = true;
        $code = 422;
        $data = null;
        if ($item && !$item->jobInterviewForm->isEmpty()) {
            $pendingInterview = $item->jobInterviewForm->where('status', 'PENDING')->sortBy('date')->first();
            $carbonDate = Carbon::parse($pendingInterview->date);
            $data = [
                'name' => Str::title($item->candidate->first_name . ' ' . $item->candidate->middle_name . ' ' . $item->candidate->last_name),
                'position' => Str::title($item->jobVacancy->position),
                'date' => $carbonDate->format('Y-m-d'),
                'day' => $carbonDate->format('l'),
                'hour' => $carbonDate->format('H:i'),
                'telephone_hr' => $helper->telephone_invitation_interview !== null ? $helper->telephone_invitation_interview : '00000000',
                'email_hr' => $helper->email_invitation_interview !== null ? $helper->email_invitation_interview : 'hr@rsyarsi.co.id',
            ];
            Mail::to($item->candidate->email)->send(new SendInvitationInterviewMail($data));
            $message = 'Email invitation interview sent successfully';
            $error = false;
            $code = 200;
            $data = $item;
        }
        return [
            'message' => $message,
            'error' => $error,
            'code' => $code,
            'data' => $data
        ];
    }

    public function formatTextTitle($data)
    {
        return Str::upper($data);
    }
}
