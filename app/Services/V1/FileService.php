<?php

namespace App\Services\V1;

use App\Http\Requests\V1\FileRequest;
use App\Http\Requests\V1\ImageRequest;
use App\Models\File as FileModel;
use App\Models\User;
use App\Repositories\FileRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    protected $userRepository;
    protected $fileRepository;

    protected $profileImagesPath;
    protected $filesPath;

    public function __construct(UserRepository $userRepository, FileRepository $fileRepository)
    {
        $this->userRepository = $userRepository;
        $this->fileRepository = $fileRepository;

        $this->profileImagesPath = app('profileImagesPath');
        $this->filesPath         = app('filesPath');
    }

    /**
     * Update user's profile picture and images files
     *
     * @param \App\Http\Requests\V1\ImageRequest $request
     * @return User|null
     */
    public function storeProfileImage(ImageRequest $request): ?User
    {
        $oldImage  = null;
        $oldPath   = null;
        $imageName = null;
        $image     = null;
        $user      = null;

        if (!$request->hasFile('image')) {
            return null;
        }

        try {

            DB::beginTransaction();

            $user = $this->userRepository->find($request->userId);

            $oldImage = $user->profile_picture;

            $image = $request->file('image');

            $imageName = Str::random(25) . '.jpg';

            $image->storeAs($this->profileImagesPath, $imageName, 'public');

            if ($oldImage) {
                $oldPath = "{$this->profileImagesPath}/{$oldImage}";
                Storage::disk('public')->delete($oldPath);
            }

            $user->profile_picture = $imageName;
            $this->userRepository->save($user);

            DB::commit();

            return $user->refresh();

        } catch (\Throwable $th) {

            DB::rollback();

            throw $th;
        }
    }

    /**
     * upates files of user's resumes
     *
     * @param FileRequest $request
     * @return FileModel|null
     */
    public function storeResumeFile(FileRequest $request): ?FileModel
    {
        $fileName  = null;
        $oldResume = null;
        $newResume = null;
        $now       = null;

        if (!$request->hasFile('resume')) {
            return null;
        }

        $oldResume = $this->fileRepository
            ->getFirstByUser($request->userId);

        try {

            DB::beginTransaction();

            $now = Carbon::now()->format('dmYhm');

            $resume = $request->file('resume');

            $fileName = Str::random(5) . "_{$now}.{$resume->getClientOriginalExtension()}";

            $resume->storeAs($this->filesPath, $fileName, 'public');

            $newResume = new FileModel();

            $newResume->fill([
                'file'    => $fileName,
                'user_id' => $request->userId,
            ]);

            $newResume = $this->fileRepository->save($newResume);

            if ($oldResume) {
                $oldPath = "{$this->filesPath}/{$oldResume->file}";
                Storage::disk('public')->delete($oldPath);
                $this->fileRepository->delete($oldResume);
            }

            DB::commit();

            return $newResume;

        } catch (\Throwable $th) {

            DB::rollback();

            throw $th;
        }
    }
}
