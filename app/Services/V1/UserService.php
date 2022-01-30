<?php

namespace App\Services\V1;

use App\Http\Requests\V1\UserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected $userRepository;

    /**
     * Creates the instance of service
     *
     * @param \App\Repositories\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Store information of a new User
     *
     * @param \App\Http\Requests\V1\UserRequest $request
     * @return \App\Models\User
     */
    public function storeUser(UserRequest $request): User
    {
        try {
            $user = new User();

            DB::beginTransaction();

            $picture = $this->updateProfilePicture($request);

            $user->fill([
                'name'            => $request->name,
                'last_name'       => $request->last_name,
                'sur_name'        => $request->sur_name,
                'birthday'        => $request->birthday,
                'rfc'             => $request->rfc,
                'profile_picture' => $picture,
            ]);

            $this->userRepository->save($user);

            DB::commit();

            return $user->refresh();

        } catch (\Throwable $th) {

            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Update user informaciom
     *
     * @param \App\Http\Requests\V1\UserRequest $request
     * @param \App\Models\User
     * @return \App\Models\User
     */
    public function updateUser(UserRequest $request, User $user): User
    {
        try {

            DB::beginTransaction();

            $oldPicture = $user->profile_picture;

            $picture = $this->updateProfilePicture($request);

            $user->fill([
                'name'      => $request->name,
                'last_name' => $request->last_name,
                'sur_name'  => $request->sur_name,
                'birthday'  => $request->birthday,
                'rfc'       => $request->rfc,
            ]);

            if ($picture) {

                $user->profile_picture = $picture;
            }

            $this->deleteOldProfilePicture($oldPicture);

            $this->userRepository->save($user);

            DB::commit();

            return $user->refresh();

        } catch (\Throwable $th) {

            DB::rollBack();
            throw $th;
        }
    }

    /**
     * store profile picture with file given
     *
     * @param UserRequest $request
     * @return string|null
     */
    public function updateProfilePicture(UserRequest $request): ?string
    {
        return '';
    }

    public function deleteOldProfilePicture(string $path): void
    {
        # code...
    }

}
