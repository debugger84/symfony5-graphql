<?php

namespace App\Infra\Request;

use App\Infra\Request\Exception\InvalidRequestDataException;
use App\Infra\Request\Exception\UserRequiredException;
use App\Infra\Request\RequestObject\RequestObjectInterface;
use Exception;
use Generator;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class RequestObjectResolver implements ArgumentValueResolverInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * RequestObjectResolver constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }


    public function supports(Request $request, ArgumentMetadata $argument)
    {
        if ($argument->getType() === null) {
            return false;
        }
        return is_subclass_of($argument->getType(), RequestObjectInterface::class);
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return Generator
     * @throws ExceptionInterface
     * @throws InvalidRequestDataException
     * @throws UserRequiredException
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $data = $this->getDataFromRequest($request);
        $normalizer = new PropertyNormalizer();
        if ($argument->getType() === null) {
            throw new Exception('Argument type is null');
        }
        $dto = $normalizer->denormalize($data, $argument->getType());

        $messages = $this->validateDTO($dto);
        if ($messages) {
            $e = new InvalidRequestDataException($messages);
            throw $e;
        }

        yield $dto;
    }

    private function getDataFromRequest(Request $request): array
    {
        $data = $request->request->all();
        if (!$data && (
                Request::METHOD_POST === $request->getMethod() ||
                Request::METHOD_PUT === $request->getMethod() ||
                Request::METHOD_PATCH === $request->getMethod())
        ) {
            $data = json_decode($request->getContent(), true);
        }
        if (Request::METHOD_GET === $request->getMethod()) {
            $data = $request->query->all();
        }
        if (!$data) {
            $data = [];
        }

        return $data;
    }

    /**
     * @param stdClass $dto
     * @return array
     */
    private function validateDTO($dto): array
    {
        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($dto);
        if (0 !== count($errors)) {
            $messages = [];
            foreach ($errors as $error) {
                $property = $error->getPropertyPath();
                $messages[$property] = $error->getMessage();
            }

            return $messages;
        }

        return [];
    }
}
