<?php

namespace App\Services\V1;

use App\Http\Requests\V1\UserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService
{
    protected $userRepository;
    protected $profileImagesPath;

    /**
     * Creates the instance of service
     *
     * @param \App\Repositories\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository    = $userRepository;
        $this->profileImagesPath = 'users/profile_picture';
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
                'last_name'       => $request->lastName,
                'sur_name'        => $request->surName,
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
                'last_name' => $request->lastName,
                'sur_name'  => $request->surName,
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
     * store profile picture with a file given
     *
     * @param UserRequest $request
     * @return string|null
     */
    public function updateProfilePicture(UserRequest $request): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        $imageName = Str::random(25) . '.jpg';

        $path = "{$this->profileImagesPath}/{$imageName}";

        Storage::disk('public')->put($path, $request->file('image'));

        return $imageName;

    }

    /**
     * Delete image file with path given
     *
     * @param string $imagePath
     * @return void
     */
    public function deleteOldProfilePicture(string $imagePath): void
    {
        $path = "{$this->profileImagesPath}/{$imagePath}";

        if (Storage::disk('public')->exists($path)) {
            Storage::delete($path);
        }
    }

}
