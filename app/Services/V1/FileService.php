<?php

namespace App\Services\V1;

use App\Http\Requests\V1\FileRequest;
use App\Http\Requests\V1\ImageRequest;
use App\Models\File;
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

        $this->profileImagesPath = 'users/profile_picture';
        $this->filesPath         = 'users/files';
    }

    public function storeProfileImage(ImageRequest $request, User $user): ?User
    {
        $oldImage  = null;
        $oldPath   = null;
        $imageName = null;

        if (!$request->hasFile('image')) {
            return null;
        }

        try {

            DB::beginTransaction();

            $oldImage = $user->profile_picture;

            $imageName = Str::random(25) . '.jpg';
            $path      = "{$this->profileImagesPath}/{$imageName}";

            Storage::disk('public')->put($path, $request->file('image'));

            if ($oldImage) {
                $oldPath = "{$this->profileImagesPath}/{$oldImage}";
                Storage::delete($oldPath);
            }

            $user->profile_picture = $imageName;

            DB::commit();

            return $this->userRepository->save($user);

        } catch (\Throwable $th) {

            DB::rollback();

            throw $th;
        }
    }

    public function storeResumeFile(FileRequest $request): ?File
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

            $now = Carbon::now()->format('dmY');

            $resume = $request->file('resume');

            $fileName = Str::random(5) . "_{$now}.{$resume->getClientOriginalExtension()}";

            $resume->storeAs($this->filesPath, $fileName, 'local');

            $newResume = new File();

            $newResume->fill([
                'file'    => $fileName,
                'user_id' => $request->userId,
            ]);

            $newResume = $this->fileRepository->save($newResume);

            if ($oldResume) {
                $oldPath = "{$this->profileImagesPath}/{$oldResume->path}";
                Storage::delete($oldPath);
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
