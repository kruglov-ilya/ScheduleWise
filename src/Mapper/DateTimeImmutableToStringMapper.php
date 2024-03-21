<?php

namespace App\Mapper;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateTimeImmutableToStringMapper implements DataTransformerInterface
{
    public function transform(mixed $dateTimeImmutable): mixed
    {
        if (null === $dateTimeImmutable) {
            return '';
        }

        return $dateTimeImmutable->format('Y-m-d H:i:s');
    }

    public function reverseTransform($dateTimeString): ?\DateTimeImmutable
    {
        if (!$dateTimeString) {
            return null;
        }

        try {
            return new \DateTimeImmutable($dateTimeString);
        } catch (\Exception $e) {
            throw new TransformationFailedException($e->getMessage());
        }
    }
}