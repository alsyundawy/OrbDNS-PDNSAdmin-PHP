<?php
declare(strict_types=1);

namespace App\Core;

final class Validator
{
    private array $errors = [];

    public function required(string $field, mixed $value, string $message): self
    {
        if ($value === null || trim((string) $value) === '') {
            $this->errors[$field][] = $message;
        }
        return $this;
    }

    public function email(string $field, mixed $value, string $message): self
    {
        if ($value !== null && $value !== '' && filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[$field][] = $message;
        }
        return $this;
    }

    public function domain(string $field, mixed $value, string $message): self
    {
        $pattern = '/^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,}\.?$/';
        if (!is_string($value) || preg_match($pattern, $value) !== 1) {
            $this->errors[$field][] = $message;
        }
        return $this;
    }

    public function ipList(string $field, array $values, string $message): self
    {
        foreach ($values as $value) {
            if (filter_var($value, FILTER_VALIDATE_IP) === false) {
                $this->errors[$field][] = $message . ': ' . $value;
            }
        }
        return $this;
    }

    public function in(string $field, mixed $value, array $allowed, string $message): self
    {
        if (!in_array($value, $allowed, true)) {
            $this->errors[$field][] = $message;
        }
        return $this;
    }

    public function passes(): bool
    {
        return $this->errors === [];
    }

    public function first(): string
    {
        foreach ($this->errors as $messages) {
            return (string) ($messages[0] ?? 'Validation error');
        }
        return 'Validation error';
    }
}
