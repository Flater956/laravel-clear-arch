<?php


namespace App\Services\User;


use App\Contracts\Repositories\UserNotifyRepositoryInterface;
use App\Definition\Notifications\NotifyPhrase;
use App\Definition\UserRoles;
use App\Repositories\User\UserNotifyRepository;
use App\Services\PushNotify\PushNotifyService;
use Illuminate\Http\Request;

class UserNotifyService
{
    private UserNotifyRepositoryInterface $userNotifyRepository;

    public function __construct(UserNotifyRepositoryInterface $userNotifyRepository)
    {
        $this->userNotifyRepository = $userNotifyRepository;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCurrentUserNotifications(): \Illuminate\Support\Collection
    {
        $currentUserId = UserService::getCurrentUser()->id;

        return $this->userNotifyRepository->getNotificationsByUserId($currentUserId);
    }

    /**
     * @return \App\Models\UserNotificationsSetting|null
     */
    public function getCurrentUserNotifySettings(): ?\App\Models\UserNotificationsSetting
    {
        $currentUserId = UserService::getCurrentUser()->id;

        return $this->userNotifyRepository->getNotifySettingsByUserId($currentUserId);
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function updateUserNotifySettings(Request $request)
    {
        $currentUserId = UserService::getCurrentUser()->id;
        $notifySettings = $request->input('notifications');

        $this->userNotifyRepository->updateUserNotifySettings(
            ['notifications' => $notifySettings],
            $currentUserId
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function updateNotification(Request $request)
    {
        $notifyId = $request->input('id');
        $updatedData = [];

        foreach ($request->all() as $fieldCode => $value)
        {
            if ($value && $value !== 'id') {
                $updatedData[$fieldCode] = $value;
            }
        }

        $this->userNotifyRepository->updateUserNotification($updatedData, $notifyId);

        if (isset($updatedData['is_confirmed'])) {
            self::sendUpdateNotifyPush($updatedData['meeting_id']);
        }
    }

    /**
     * @param array|int $userIds
     * @param string $typeOfNotify
     * @param int $meetingId
     * @param array|null $data
     */
    public static function createNotify(
        array|int $userIds,
        string $typeOfNotify,
        int $meetingId,
        ?array $data
    )
    {
        UserNotifyRepository::createNotifications($userIds, $typeOfNotify, $meetingId, $data);
    }

    /**
     * @param int $meetingId
     */
    private static function sendUpdateNotifyPush(int $meetingId)
    {
        $notifyData = ['meetingID' => $meetingId];
        $usersIds = UserService::getUsersIdsByRole(UserRoles::ROLE_BARISTA);
        $fcmTokens = UserService::getUsersTokens($usersIds);

        $pushService = new PushNotifyService();
        $pushService->sendPush(
            NotifyPhrase::UPDATE_NOTIFY_PUSH_TITLE,
            NotifyPhrase::UPDATE_NOTIFY_PUSH_BODY,
            $fcmTokens,
            $notifyData
        );
    }

}