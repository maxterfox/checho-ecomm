<?php

class Validation
{
    private array $errors = [];

    public function required(string $field, string $value, string $label = ''): self
    {
        if (empty(trim($value))) {
            $this->errors[$field][] = ($label ?: $field) . ' is required.';
        }
        return $this;
    }

    public function email(string $field, string $value): self
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = 'Please enter a valid email address.';
        }
        return $this;
    }

    public function minLength(string $field, string $value, int $length): self
    {
        if (!empty($value) && mb_strlen($value) < $length) {
            $this->errors[$field][] = "{$field} must be at least {$length} characters.";
        }
        return $this;
    }

    public function maxLength(string $field, string $value, int $length): self
    {
        if (!empty($value) && mb_strlen($value) > $length) {
            $this->errors[$field][] = "{$field} must not exceed {$length} characters.";
        }
        return $this;
    }

    public function numeric(string $field, mixed $value): self
    {
        if (!empty($value) && !is_numeric($value)) {
            $this->errors[$field][] = "{$field} must be a number.";
        }
        return $this;
    }

    public function matches(string $field, string $value, string $otherField, string $otherValue): self
    {
        if ($value !== $otherValue) {
            $this->errors[$field][] = "{$field} does not match {$otherField}.";
        }
        return $this;
    }

    public function unique(string $field, string $value, string $table, string $column, ?int $excludeId = null): self
    {
        if (empty($value)) {
            return $this;
        }

        $db = \App\Core\Database::getInstance();
        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";
        $params = ['value' => $value];

        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $result = $db->fetch($sql, $params);

        if ((int) $result['count'] > 0) {
            $this->errors[$field][] = "This {$field} is already taken.";
        }

        return $this;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }
}
