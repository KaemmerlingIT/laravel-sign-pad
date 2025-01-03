<?php

namespace Kaemmerlingit\LaravelSignPad\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Kaemmerlingit\LaravelSignPad\Contracts\CanBeSigned;
use Kaemmerlingit\LaravelSignPad\Exceptions\ModelHasAlreadyBeenSigned;
use Exception;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LaravelSignPadController
{
    use ValidatesRequests;

    public function __invoke(Request $request)
    {
        $validatedData = $this->validate($request, [
            'model' => ['required'],
            'sign' => ['required'],
            'id' => ['required'],
            'token' => ['required'],
            'part' => ['nullable', "max:255"],
        ]);

        $modelClass = $validatedData['model'];
        $decodedImage = base64_decode(explode(',', $validatedData['sign'])[1]);

        if (!$decodedImage) {
            throw new Exception('Invalid signature');
        }

        $model = app($modelClass)->findOrFail($validatedData['id']);

        $requiredToken = md5(config('app.key') . $modelClass);
        if ($validatedData['token'] !== $requiredToken) {
            abort(403, 'Invalid token');
        }

        if ($model instanceof CanBeSigned && $model->hasBeenSigned($validatedData['part'] ?? null)) {
            throw new ModelHasAlreadyBeenSigned;
        }

        $uuid = Str::uuid()->toString();
        $filename = "{$uuid}.png";
        $signature = $model->signatures()->create([
            'uuid' => $uuid,
            'from_ips' => $request->ips(),
            'filename' => $filename,
            'part' => $validatedData['part'] ?? null
        ]);

        if (config('sign-pad.encrypt_files')) {
            $decodedImage = Crypt::encryptString($decodedImage);
        }
        Storage::disk(config('sign-pad.disk_name'))->put($signature->getSignatureImagePath(), $decodedImage);

        if (config('sign-pad.redirect_route_name')) {
            return redirect()->route(config('sign-pad.redirect_route_name'), ['uuid' => $uuid]);
        }
        return response("", Response::HTTP_CREATED);
    }
}
