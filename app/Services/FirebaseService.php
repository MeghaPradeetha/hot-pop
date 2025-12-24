<?php

namespace App\Services;

use App\Models\User;
use Kreait\Firebase\Factory;

/**
 * Firebase Helper
 */
class FirebaseService
{

    protected $serviceAccount;
    protected $firebase;
    protected static $database;

    function __construct()
    {
        self::init();
    }

    private static function init()
    {
        $factory = new Factory();

        if (!isset(self::$database)) {

            $factory = (new Factory)->withServiceAccount(env('FIREBASE_CREDENTIALS', ''))
                ->withDatabaseUri(env('FIREBASE_DATABASE_URI'));

            self::$database = $factory->createDatabase();
        }
    }

    /**
     *      User Update
     * =========================================
     */

    public static function addNewUser($user)
    {
        $data = [
            'full_name'  => $user->name,
            'id'         => $user->id,
            'avatar_url' => $user->avatar_url,
        ];

        self::updateFirebase($data, false);
    }

    public static function updateUser($user)
    {
        $data = [
            'full_name'  => $user->name,
            'id'         => $user->id,
            'avatar_url' => $user->avatar2,
        ];

        self::updateFirebase($data, true);
    }

    public static function updateUserStatus($user, $status, $type = null, $channel = null, $receiver = null, $token = null)
    {
        $data = [
            'id'                 => $user->id,
            'call_cancel_status' => $status == User::CALL_DECLINED ? 'call_status_free' : "call_status_ringing",
            'call_status'        => $status == User::CALL_DECLINED ? 'call_status_free' : "call_status_ringing",
            'status'             => $status,
        ];

        if ($token || $status == User::CALL_DECLINED) {
            $data['call_type'] = $type;
            $data['caller_avatar'] = $receiver?->avatar2;
            $data['caller_name'] = $receiver?->name;
            $data['chat_with'] = $receiver?->id;
            $data['channel_name'] = $channel;
            $data['token'] = $token;
        }

        self::updateFirebase($data, true);
    }

    public static function updateUserChat($user)
    {
        $current = self::getFirebaseValue("users/{$user->id}/unread_chat_count") ?? 0;

        self::updateFirebase([
            'unread_chat_count' => $current + 1
        ], true);
    }

    public static function getFirebaseValue($path)
    {
        self::init();
        $ref = self::$database->getReference($path);
        return $ref->getValue();
    }

    /**
     * Private function - Summary of updateFirebase
     * @param mixed $data
     * @param mixed $isUpdate
     */
    protected static function updateFirebase($data, $isUpdate)
    {
        try {
            self::init();

            $path = 'users/' . $data['id'];

            $ref = self::$database->getReference($path);
            $isUpdate ? $ref->update($data) : $ref->set($data);

        } catch (\Throwable $th) {
            \Log::error('Error updating Firebase: ' . $th->getMessage());
            return false;
        }
    }
}