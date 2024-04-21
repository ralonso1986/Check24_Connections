<?php

declare(strict_types=1);

namespace App\Connections\Infrastructure\Http;

use Symfony\Component\HttpFoundation\Response;

class CreateThirdPartyRequestActionResponder
{
    private array $errors = [];
    private Response $response;

    public function loadError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function loadThirdPartyReq(string $data): void
    {
        $this->response = new Response($data, Response::HTTP_CREATED);
    }

    public function response(): Response
    {
        if (!empty($this->errors)) {
            return new Response(
                $this->errors[0],
                Response::HTTP_BAD_REQUEST,
            );
        }

        return $this->response;
    }
}
